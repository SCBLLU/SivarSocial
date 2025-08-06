<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImagenController extends Controller
{
    // Subida de imagen de post (a /uploads)
    public function store(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|max:20480', // 20 MB = 20480 KB
        ]);
        $imagen = $request->file('imagen');
        $nombreImagen = Str::uuid() . '.' . $imagen->extension();
        $imagen->move(public_path('uploads'), $nombreImagen);
        return response()->json(['imagen' => $nombreImagen]);
    }

    // Subida de imagen de perfil (a /perfiles)
    public function storePerfil(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|max:20480', // 20 MB = 20480 KB
        ]);
        $imagen = $request->file('imagen');
        $nombreImagen = Str::uuid() . '.' . $imagen->extension();
        $imagen->move(public_path('perfiles'), $nombreImagen);
        return response()->json(['imagen' => $nombreImagen]);
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