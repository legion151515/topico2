<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use Carbon\Carbon;

class GestionCitasController extends Controller
{
    /**
     * Lista de todas las citas
     */
    public function index(Request $request)
    {
        $query = Cita::with(['estudiante', 'personalAtendio']);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        $citas = $query->orderBy('fecha', 'desc')
                       ->orderBy('hora', 'desc')
                       ->paginate(20);

        // Estadísticas
        $estadisticas = [
            'pendientes' => Cita::pendientes()->count(),
            'aprobadas' => Cita::aprobadas()->count(),
            'hoy' => Cita::hoy()->count(),
        ];

        return view('gestion-citas.index', compact('citas', 'estadisticas'));
    }

    /**
     * Vista de calendario
     */
    public function calendario(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);

        $citas = Cita::with(['estudiante', 'personalAtendio'])
                     ->whereYear('fecha', $anio)
                     ->whereMonth('fecha', $mes)
                     ->get();

        return view('gestion-citas.calendario', compact('citas', 'mes', 'anio'));
    }

    /**
     * Ver detalle de cita
     */
    public function show(Cita $cita)
    {
        $cita->load(['estudiante', 'personalAtendio']);
        return view('gestion-citas.show', compact('cita'));
    }

    /**
     * Aprobar cita
     */
    public function aprobar(Cita $cita)
    {
        if ($cita->estado !== 'pendiente') {
            return redirect()->back()
                             ->with('error', 'Solo se pueden aprobar citas pendientes.');
        }

        $cita->update([
            'estado' => 'aprobada',
            'fecha_aprobacion' => now(),
            'atendido_por' => auth()->id(),
        ]);

        return redirect()->route('gestion-citas.index')
                         ->with('success', 'Cita aprobada exitosamente.');
    }

    /**
     * Rechazar cita
     */
    public function rechazar(Request $request, Cita $cita)
    {
        if ($cita->estado !== 'pendiente') {
            return redirect()->back()
                             ->with('error', 'Solo se pueden rechazar citas pendientes.');
        }

        $request->validate([
            'observaciones_personal' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $cita->update([
            'estado' => 'rechazada',
            'fecha_rechazo' => now(),
            'observaciones_personal' => $request->observaciones_personal,
            'atendido_por' => auth()->id(),
        ]);

        return redirect()->route('gestion-citas.index')
                         ->with('success', 'Cita rechazada.');
    }

    /**
     * Marcar cita como completada
     */
    public function completar(Request $request, Cita $cita)
    {
        if ($cita->estado !== 'aprobada') {
            return redirect()->back()
                             ->with('error', 'Solo se pueden completar citas aprobadas.');
        }

        $request->validate([
            'observaciones_personal' => ['nullable', 'string', 'max:500'],
        ]);

        $cita->update([
            'estado' => 'completada',
            'observaciones_personal' => $request->observaciones_personal,
            'atendido_por' => auth()->id(),
        ]);

        return redirect()->route('gestion-citas.index')
                         ->with('success', 'Cita marcada como completada.');
    }

    /**
     * Cancelar cita (por parte del personal)
     */
    public function cancelar(Request $request, Cita $cita)
    {
        if (!in_array($cita->estado, ['pendiente', 'aprobada'])) {
            return redirect()->back()
                             ->with('error', 'No se puede cancelar esta cita.');
        }

        $request->validate([
            'observaciones_personal' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $cita->update([
            'estado' => 'cancelada',
            'observaciones_personal' => $request->observaciones_personal,
            'atendido_por' => auth()->id(),
        ]);

        return redirect()->route('gestion-citas.index')
                         ->with('success', 'Cita cancelada.');
    }
}
