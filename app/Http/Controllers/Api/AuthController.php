<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Rules\EmailDomain;

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
            // Valido todos los campos requeridos para el registro
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:15|unique:users', // El username debe ser único
                'email' => ['required','string','email','max:255','unique:users',new EmailDomain('itca.edu.sv')], // solo acepta correos del itca
                'password' => 'required|string|min:8', // Mínimo 8 caracteres para la contraseña
            ]);

            // Creo el nuevo usuario con la contraseña hasheada por seguridad
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

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
}