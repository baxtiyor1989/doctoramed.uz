<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function captcha(Request $request)
    {
        $code = (string) random_int(10000, 99999);
        $codes = collect($request->session()->get('admin_login_captcha_codes', []))
            ->filter(fn (array $item) => ($item['expires_at'] ?? 0) >= now()->timestamp)
            ->push([
                'code' => $code,
                'expires_at' => now()->addMinutes(5)->timestamp,
            ])
            ->take(-3)
            ->values()
            ->all();

        $request->session()->put('admin_login_captcha_codes', $codes);

        $width = 180;
        $height = 58;
        $image = imagecreatetruecolor($width, $height);

        $background = imagecolorallocate($image, 255, 247, 248);
        $primary = imagecolorallocate($image, 183, 33, 45);
        $dark = imagecolorallocate($image, 36, 45, 62);
        $muted = imagecolorallocate($image, 228, 204, 208);

        imagefilledrectangle($image, 0, 0, $width, $height, $background);

        for ($i = 0; $i < 7; $i++) {
            imageline($image, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $muted);
        }

        for ($i = 0; $i < 90; $i++) {
            imagesetpixel($image, random_int(0, $width - 1), random_int(0, $height - 1), $muted);
        }

        $x = 24;
        foreach (str_split($code) as $index => $char) {
            imagestring($image, 5, $x, random_int(17, 25), $char, $index % 2 === 0 ? $primary : $dark);
            $x += random_int(26, 30);
        }

        ob_start();
        imagepng($image);
        $content = ob_get_clean();
        imagedestroy($image);

        return response($content, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'string'],
        ]);

        $captcha = trim($data['captcha']);
        $validCodes = collect($request->session()->get('admin_login_captcha_codes', []))
            ->filter(fn (array $item) => ($item['expires_at'] ?? 0) >= now()->timestamp)
            ->pluck('code')
            ->all();

        $captchaIsValid = collect($validCodes)->contains(fn (string $code) => hash_equals($code, $captcha));

        if (! $captchaIsValid) {
            return back()
                ->withErrors(['captcha' => 'Rasmdagi kod noto‘g‘ri kiritildi.'])
                ->onlyInput('login');
        }

        $request->session()->forget('admin_login_captcha_codes');
        $credentials = [
            'login' => $data['login'],
            'password' => $data['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()
            ->withErrors(['login' => 'Login yoki parol noto‘g‘ri.'])
            ->onlyInput('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
