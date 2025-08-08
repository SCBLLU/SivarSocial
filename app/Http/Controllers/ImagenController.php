<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImagenController extends Controller
{
    // Subida de imagen de post (a /uploads) - AUTOMÁTICO 1:1
    public function store(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|max:20480', // 20 MB = 20480 KB
        ]);

        $imagen = $request->file('imagen');
        $nombreImagen = Str::uuid() . '.jpg'; // Siempre guardar como JPG para consistencia

        // Crear manager de Intervention Image
        $manager = new ImageManager(new Driver());

        try {
            // Leer la imagen
            $imagenServidor = $manager->read($imagen);

            // FORZAR DIMENSIONES 1:1 (cuadrada) para todos los posts
            $targetSize = 1080; // Instagram standard

            // Obtener dimensiones originales
            $width = $imagenServidor->width();
            $height = $imagenServidor->height();

            // Si la imagen ya es cuadrada o muy cerca, solo redimensionar
            $aspectRatio = $width / $height;
            
            if (abs($aspectRatio - 1.0) < 0.05) {
                // La imagen ya es prácticamente cuadrada, solo redimensionar
                $imagenServidor->resize($targetSize, $targetSize);
            } else {
                // Calcular la escala para que quepa completamente en el cuadrado
                $scale = min($targetSize / $width, $targetSize / $height);
                $newWidth = (int)($width * $scale);
                $newHeight = (int)($height * $scale);

                // Redimensionar manteniendo proporciones (SIN recortar)
                $imagenServidor->scale($newWidth, $newHeight);

                // Crear un canvas cuadrado con fondo negro (estilo Instagram/TikTok)
                $canvas = $manager->create($targetSize, $targetSize)->fill('000000');

                // Calcular posición para centrar
                $x = (int)(($targetSize - $newWidth) / 2);
                $y = (int)(($targetSize - $newHeight) / 2);

                // Colocar la imagen centrada en el canvas
                $canvas->place($imagenServidor, 'top-left', $x, $y);
                
                // Usar el canvas como imagen final
                $imagenServidor = $canvas;
            }

            // Guardar con calidad optimizada
            $imagenServidor->save(public_path('uploads') . '/' . $nombreImagen, 85);
            
            return response()->json([
                'imagen' => $nombreImagen,
                'message' => 'Imagen procesada correctamente a formato 1:1',
                'size' => '1080x1080',
                'original_dimensions' => $width . 'x' . $height
            ]);
            
        } catch (\Exception $e) {
            // Si falla Intervention Image, usar método tradicional como fallback
            $imagen->move(public_path('uploads'), $nombreImagen);
            
            return response()->json([
                'imagen' => $nombreImagen,
                'message' => 'Imagen subida (procesamiento básico)',
                'size' => 'original',
                'note' => 'Se usó método de respaldo'
            ]);
        }
    }

    // Subida de imagen de perfil (a /perfiles) 
    public function storePerfil(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|max:20480', // 20 MB = 20480 KB
        ]);

        $imagen = $request->file('imagen');
        $nombreImagen = Str::uuid() . '.jpg'; // Siempre guardar como JPG

        // Crear manager de Intervention Image
        $manager = new ImageManager(new Driver());

        try {
            // Leer la imagen
            $imagenServidor = $manager->read($imagen);

            // Para perfiles, usar tamaño fijo cuadrado
            $imagenServidor->cover(400, 400);

            // Guardar con calidad alta para perfiles
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath, 90); // Calidad 90%

        } catch (\Exception $e) {
            // Si falla Intervention Image, usar método tradicional
            $imagen->move(public_path('perfiles'), $nombreImagen);
        }

        return response()->json([
            'imagen' => $nombreImagen,
            'message' => 'Imagen de perfil subida correctamente'
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
