<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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

    public function store(Request $request, User $user)
    {
        $request->request->add([
            'username' => Str::slug($request->username),
        ]);
        $request->validate([
            'username' => [
                'required',
                'string',
                'max:15',
                'unique:users,username,' . Auth::user()->id,
                'not_in:editar,manolo,
            admin,administrador,root,superadmin,super-administrador,super-adm',
                'regex:/^[a-zA-Z0-9_]+$/', // Solo permite letras, números y guiones bajos
            ],
        ]);

        // Procesar imagen solo si se subió
        if ($request->imagen) {
            $imagen = $request->file('imagen');

            $nombreImagen = Str::uuid() . "." . $imagen->extension(); //(UUID para nombre único)

            $manager = new ImageManager(new Driver()); // Instancia el manager
            $imagenServidor = $manager->read($imagen); // Lee la imagen
            $imagenServidor->cover(1000, 1000);

            // Guarda la imagen en el directorio 'uploads' dentro de 'public'
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }

        $user = User::find(Auth::id());
        $user->username = $request->username;
        if (isset($nombreImagen)) {
            $user->imagen = $nombreImagen;
        }
        $user->save();



        return redirect()->route('posts.index', ['user' => $user->username])->with('success', 'Perfil actualizado correctamente');
    }
}
