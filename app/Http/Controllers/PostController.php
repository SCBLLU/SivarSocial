<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends \Illuminate\Routing\Controller
{
    /* para proteger que no se pueda abrir el muro en otra página */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user)
    {
        return view('layouts.dashboard', [
            'user' => $user,
        ]);
    }
}
