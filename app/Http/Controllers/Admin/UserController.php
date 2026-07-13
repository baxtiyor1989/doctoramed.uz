<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'items' => User::query()->latest()->paginate(20),
            'roles' => User::roles(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', [
            'item' => new User(['role' => 'administrator']),
            'roles' => User::roles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($this->validatedData($request));

        return redirect()->route('admin.users.index')->with('status', 'Foydalanuvchi qo‘shildi.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', [
            'item' => $user,
            'roles' => User::roles(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $user->update($this->validatedData($request, $user));

        return redirect()->route('admin.users.index')->with('status', 'Foydalanuvchi yangilandi.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->with('status', 'O‘zingizni o‘chira olmaysiz.');
        }

        $user->delete();

        return back()->with('status', 'Foydalanuvchi o‘chirildi.');
    }

    private function validatedData(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:255', Rule::unique('users', 'login')->ignore($user)],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6', 'confirmed'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $data['email'] = null;

        return $data;
    }
}
