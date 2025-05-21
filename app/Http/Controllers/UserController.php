<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /* VALIDACIONES */
        /* validacion con el slug helper */
        $request ->request->add([
            'username' => Str::slug($request->username),
        ]);
        /* validaciones */
        $request->validate([
            'name' => 'required|string|max:10',
            'username' => 'required|string|max:15|unique:users',
            'email' => 'required|string|email|max:45|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:Male,Female',
            'profession' => 'required|string|max:50',
        ]);

        /* CREACION DE USUARIO */
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'image' => $request->file('image')->store('images', 'public'),
            'gender' => $request->gender,
            'profession' => $request->profession,
        ]);

/*         return response()->json($user, 201);
 */
        /* AUTENTICACION */
        Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);
        
        /* redireccionamiento */
        return redirect()
            ->route('posts.index')
            ->with('success', '¡Registro exitoso! Bienvenido a SivarSocial.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
