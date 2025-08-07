<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class RecoverController extends Controller
{
    /**
     * muestra la vista recuperar
     */

    /**
     * PASO 1
     * */
    public function index()
    {
        return view('auth.recover.recuperar');
    }

    /**
     * PASO 2 - LA VALIDACIÓN
     * */
    public function enviarCodigo(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
        return redirect()->back()
                ->withInput()
                ->with('status', 'El correo no existe en nuestros registros.');
        }

        // Genera un codigo random código
        $codigo = random_int(100000, 999999);

        // Guarda en la sesión
        Session::put('codigo_verificacion', $codigo);
        Session::put('email_verificacion', $request->email);

        // Encargado de enviar correo al usuario destino
        Mail::send([], [], function ($message) use ($request, $codigo) {
            $html = view('emails.codigo', [
                'codigo' => $codigo,
                'email' => $request->email
            ])->render();
            $message->to($request->email)
                    ->subject('Código de verificación')
                    ->html($html); // <- cambia esto
        });

        // Redirige a la vista de verificación
        return redirect()->route('code.verific');
    }

    /**
     * PASO 3
     * */
    public function index2()
    {
        if (!Session::has('codigo_verificacion')) {
            return redirect()->route('recuperar')->with('status', 'No se ha solicitado recuperación');
        }

        return view('auth.recover.code-verific');
    }

    /**
     * PASO 4 - VERIFICACIÓN
     * */
    public function validarCodigo(Request $request)
    {
        // Valida que se haya enviado el código
        $request->validate([
            'codigo' => ['required', 'digits:6'],
        ]);

        // Obtener el código esperado desde la sesión
        $codigoEsperado = Session::get('codigo_verificacion');
        $correo = Session::get('email_verificacion');

        /**
         *  ¿CODIGO INGRESADO ES = A CODIGO ENVIADO?
         * */

        if ($request->codigo == $codigoEsperado) {
            // Código correcto
            Session::put('codigo_verificado', true);
            return redirect()->route('restablecer'); 
        } else {
            // Código incorrecto
            return back()->with('status', 'El código ingresado es incorrecto')->withInput();
        }
    }

    /**
     * PASO 5
     * */
    public function index3()
    {
        if (!Session::has('codigo_verificado')) {
            return redirect()->route('code.verific')->with('status', 'No se ha solicitado recuperación');
        }
        return view('auth.recover.restablecer');
    }


    /**
     * PASO 6 - CAMBIO DE CONTRASEÑA
     * */
    public function restablecer(Request $request)
    {
        // Validar contraseña segura
        $request->validate([
            'password' => [
                'required', 'confirmed', 'min:8',
                'regex:/[a-z]/',     
                'regex:/[A-Z]/',    
                'regex:/[0-9]/',    
                'regex:/[\W_]/',   
            ],
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe tener mayúsculas, minúsculas, números y un carácter especial.',
        ]);

        // Se hace la petición para obtener correo
        $correo = Session::get('email_verificacion');

        if (!$correo) {
            return redirect()->route('recuperar')->with('status', 'No se ha solicitado recuperación.');
        }

        // Buscar el usuario de la petición por su email
        $usuario = User::where('email', $correo)->first();

        if (!$usuario) {
            return redirect()->route('recuperar')->with('status', 'El usuario no fue encontrado.');
        }

        // Actualizar la contraseña con su debido hash
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        // Enviar correo del usuario la confirmación
        $html = view('emails.verificacion', [
            'email' => $correo
        ])->render();

        Mail::send([], [], function ($message) use ($correo, $html) {
            $message->to($correo)
                    ->subject('Contraseña Cambiada')
                    ->html($html);
        });

        // Borra sessiones de estos pasos
        Session::forget(['email_verificacion', 'codigo_verificacion', 'codigo_verificado']);

        // Redirigir al login u otra ruta con éxito
        return redirect()->route('login')->with('success', 'Tu contraseña fue actualizada con éxito.');
    }
}
