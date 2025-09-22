<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\su_ad;
use App\Models\User;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SUController extends Controller
{
    public function dashboard()
    {

        $users = \App\Models\User::latest()->get();
        return view('su.dashboard', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        // Simulación: datos de usuario definidos directamente en PHP
        $user = su_ad::create([
            'name' => 'Mateyo Admin',
            'username' => 'mateyo',
            'email' => 'mateyo@example.com',
            'password' => bcrypt('./54777uSiVAra-l'),
            'is_admin' => true, // admin = 1
            'last_login' => now(), // fecha actual
            'password_verific_modify' => bcrypt('.//5777u51VAr-s0'),
            'imagen' => '8dbd213d-9d8f-48bb-a24e-e9b1a2be793f.jpg',
            'profession' => 'Desarrollador',
        ]);

        return "¡Registro de prueba exitoso! Usuario creado correctamente.";
    }

    public function info(User $user)
    {
        if (!$user->exists) {
            abort(404, 'Usuario no encontrado');
        }

        return view('su.perfil', [
            'user' => $user,
        ]);
    }

    // Agregar insignia al usuario
    public function addInsignia(Request $request, User $user)
    {
        // Validar
        $request->validate([
            'type' => 'required|in:Colaborador,Comunidad'
        ]);

        // Actualizar campo insignia
        $user->update([
            'insignia' => $request->type
        ]);

        return redirect()->route('su.info', $user->username)
            ->with('success', 'Insignia agregada correctamente');
    }

    // Editar insignia del usuario
    public function editInsignia(Request $request, User $user)
    {
        $request->validate([
            'type' => 'required|string|in:Colaborador,Comunidad',
        ]);

        $user->insignia = $request->type;
        $user->save();

        return back()->with('success', 'Insignia actualizada correctamente.');
    }

    // Eliminar insignia del usuario
    public function deleteInsignia(Request $request, User $user)
    {
        $request->validate([
            'pass_verific' => 'required|string',
        ]);

        // Traemos al super usuario logueado
        $su = auth()->guard('super')->user(); // si usas un guard "su"

        // Verificamos contraseña
        if (!$su || !Hash::check($request->pass_verific, $su->password_verific_modify)) {
            return back()->withErrors(['pass_verific' => 'La contraseña no es correcta'])->withInput();
        }

        // Si la contraseña es correcta, eliminamos la insignia
        $user->insignia = null;
        $user->save();

        return back()->with('success', 'Insignia eliminada correctamente.');
    }

    public function ads()
    {
        return view('su.anuncio');
    }

    public function create(Request $request)
    {
        // Validación
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:feature,update,info',
            'image_url' => 'nullable|string|max:255',
            'file' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:20480', // 20MB
            'action_text' => 'nullable|string|max:255',
            'action_url' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Manejar subida de archivo
        $imagePath = $request->image_url; // por defecto URL
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/banners', $filename);
            $imagePath = '/storage/banners/' . $filename;
        }

        // Crear banner
        Banner::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'type' => $request->input('type'),
            'image_url' => $imagePath,
            'action_text' => $request->input('action_text'),
            'action_url' => $request->input('action_url'),
            'is_active' => $request->input('is_active'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);

        return redirect()->route('su.ads')->with('success', 'Banner creado correctamente');
    }

    public function insig()
    {
        return view('su.insignia');
    }
}
