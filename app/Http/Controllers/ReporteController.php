<?php

namespace App\Http\Controllers;

use App\Models\Atencion;
use App\Models\Paciente;
use App\Models\Medicamento;
use App\Models\MotivoConsulta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function area()
    {
        $atenciones = Atencion::with('paciente.carrera', 'paciente.nivel')->get();

        $areas = $atenciones->groupBy(function($atencion) {
            if ($atencion->paciente && $atencion->paciente->carrera) {
                return $atencion->paciente->carrera->nombre;
            } elseif ($atencion->paciente && $atencion->paciente->nivel) {
                if ($atencion->paciente->nivel->nivel_escuela) {
                    return 'Escuela - ' . $atencion->paciente->nivel->nivel_escuela;
                } elseif ($atencion->paciente->nivel->otros_especificacion) {
                    return 'Otros - ' . $atencion->paciente->nivel->otros_especificacion;
                }
            }
            return 'Sin especificar';
        })->map(function($group) {
            return $group->count();
        });

        return view('reportes.area', compact('areas'));
    }

    public function enfermedad()
    {
        $atenciones = Atencion::with('motivo')->get();
        
        $enfermedades = $atenciones->groupBy(function($atencion) {
            return $atencion->motivo->nombre ?? $atencion->motivo_otro;
        })->map(function($group) {
            return $group->count();
        });

        return view('reportes.enfermedad', compact('enfermedades'));
    }

    public function stock()
    {
        $medicamentos = Medicamento::whereRaw('cantidad_stock < stock_minimo_alerta')->get();
        return view('reportes.stock', compact('medicamentos'));
    }

    // Generar PDF de reporte por área
    public function areaPDF()
    {
        $atenciones = Atencion::with('paciente.carrera', 'paciente.nivel')->get();

        $areas = $atenciones->groupBy(function($atencion) {
            if ($atencion->paciente && $atencion->paciente->carrera) {
                return $atencion->paciente->carrera->nombre;
            } elseif ($atencion->paciente && $atencion->paciente->nivel) {
                if ($atencion->paciente->nivel->nivel_escuela) {
                    return 'Escuela - ' . $atencion->paciente->nivel->nivel_escuela;
                } elseif ($atencion->paciente->nivel->otros_especificacion) {
                    return 'Otros - ' . $atencion->paciente->nivel->otros_especificacion;
                }
            }
            return 'Sin especificar';
        })->map(function($group) {
            return $group->count();
        });

        $pdf = Pdf::loadView('reportes.area-pdf', compact('areas'));
        return $pdf->download('Reporte_Atenciones_Area_' . date('Y-m-d') . '.pdf');
    }

    // Generar PDF de reporte por enfermedad
    public function enfermedadPDF()
    {
        $atenciones = Atencion::with('motivo')->get();

        $enfermedades = $atenciones->groupBy(function($atencion) {
            return $atencion->motivo->nombre ?? $atencion->motivo_otro;
        })->map(function($group) {
            return $group->count();
        });

        $pdf = Pdf::loadView('reportes.enfermedad-pdf', compact('enfermedades'));
        return $pdf->download('Reporte_Enfermedades_' . date('Y-m-d') . '.pdf');
    }

    // Generar PDF de reporte de stock
    public function stockPDF()
    {
        $medicamentos = Medicamento::whereRaw('cantidad_stock < stock_minimo_alerta')->get();

        $pdf = Pdf::loadView('reportes.stock-pdf', compact('medicamentos'));
        return $pdf->download('Reporte_Stock_Bajo_' . date('Y-m-d') . '.pdf');
    }

    // =====================================================
    // REPORTES MENSUALES
    // =====================================================

    /**
     * Mostrar formulario de selección de mes y año
     */
    public function mensual()
    {
        return view('reportes.mensual');
    }

    /**
     * Generar reporte mensual HTML
     */
    public function mensualReporte(Request $request)
    {
        $request->validate([
            'mes' => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:2020|max:2100',
        ]);

        $mes = $request->mes;
        $anio = $request->anio;

        // Obtener todas las atenciones del mes seleccionado
        $atenciones = Atencion::with([
            'paciente.carrera',
            'paciente.nivel',
            'motivo',
            'medicamentos'
        ])
        ->whereYear('fecha', $anio)
        ->whereMonth('fecha', $mes)
        ->orderBy('fecha', 'asc')
        ->orderBy('hora_entrada', 'asc')
        ->get();

        $nombreMes = $this->obtenerNombreMes($mes);

        return view('reportes.mensual-resultado', compact('atenciones', 'mes', 'anio', 'nombreMes'));
    }

    /**
     * Generar reporte mensual en PDF
     */
    public function mensualPDF(Request $request)
    {
        $request->validate([
            'mes' => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:2020|max:2100',
        ]);

        $mes = $request->mes;
        $anio = $request->anio;

        $atenciones = Atencion::with([
            'paciente.carrera',
            'paciente.nivel',
            'motivo',
            'medicamentos'
        ])
        ->whereYear('fecha', $anio)
        ->whereMonth('fecha', $mes)
        ->orderBy('fecha', 'asc')
        ->orderBy('hora_entrada', 'asc')
        ->get();

        $nombreMes = $this->obtenerNombreMes($mes);

        $pdf = Pdf::loadView('reportes.mensual-pdf', compact('atenciones', 'mes', 'anio', 'nombreMes'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Reporte_Mensual_' . $nombreMes . '_' . $anio . '.pdf');
    }

    /**
     * Generar reporte mensual en Excel
     */
    public function mensualExcel(Request $request)
    {
        $request->validate([
            'mes' => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:2020|max:2100',
        ]);

        $mes = $request->mes;
        $anio = $request->anio;
        $nombreMes = $this->obtenerNombreMes($mes);

        return Excel::download(
            new \App\Exports\ReporteMensualExport($mes, $anio),
            'Reporte_Mensual_' . $nombreMes . '_' . $anio . '.xlsx'
        );
    }

    // =====================================================
    // REPORTES ANUALES
    // =====================================================

    /**
     * Mostrar formulario de selección de año
     */
    public function anual()
    {
        return view('reportes.anual');
    }

    /**
     * Generar reporte anual HTML
     */
    public function anualReporte(Request $request)
    {
        $request->validate([
            'anio' => 'required|integer|min:2020|max:2100',
        ]);

        $anio = $request->anio;

        // Obtener todas las atenciones del año seleccionado
        $atenciones = Atencion::with([
            'paciente.carrera',
            'paciente.nivel',
            'motivo',
            'medicamentos'
        ])
        ->whereYear('fecha', $anio)
        ->orderBy('fecha', 'asc')
        ->orderBy('hora_entrada', 'asc')
        ->get();

        return view('reportes.anual-resultado', compact('atenciones', 'anio'));
    }

    /**
     * Generar reporte anual en PDF
     */
    public function anualPDF(Request $request)
    {
        $request->validate([
            'anio' => 'required|integer|min:2020|max:2100',
        ]);

        $anio = $request->anio;

        $atenciones = Atencion::with([
            'paciente.carrera',
            'paciente.nivel',
            'motivo',
            'medicamentos'
        ])
        ->whereYear('fecha', $anio)
        ->orderBy('fecha', 'asc')
        ->orderBy('hora_entrada', 'asc')
        ->get();

        $pdf = Pdf::loadView('reportes.anual-pdf', compact('atenciones', 'anio'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Reporte_Anual_' . $anio . '.pdf');
    }

    /**
     * Generar reporte anual en Excel
     */
    public function anualExcel(Request $request)
    {
        $request->validate([
            'anio' => 'required|integer|min:2020|max:2100',
        ]);

        $anio = $request->anio;

        return Excel::download(
            new \App\Exports\ReporteAnualExport($anio),
            'Reporte_Anual_' . $anio . '.xlsx'
        );
    }

    // =====================================================
    // REPORTES POR USUARIO
    // =====================================================

    /**
     * Mostrar formulario de selección de usuario y periodo
     */
    public function porUsuario()
    {
        $usuarios = \App\Models\User::where('tipo_usuario', '!=', 'estudiante')
            ->orderBy('name')
            ->get();
        return view('reportes.por-usuario', compact('usuarios'));
    }

    /**
     * Generar reporte de actividad por usuario
     */
    public function porUsuarioReporte(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'periodo' => 'required|in:total,mes,anio',
        ]);

        $usuario = \App\Models\User::findOrFail($request->user_id);
        $periodo = $request->periodo;

        // Consulta base
        $query = Atencion::with(['paciente.carrera', 'paciente.nivel', 'motivo', 'medicamentos'])
            ->where('user_id', $usuario->id);

        // Filtrar por periodo
        if ($periodo === 'mes') {
            $request->validate([
                'mes' => 'required|integer|min:1|max:12',
                'anio' => 'required|integer|min:2020|max:2100',
            ]);
            $query->whereYear('fecha', $request->anio)
                  ->whereMonth('fecha', $request->mes);
            $nombrePeriodo = $this->obtenerNombreMes($request->mes) . ' ' . $request->anio;
        } elseif ($periodo === 'anio') {
            $request->validate([
                'anio' => 'required|integer|min:2020|max:2100',
            ]);
            $query->whereYear('fecha', $request->anio);
            $nombrePeriodo = 'Año ' . $request->anio;
        } else {
            $nombrePeriodo = 'Total (Todas las atenciones)';
        }

        $atenciones = $query->orderBy('fecha', 'desc')
                           ->orderBy('hora_entrada', 'desc')
                           ->get();

        // Estadísticas
        $totalAtenciones = $atenciones->count();
        $pacientesUnicos = $atenciones->pluck('paciente_id')->unique()->count();

        // Medicamentos más utilizados
        $medicamentosUsados = [];
        foreach ($atenciones as $atencion) {
            foreach ($atencion->medicamentos as $med) {
                $nombre = $med->nombre;
                $cantidad = $med->pivot->cantidad_usada ?? 0;

                if (!isset($medicamentosUsados[$nombre])) {
                    $medicamentosUsados[$nombre] = 0;
                }
                $medicamentosUsados[$nombre] += $cantidad;
            }
        }
        arsort($medicamentosUsados);

        // Motivos más frecuentes
        $motivosFrecuentes = $atenciones->groupBy(function($atencion) {
            return $atencion->motivo->nombre ?? $atencion->motivo_otro ?? 'Sin especificar';
        })->map->count()->sortDesc();

        return view('reportes.por-usuario-resultado', compact(
            'usuario',
            'periodo',
            'nombrePeriodo',
            'atenciones',
            'totalAtenciones',
            'pacientesUnicos',
            'medicamentosUsados',
            'motivosFrecuentes'
        ));
    }

    /**
     * Generar PDF del reporte por usuario
     */
    public function porUsuarioPDF(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'periodo' => 'required|in:total,mes,anio',
        ]);

        $usuario = \App\Models\User::findOrFail($request->user_id);
        $periodo = $request->periodo;

        // Consulta base
        $query = Atencion::with(['paciente.carrera', 'paciente.nivel', 'motivo', 'medicamentos'])
            ->where('user_id', $usuario->id);

        // Filtrar por periodo
        if ($periodo === 'mes') {
            $query->whereYear('fecha', $request->anio)
                  ->whereMonth('fecha', $request->mes);
            $nombrePeriodo = $this->obtenerNombreMes($request->mes) . ' ' . $request->anio;
        } elseif ($periodo === 'anio') {
            $query->whereYear('fecha', $request->anio);
            $nombrePeriodo = 'Año ' . $request->anio;
        } else {
            $nombrePeriodo = 'Total';
        }

        $atenciones = $query->orderBy('fecha', 'desc')->get();
        $totalAtenciones = $atenciones->count();
        $pacientesUnicos = $atenciones->pluck('paciente_id')->unique()->count();

        $pdf = Pdf::loadView('reportes.por-usuario-pdf', compact(
            'usuario',
            'nombrePeriodo',
            'atenciones',
            'totalAtenciones',
            'pacientesUnicos'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('Reporte_Usuario_' . str_replace(' ', '_', $usuario->name) . '_' . $nombrePeriodo . '.pdf');
    }

    // =====================================================
    // MÉTODOS AUXILIARES
    // =====================================================

    /**
     * Obtener nombre del mes en español
     */
    private function obtenerNombreMes($numeroMes)
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        return $meses[$numeroMes] ?? 'Desconocido';
    }
}