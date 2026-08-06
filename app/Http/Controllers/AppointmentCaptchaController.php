<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentCaptchaController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $code = (string) random_int(10000, 99999);
        $codes = collect($request->session()->get('appointment_captcha_codes', []))
            ->filter(fn (array $item) => ($item['expires_at'] ?? 0) >= now()->timestamp)
            ->push(['code' => $code, 'expires_at' => now()->addMinutes(5)->timestamp])
            ->take(-3)
            ->values()
            ->all();

        $request->session()->put('appointment_captcha_codes', $codes);

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
        foreach (str_split($code) as $index => $character) {
            imagestring($image, 5, $x, random_int(17, 25), $character, $index % 2 === 0 ? $primary : $dark);
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
}
