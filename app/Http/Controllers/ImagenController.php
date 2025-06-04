<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        // Obtiene el archivo de la petición
        $imagen = $request->file('file');

        $nombreImagen = Str::uuid() . "." . $imagen->extension(); //(UUID para nombre único)

        $manager = new ImageManager(new Driver()); // Instancia el manager
        $imagenServidor = $manager->read($imagen); // Lee la imagen
        $imagenServidor->cover(1000, 1000);

        // Guarda la imagen en el directorio 'uploads' dentro de 'public'
        $imagenPath = public_path('uploads') . '/' . $nombreImagen;
        $imagenServidor->save($imagenPath);

        // Devuelve el nombre de la imagen como respuesta JSON
        return response()->json(['imagen' => $nombreImagen]);
    }
}
