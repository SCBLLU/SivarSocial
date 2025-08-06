<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Buscar Usuario.
     */
    public function buscar(Request $request)
    {
        $query = $request->input('buscar');
        
        $users = User::where('name', 'like', "%$query%")
                    ->orWhere('username', 'like', "%$query%")
                    ->get();

        return view('components.listar-perfiles', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar y guardar imagen temporal si hay errores
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Guardar temporalmente la imagen en storage/app/public/temp
            $imagePath = $image->store('temp', 'public');
        }

        // Validaciones
        try {
            $request->validate([
                'name' => 'required|string|max:10',
                'username' => 'required|string|max:15|unique:users',
                'email' => 'required|string|email|max:45|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:20480',
                'gender' => 'required|in:Male,Female',
                'profession' => 'required|string|max:50',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay error, guardar la ruta temporal en la sesión
            if ($imagePath) {
                session()->flash('temp_image', $imagePath);
            }
            throw $e;
        }

        // Procesar imagen con Intervention Image
        $nombreImagen = $request->imagen;
        // CREACION DE USUARIO
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'imagen' => $nombreImagen,
            'gender' => $request->gender,
            'profession' => $request->profession,
        ]);

        Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);
        return redirect()->route('posts.index', ['user' => $user->username])
            ->with('success', '¡Registro exitoso! Bienvenido a SivarSocial.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * elimina la cuenta del usuario autenticado y sus datos relacionados
     */
    public function destroy(Request $request)
    {
        // obtengo el usuario autenticado
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('home');
        }
        // Refrescar el modelo para asegurarse de que es una instancia de Eloquent
        $user = User::find($user->id);
        // elimino todos los posts del usuario y sus imágenes
        foreach ($user->posts as $post) {
            $imagePath = public_path('uploads/' . $post->imagen);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $post->delete();
        }
        // elimino la imagen de perfil si existe
        if ($user->imagen) {
            $perfilPath = public_path('perfiles/' . $user->imagen);
            if (file_exists($perfilPath)) {
                unlink($perfilPath);
            }
        }
        // cierro sesión antes de eliminar
        Auth::logout();
        // elimino el usuario
        $user->delete();
        // redirijo al home con mensaje
        return redirect()->route('home')->with('success', 'Cuenta eliminada correctamente');
    }
}
