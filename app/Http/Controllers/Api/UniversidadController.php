<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Universidad;
use App\Models\Carrera;
use Illuminate\Http\Request;

/**
 * Controlador de Universidades para la API de SivarSocial
 * Maneja la obtención de universidades y carreras para el formulario de registro
 */
class UniversidadController extends Controller
{
    /**
     * Obtiene la lista completa de universidades
     * Para mostrar en el ion-select del formulario de registro
     */
    public function index()
    {
        try {
            // Obtengo todas las universidades ordenadas alfabéticamente
            $universidades = Universidad::orderBy('nombre', 'asc')->get();

            return response()->json([
                'success' => true,
                'data' => $universidades
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener universidades',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene las carreras de una universidad específica
     * Para cargar dinámicamente las carreras según la universidad seleccionada
     * 
     * @param int $universidadId - ID de la universidad
     */
    public function getCarreras($universidadId)
    {
        try {
            // Busco la universidad con sus carreras relacionadas
            $universidad = Universidad::with('carreras')->find($universidadId);

            if (!$universidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Universidad no encontrada'
                ], 404);
            }

            // Ordeno las carreras alfabéticamente
            $carreras = $universidad->carreras()->orderBy('nombre', 'asc')->get();

            return response()->json([
                'success' => true,
                'data' => $carreras
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener carreras',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene todas las carreras disponibles
     * Por si necesitas mostrar todas las carreras sin filtrar por universidad
     */
    public function getAllCarreras()
    {
        try {
            $carreras = Carrera::orderBy('nombre', 'asc')->get();

            return response()->json([
                'success' => true,
                'data' => $carreras
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener carreras',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
