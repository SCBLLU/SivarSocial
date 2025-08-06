<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user instanceof \App\Models\User) {
            $user = \App\Models\User::find(Auth::id());
        }
        $hasChanges = false;

        // No aplicar slug al username - mantener tal como lo escribió el usuario

        // Validar los campos
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:15',
                'min:3', // Mínimo 3 caracteres
                'unique:users,username,' . $user->id,
                'not_in:editar,manolo,admin,administrador,root,superadmin,super-administrador,super-adm',
                'regex:/^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]$|^[a-zA-Z0-9]$/', // Permite letras, números, puntos, guiones bajos y guiones, pero no al inicio/final
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'profession' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:20480']
        ]);

        // Verificar y aplicar cambios solo si son diferentes
        if ($request->name !== $user->name) {
            $user->name = $request->name;
            $hasChanges = true;
        }

        if ($request->username !== $user->username) {
            $user->username = $request->username;
            $hasChanges = true;
        }

        if ($request->email !== $user->email) {
            $user->email = $request->email;
            $hasChanges = true;
        }

        if ($request->profession !== $user->profession) {
            $user->profession = $request->profession;
            $hasChanges = true;
        }

        // Actualizar contraseña solo si se proporcionó una nueva
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $hasChanges = true;
        }

        // Procesar imagen solo si se subió una nueva
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            $manager = new ImageManager(new Driver());
            $imagenServidor = $manager->read($imagen);
            $imagenServidor->cover(1000, 1000);

            // Guarda la imagen en el directorio 'perfiles' dentro de 'public'
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);

            // Eliminar imagen anterior si existe
            if ($user->imagen && file_exists(public_path('perfiles/' . $user->imagen))) {
                unlink(public_path('perfiles/' . $user->imagen));
            }

            $user->imagen = $nombreImagen;
            $hasChanges = true;
        }

        // Guardar solo si hay cambios
        if ($hasChanges) {
            $user->save();
            return redirect()->route('posts.index', ['user' => $user->username])
                ->with('success', 'Perfil actualizado correctamente');
        } else {
            return redirect()->route('posts.index', ['user' => $user->username])
                ->with('info', 'No se realizaron cambios en el perfil');
        }
    }
}
