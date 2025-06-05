<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:10',
            'username' => 'required|string|max:15|unique:users',
            'email' => 'required|string|email|max:45|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'imagen' => 'required|string', // Este será el nombre del archivo subido por Dropzone
            'gender' => 'required|in:Male,Female',
            'profession' => 'required|string|max:50',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede tener más de 10 caracteres',
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.max' => 'El nombre de usuario no puede tener más de 15 caracteres',
            'username.unique' => 'Este nombre de usuario ya está en uso',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingresa un correo electrónico válido',
            'email.max' => 'El correo electrónico es muy largo',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'imagen.required' => 'Debes subir una imagen de perfil',
            'gender.required' => 'Selecciona un género',
            'profession.required' => 'La profesión es obligatoria',
            'profession.max' => 'La profesión no puede tener más de 50 caracteres',
        ]);

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'imagen' => $request->imagen, // Nombre del archivo de imagen
            'gender' => $request->gender,
            'profession' => $request->profession,
        ]);

        // Autenticar usuario automáticamente
        Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect()->route('posts.index', ['user' => $user->username])
            ->with('success', '¡Registro exitoso! Bienvenido a SivarSocial.');
    }
}