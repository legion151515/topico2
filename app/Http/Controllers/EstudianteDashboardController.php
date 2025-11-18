<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstudianteDashboardController extends Controller
{
    /**
     * Mostrar dashboard de estudiante
     */
    public function index()
    {
        // Por ahora un mensaje temporal
        // Más adelante aquí irá el sistema de citas
        return view('estudiante.dashboard');
    }
}
