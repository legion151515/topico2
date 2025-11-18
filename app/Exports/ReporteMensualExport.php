<?php

namespace App\Exports;

use App\Models\Atencion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ReporteMensualExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths
{
    protected $mes;
    protected $anio;

    public function __construct($mes, $anio)
    {
        $this->mes = $mes;
        $this->anio = $anio;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Atencion::with([
            'paciente.carrera',
            'paciente.nivel',
            'motivo',
            'medicamentos'
        ])
        ->whereYear('fecha', $this->anio)
        ->whereMonth('fecha', $this->mes)
        ->orderBy('fecha', 'asc')
        ->orderBy('hora_entrada', 'asc')
        ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'Fecha',
            'Hora',
            'Nombre y Apellidos',
            'Carrera',
            'Semestre',
            'Edad',
            'Motivo de Consulta',
            'Medicamentos',
        ];
    }

    /**
     * @var Atencion $atencion
     */
    public function map($atencion): array
    {
        static $index = 0;
        $index++;

        // Nombre completo
        $nombreCompleto = '-';
        if ($atencion->paciente) {
            $nombreCompleto = trim($atencion->paciente->nombres . ' ' .
                                 $atencion->paciente->apellido_paterno . ' ' .
                                 $atencion->paciente->apellido_materno);
        }

        // Carrera
        $carrera = '-';
        if ($atencion->paciente && $atencion->paciente->carrera) {
            $carrera = $atencion->paciente->carrera->acronimo;
        } elseif ($atencion->paciente && $atencion->paciente->nivel) {
            if ($atencion->paciente->nivel->nivel_escuela) {
                $carrera = 'Escuela';
            } elseif ($atencion->paciente->nivel->otros_especificacion) {
                $carrera = 'Otros';
            }
        }

        // Semestre
        $semestre = '-';
        if ($atencion->paciente && $atencion->paciente->semestre) {
            $semestre = $atencion->paciente->semestre . '°';
        }

        // Edad
        $edad = '-';
        if ($atencion->paciente && $atencion->paciente->fecha_nacimiento) {
            $edad = Carbon::parse($atencion->paciente->fecha_nacimiento)->age . ' años';
        }

        // Motivo
        $motivo = '-';
        if ($atencion->motivo) {
            $motivo = $atencion->motivo->nombre;
        } elseif ($atencion->motivo_otro) {
            $motivo = $atencion->motivo_otro;
        }

        // Medicamentos
        $medicamentos = 'Sin medicamentos';
        if ($atencion->medicamentos && $atencion->medicamentos->count() > 0) {
            $medicamentos = $atencion->medicamentos->map(function ($med) {
                return $med->nombre . ' (Cant: ' . $med->pivot->cantidad . ')';
            })->implode(', ');
        }

        return [
            $index,
            Carbon::parse($atencion->fecha)->format('d/m/Y'),
            Carbon::parse($atencion->hora_entrada)->format('H:i'),
            $nombreCompleto,
            $carrera,
            $semestre,
            $edad,
            $motivo,
            $medicamentos,
        ];
    }

    /**
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo de encabezado
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3498db'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        $nombreMes = $meses[$this->mes] ?? 'Desconocido';
        return $nombreMes . ' ' . $this->anio;
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // #
            'B' => 12,  // Fecha
            'C' => 10,  // Hora
            'D' => 30,  // Nombre y Apellidos
            'E' => 12,  // Carrera
            'F' => 10,  // Semestre
            'G' => 10,  // Edad
            'H' => 30,  // Motivo
            'I' => 40,  // Medicamentos
        ];
    }
}
