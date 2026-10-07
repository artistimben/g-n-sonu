<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class SetupController extends Controller
{
    public function create()
    {
        abort_if(User::exists(), 404);

        return view('auth.setup');
    }

    public function store(Request $request)
    {
        abort_if(User::exists(), 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(10)->letters()->numbers()],
        ], [
            'name.required' => 'Adınızı ve soyadınızı girin.',
            'email.required' => 'E-posta adresinizi girin.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'email.unique' => 'Bu e-posta adresi kullanılıyor.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
            'password.min' => 'Şifre en az 10 karakter olmalı.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Yönetici hesabınız oluşturuldu. Ekibinizi ekleyebilirsiniz.');
    }
}
