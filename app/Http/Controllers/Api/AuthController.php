<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Rules\EmailDomain;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;

/**
 * Controlador de autenticación para la API de SivarSocial
 * Maneja el login, registro, logout y obtención de datos del usuario autenticado
 */
class AuthController extends Controller
{
    /**
     * Método de login para la API
     * Autentica al usuario y devuelve un token Sanctum para acceso a la API
     */
    public function login(Request $request)
    {
        // Valido que me envíen email y password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Busco al usuario por email en la base de datos
        $user = User::where('email', $request->email)->first();

        // Verifico que el usuario exista y que la contraseña coincida
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Las credenciales son incorrectas.'
            ], 401);
        }

        // Si las credenciales son correctas, creo un token de acceso para la API móvil
        $token = $user->createToken('mobile-app')->plainTextToken;

        // Devuelvo la respuesta con los datos del usuario y el token
        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    // Construyo la URL completa de la imagen de perfil o uso una por defecto
                    'imagen_url' => $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/usuario.svg'),
                    'profession' => $user->profession,
                    'insignia' => $user->insignia
                ],
                'token' => $token
            ],
            'message' => 'Login Exitoso'
        ]);
    }

    /**
     * Método de registro para nuevos usuarios de la API
     * Crea un nuevo usuario y devuelve un token para acceso inmediato
     */
    public function register(Request $request)
    {
        try {
            // Primera validación: campos básicos
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:15|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'universidad_id' => 'required|exists:universidades,id',
                'carrera_id' => 'required|exists:carreras,id',
            ]);

            // Obtener la universidad seleccionada
            $universidad = \App\Models\Universidad::find($request->universidad_id);

            // Validar que la universidad tenga un dominio configurado
            if (!$universidad->dominio) {
                return response()->json([
                    'success' => false,
                    'message' => 'La universidad seleccionada no tiene un dominio de correo configurado',
                    'errors' => [
                        'universidad_id' => ['Esta universidad no tiene configurado un dominio de correo institucional']
                    ]
                ], 422);
            }

            // Validar que el email pertenezca al dominio de la universidad
            $emailDomain = substr(strrchr($request->email, "@"), 1);
            if ($emailDomain !== $universidad->dominio) {
                return response()->json([
                    'success' => false,
                    'message' => 'El correo debe pertenecer al dominio de la universidad seleccionada',
                    'errors' => [
                        'email' => ["El correo debe tener el dominio @{$universidad->dominio}"]
                    ]
                ], 422);
            }

            // Verificar que la carrera pertenezca a la universidad seleccionada
            if (!$universidad->carreras()->where('carrera_id', $request->carrera_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La carrera seleccionada no pertenece a la universidad elegida',
                    'errors' => [
                        'carrera_id' => ['La carrera seleccionada no está disponible en esta universidad']
                    ]
                ], 422);
            }

            // Creo el nuevo usuario con la contraseña hasheada por seguridad
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'universidad_id' => $request->universidad_id,
                'carrera_id' => $request->carrera_id,
            ]);

            // Cargo las relaciones de universidad y carrera para incluirlas en la respuesta
            $user->load(['universidad', 'carrera']);

            // Genero un token inmediatamente para que pueda usar la app sin hacer login adicional
            $token = $user->createToken('mobile-app')->plainTextToken;

            // Devuelvo respuesta exitosa con el usuario creado y su token
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'token' => $token
                ],
                'message' => 'Usuario registrado exitosamente'
            ], 201);
        } catch (ValidationException $e) {
            // Si hay errores de validación, los devuelvo en formato JSON para la API
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Método de logout para la API
     * Elimina el token actual del usuario para cerrar la sesión
     */
    public function logout(Request $request)
    {
        // Elimino el token actual que está usando el usuario para cerrar su sesión
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout exitoso'
        ]);
    }

    /**
     * Método para obtener los datos del usuario autenticado
     * Devuelve la información del usuario que está usando la API
     */
    public function me(Request $request)
    {
        // Obtengo el usuario autenticado a través del token
        $user = $request->user();

        // Agrego la URL completa de la imagen de perfil para que la app móvil pueda mostrarla
        $user->imagen_url = $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/usuario.svg');

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
    public function edit(Request $request)
{
    try {
        // Validación de campos
        $request->validate([
            'id' => 'required',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'profession' => 'nullable|string|max:255',
            'univerdidad_id' => 'nullable|exists:universidades,id',
            'carrera_id' => 'nullable|exists:carreras,id',
            'gender' => 'nullable',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:20480' // Validar imagen de perfil

        ]);

        $user = User::find($request->id);
        if (!$user) {
            throw new Exception('Usuario no encontrado');
        }
        $username = User::where('username', $request->username)->first();
        if ($username && $username->id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'El username ya está en uso por otro usuario'
            ], 422);
        }

        // Actualizar los campos de texto
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->has('profession')) {
            $user->profession = $request->profession;
        }
        if ($request->has('universidad_id')) {
            $user->universidad_id = $request->universidad_id;
        }
        if ($request->has('carrera_id')) {
            $user->carrera_id = $request->carrera_id;
        }
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }

        // Procesar imagen de perfil si se envió una nueva
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = Str::uuid() . ".jpg";

            $manager = new ImageManager(new Driver());

            try {
                // Leer la imagen
                $imagenServidor = $manager->read($imagen);

                // Obtener dimensiones originales
                $width = $imagenServidor->width();
                $height = $imagenServidor->height();

                // Tamaño objetivo para perfiles
                $targetSize = 400;

                // Mantener proporciones originales
                $scale = min($targetSize / $width, $targetSize / $height);
                $newWidth = (int)($width * $scale);
                $newHeight = (int)($height * $scale);

                // Redimensionar manteniendo proporciones
                $imagenServidor->scale($newWidth, $newHeight);

                // Guardar con calidad alta para perfiles
                $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
                $imagenServidor->save($imagenPath, 90);

                // Eliminar imagen antigua si existe
                if ($user->imagen) {
                    $oldImagePath = public_path('perfiles/' . $user->imagen);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Actualizar el campo imagen
                $user->imagen = $nombreImagen;

            } catch (\Exception $e) {
                // Si falla Intervention Image, usar método tradicional
                $imagen->move(public_path('perfiles'), $nombreImagen);
                
                // Eliminar imagen antigua si existe
                if ($user->imagen) {
                    $oldImagePath = public_path('perfiles/' . $user->imagen);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $user->imagen = $nombreImagen;
            }
        }

        // Guardar cambios en la base de datos
        $user->save();

        // Agregar URL completa de la imagen de perfil
        $user->imagen_url = $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/usuario.svg');

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Usuario actualizado exitosamente'
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->getMessage()
        ], 422);
    }
}

}
