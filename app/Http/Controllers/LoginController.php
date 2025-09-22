<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\su_ad; 
use App\Models\User;

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

        // 1️⃣ Verificar credenciales en superusuarios
        if (Auth::guard('super')->attempt($request->only('email', 'password'), $request->remember)) {
            return redirect()->route('su.dash');
        }

        // 2️⃣ Verificar credenciales en usuarios normales
        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            return redirect()->route('posts.index', Auth::user());
        }

        // 3️⃣ Si nada coincide → error
        return back()
            ->with('status', 'Las credenciales no coinciden')
            ->withInput($request->only('email'));
    }
}
