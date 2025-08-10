<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ColaboradoresController extends Controller
{
    public function index()
    {
        // La vista ahora maneja directamente los datos con PHP simple
        return view('colaboradores.index');
    }

    public function dynamic()
    {
        return view('colaboradores.index');
    }

    public function getColaboradores(Request $request): JsonResponse
    {
        try {
            // Obtener colaboradores con paginación optimizada
            $colaboradores = User::where('insignia', 'Colaborador')
                ->select(['id', 'name', 'username', 'imagen', 'profession', 'insignia', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            // Transformar los datos para optimizar la respuesta
            $colaboradoresData = $colaboradores->map(function ($colaborador) {
                return [
                    'id' => $colaborador->id,
                    'name' => $colaborador->name,
                    'username' => $colaborador->username,
                    'imagen_url' => $colaborador->imagen_url,
                    'profession' => $colaborador->profession ?? 'Colaborador',
                    'insignia' => $colaborador->insignia,
                    'profile_url' => route('posts.index', $colaborador->username),
                    'roles' => $this->getColaboradorRoles($colaborador),
                ];
            });

            return response()->json([
                'success' => true,
                'colaboradores' => $colaboradoresData,
                'pagination' => [
                    'current_page' => $colaboradores->currentPage(),
                    'last_page' => $colaboradores->lastPage(),
                    'per_page' => $colaboradores->perPage(),
                    'total' => $colaboradores->total(),
                    'has_more' => $colaboradores->hasMorePages(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los colaboradores'
            ], 500);
        }
    }
}
