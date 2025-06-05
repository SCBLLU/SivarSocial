<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $user)
    {
        // El usuario autenticado sigue al usuario recibido
        $user->followers()->attach(Auth::id());
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Follower $follower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Follower $follower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Follower $follower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // El usuario autenticado deja de seguir al usuario recibido
        $user->followers()->detach(Auth::id());
        return back();
    }
}
