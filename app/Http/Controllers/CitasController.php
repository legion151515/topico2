<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use Carbon\Carbon;

class CitasController extends Controller
{
    /**
     * Mostrar formulario para agendar cita
     */
    public function index()
    {
        // Obtener citas del estudiante
        $citas = Cita::where('estudiante_id', auth()->id())
                     ->with('personalAtendio')
                     ->orderBy('fecha', 'desc')
                     ->orderBy('hora', 'desc')
                     ->get();

        return view('estudiante.citas.index', compact('citas'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('estudiante.citas.create');
    }

    /**
     * Guardar nueva cita
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'motivo' => ['required', 'string', 'min:10', 'max:500'],
            'observaciones_estudiante' => ['nullable', 'string', 'max:1000'],
        ]);

        // Validar que la hora esté dentro del horario de atención (8 AM - 1 PM)
        $hora = Carbon::createFromFormat('H:i', $request->hora);
        $horaInicio = Carbon::createFromTime(8, 0);
        $horaFin = Carbon::createFromTime(13, 0);

        if ($hora->lt($horaInicio) || $hora->gte($horaFin)) {
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['hora' => 'El horario de atención es de 8:00 AM a 1:00 PM']);
        }

        // Validar que no sea domingo
        $fecha = Carbon::parse($request->fecha);
        if ($fecha->isSunday()) {
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['fecha' => 'No se pueden agendar citas los domingos']);
        }

        // Validar que no haya otra cita en la misma fecha y hora
        $citaExistente = Cita::where('fecha', $request->fecha)
                             ->where('hora', $request->hora)
                             ->whereIn('estado', ['pendiente', 'aprobada'])
                             ->exists();

        if ($citaExistente) {
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['hora' => 'Ya existe una cita agendada para esta fecha y hora. Por favor selecciona otro horario.']);
        }

        Cita::create([
            'estudiante_id' => auth()->id(),
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'motivo' => $request->motivo,
            'observaciones_estudiante' => $request->observaciones_estudiante,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('estudiante.citas.index')
                         ->with('success', 'Cita agendada exitosamente. Espera la confirmación del personal médico.');
    }

    /**
     * Cancelar cita
     */
    public function cancelar(Cita $cita)
    {
        // Verificar que la cita pertenezca al estudiante autenticado
        if ($cita->estudiante_id !== auth()->id()) {
            abort(403, 'No tienes permiso para cancelar esta cita.');
        }

        // Solo se pueden cancelar citas pendientes o aprobadas
        if (!in_array($cita->estado, ['pendiente', 'aprobada'])) {
            return redirect()->route('estudiante.citas.index')
                             ->with('error', 'No puedes cancelar esta cita.');
        }

        $cita->update(['estado' => 'cancelada']);

        return redirect()->route('estudiante.citas.index')
                         ->with('success', 'Cita cancelada exitosamente.');
    }
}
