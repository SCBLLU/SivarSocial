<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Validation\ValidatesRequests;

class LoginController extends Controller
{
    use ValidatesRequests;
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->remember)) {
            // Si las credenciales no coinciden, redirigir de vuelta con un mensaje de error
            return back()
                ->with('status', 'Las credenciales no coinciden')
                ->withInput($request->only('email'));
        }

        return redirect()->route('posts.index', Auth::user()); // redirigir al muro del usuario autenticado
    }
}
