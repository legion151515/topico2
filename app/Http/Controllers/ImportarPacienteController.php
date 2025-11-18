<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Carrera;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportarPacienteController extends Controller
{
    /**
     * Mostrar formulario de importación
     */
    public function index()
    {
        return view('pacientes.importar');
    }

    /**
     * Procesar archivo Excel oficial del instituto
     */
    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls|max:5120', // Máx 5MB
            'categoria' => 'required|in:Tecnológico,Pedagógico',
            'carrera_id' => 'required|exists:carreras,id',
            'semestre' => 'required|string|max:10',
        ]);

        try {
            $archivo = $request->file('archivo');
            $categoria = $request->categoria;
            $carreraId = $request->carrera_id;
            $semestre = $request->semestre;

            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($archivo->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            // Determinar desde qué fila empiezan los datos
            // Pedagógico: fila 9 | Tecnológico: fila 10
            $filaInicio = ($categoria === 'Pedagógico') ? 9 : 10;

            $importados = 0;
            $duplicados = 0;
            $errores = [];

            DB::beginTransaction();

            // Procesar cada fila de estudiantes
            for ($fila = $filaInicio; $fila <= $highestRow; $fila++) {
                try {
                    // Extraer DNI de las columnas B hasta I (índices 1-8)
                    $dni = '';
                    for ($col = 1; $col <= 8; $col++) {
                        $celda = $worksheet->getCellByColumnAndRow($col, $fila);
                        $valor = trim($celda->getValue() ?? '');
                        $dni .= $valor;
                    }

                    // Validar que el DNI tenga 8 dígitos
                    if (strlen($dni) != 8 || !ctype_digit($dni)) {
                        // Saltar filas vacías o inválidas
                        if (!empty($dni)) {
                            $errores[] = "Fila {$fila}: DNI inválido ({$dni})";
                        }
                        continue;
                    }

                    // Extraer Apellidos y Nombres de la columna J (índice 9)
                    $apellidosNombres = trim($worksheet->getCellByColumnAndRow(9, $fila)->getValue() ?? '');

                    if (empty($apellidosNombres)) {
                        continue; // Saltar si no hay nombre
                    }

                    // Separar apellidos y nombres
                    // Formato: "APELLIDO1 APELLIDO2, Nombre1 Nombre2"
                    $partes = explode(',', $apellidosNombres, 2);
                    $apellido = trim($partes[0] ?? '');
                    $nombre = trim($partes[1] ?? '');

                    // Si no hay coma, intentar dividir por espacios (tomar primeros 2 como apellidos)
                    if (empty($nombre)) {
                        $palabras = explode(' ', $apellidosNombres);
                        if (count($palabras) >= 3) {
                            $apellido = implode(' ', array_slice($palabras, 0, 2));
                            $nombre = implode(' ', array_slice($palabras, 2));
                        } else {
                            $apellido = $palabras[0] ?? '';
                            $nombre = $palabras[1] ?? '';
                        }
                    }

                    // Verificar si el paciente ya existe
                    $pacienteExistente = Paciente::where('dni', $dni)->first();
                    if ($pacienteExistente) {
                        $duplicados++;
                        continue;
                    }

                    // Crear paciente
                    $paciente = Paciente::create([
                        'dni' => $dni,
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'edad' => 18, // Edad por defecto
                        'carrera_id' => $carreraId,
                    ]);

                    // Crear registro en tabla niveles
                    Nivel::create([
                        'paciente_id' => $paciente->id,
                        'categoria' => $categoria,
                        'semestre' => $semestre,
                        'nivel_escuela' => null,
                        'grado' => null,
                        'anios' => null,
                        'otros_especificacion' => null,
                    ]);

                    $importados++;

                } catch (\Exception $e) {
                    $errores[] = "Fila {$fila}: Error al procesar - " . $e->getMessage();
                }
            }

            DB::commit();

            $mensaje = "✅ Importación completada: {$importados} estudiante(s) importado(s).";
            if ($duplicados > 0) {
                $mensaje .= " {$duplicados} estudiante(s) omitido(s) por DNI duplicado.";
            }
            if (count($errores) > 0) {
                $mensaje .= " " . count($errores) . " error(es) encontrado(s).";
            }

            return redirect()->route('pacientes.importar')
                           ->with('success', $mensaje)
                           ->with('errores', $errores);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pacientes.importar')
                           ->with('error', 'Error al procesar el archivo Excel: ' . $e->getMessage());
        }
    }

    /**
     * Descargar plantilla de ejemplo en formato Excel
     */
    public function descargarPlantilla()
    {
        $contenido = "INSTRUCCIONES PARA IMPORTAR ESTUDIANTES\n\n";
        $contenido .= "PASO 1: Selecciona la categoría (Tecnológico o Pedagógico)\n";
        $contenido .= "PASO 2: Selecciona la carrera correspondiente\n";
        $contenido .= "PASO 3: Selecciona el semestre\n";
        $contenido .= "PASO 4: Sube el archivo Excel oficial del instituto (Lista Oficial 2025-I)\n\n";
        $contenido .= "IMPORTANTE:\n";
        $contenido .= "- Sube el archivo Excel SIN MODIFICAR tal como lo recibes del instituto\n";
        $contenido .= "- El sistema leerá automáticamente:\n";
        $contenido .= "  * DNI de las columnas B hasta I (8 dígitos)\n";
        $contenido .= "  * Apellidos y Nombres de la columna J\n";
        $contenido .= "  * Iniciará desde la fila 9 (Pedagógico) o fila 10 (Tecnológico)\n\n";
        $contenido .= "FORMATO ESPERADO DEL EXCEL:\n";
        $contenido .= "Columnas B-I: Cada dígito del DNI en una celda separada\n";
        $contenido .= "Columna J: APELLIDOS, Nombres\n\n";
        $contenido .= "EJEMPLO DE FILA EN EL EXCEL:\n";
        $contenido .= "| A | B | C | D | E | F | G | H | I | J                              |\n";
        $contenido .= "| 1 | 7 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | HUAMAN LOPEZ, Maria Elena      |\n";

        $nombreArchivo = 'INSTRUCCIONES_Importar_Estudiantes.txt';

        return response($contenido, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
            'Content-Length' => strlen($contenido),
        ]);
    }
}
