<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|max:2048', // 2MB máximo
        ]);

        $imagen = $request->file('imagen');
        
        // Generar nombre único para la imagen
        $nombreImagen = Str::uuid() . '.' . $imagen->extension();
        
        // Crear instancia del ImageManager con driver GD
        $manager = new ImageManager(new Driver());
        
        // Procesar imagen
        $imagenServidor = $manager->read($imagen);
        $imagenServidor->cover(1000, 1000); // Redimensionar manteniendo aspecto
        
        // Crear directorio si no existe
        $directorioUploads = public_path('uploads');
        if (!file_exists($directorioUploads)) {
            mkdir($directorioUploads, 0755, true);
        }
        // Guardar imagen en public/uploads
        $imagenPath = $directorioUploads . '/' . $nombreImagen;
        $imagenServidor->save($imagenPath);
        
        return response()->json([
            'imagen' => $nombreImagen,
            'url' => asset('uploads/' . $nombreImagen)
        ]);
    }
    
    public function destroy(Request $request)
    {
        $imagen = $request->get('imagen');
        
        if ($imagen) {
            $imagenPath = public_path('uploads') . '/' . $imagen;
            if (file_exists($imagenPath)) {
                unlink($imagenPath);
            }
        }
        
        return response()->json(['mensaje' => 'Imagen eliminada']);
    }
}