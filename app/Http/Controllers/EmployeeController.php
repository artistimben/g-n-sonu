<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->orderBy('name')->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(10)->letters()->numbers()],
        ], [
            'name.required' => 'Çalışan adını girin.',
            'email.required' => 'E-posta adresini girin.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
            'password.min' => 'Şifre en az 10 karakter olmalı.',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'employee',
        ]);

        return redirect()->route('employees.index')->with('success', 'Çalışan hesabı oluşturuldu. Giriş bilgilerini çalışanla paylaşabilirsiniz.');
    }
}
