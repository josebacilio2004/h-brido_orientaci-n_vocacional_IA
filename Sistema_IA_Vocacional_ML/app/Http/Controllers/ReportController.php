<?php

namespace App\Http\Controllers;

use App\Models\StudentGrade;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar página de reportes disponibles
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Solo orientadores y admins pueden ver reportes
            if (!in_array($user->role, ['admin'])) {
                return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder a reportes');
            }

            return view('reports.index');
        } catch (\Exception $e) {
            Log::error('[v0] Error en ReportController@index: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar reportes');
        }
    }

    /**
     * Descargar reporte de estudiantes en CSV
     */
    public function downloadStudentsCSV()
    {
        try {
            $user = Auth::user();
            if (!in_array($user->role, ['admin' ])) {
                return redirect()->back()->with('error', 'No tienes permiso');
            }

            $students = User::where('role', 'student')->get();
            
            $csv = "ID,Nombre,Grado,Escuela,Email,Fecha de Creación\n";
            
            foreach ($students as $student) {
                $csv .= "\"{$student->id},\"{$student->name}\",\"{$student->grade}\",\"{$student->school}\",\"{$student->email}\",\"{$student->created_at->format('Y-m-d')}\"\n";
            }

            return response($csv, 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="estudiantes-' . date('Y-m-d-His') . '.csv"');
        } catch (\Exception $e) {
            Log::error('[v0] Error descargando CSV de estudiantes: ' . $e->getMessage());
            return back()->with('error', 'Error al descargar reporte');
        }
    }

    /**
     * Descargar reporte de notas en CSV
     */
    public function downloadGradesCSV()
    {
        try {
            $user = Auth::user();
            if (!in_array($user->role, ['admin' ])) {
                return redirect()->back()->with('error', 'No tienes permiso');
            }

            $grades = StudentGrade::with('user')
                ->where('academic_year', date('Y'))
                ->get();

            $csv = "ID Estudiante,Nombre,Grado,Escuela,Matemática,Comunicación,Sociales,Tecnología,Personal,Cívica,Física,Inglés,Trabajo,Promedio\n";

            foreach ($grades as $grade) {
                $average = (
                    $grade->nota_matematica +
                    $grade->nota_comunicacion +
                    $grade->nota_ciencias_sociales +
                    $grade->nota_ciencia_tecnologia +
                    $grade->nota_desarrollo_personal +
                    $grade->nota_ciudadania_civica +
                    $grade->nota_educacion_fisica +
                    $grade->nota_ingles +
                    $grade->nota_educacion_trabajo
                ) / 9;

                $csv .= "{$grade->user->id},\"{$grade->user->name}\",\"{$grade->user->grade}\",\"{$grade->user->school}\"," .
                    "{$grade->nota_matematica},{$grade->nota_comunicacion},{$grade->nota_ciencias_sociales}," .
                    "{$grade->nota_ciencia_tecnologia},{$grade->nota_desarrollo_personal},{$grade->nota_ciudadania_civica}," .
                    "{$grade->nota_educacion_fisica},{$grade->nota_ingles},{$grade->nota_educacion_trabajo}," .
                    round($average, 2) . "\n";
            }

            return response($csv, 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="notas-academicas-' . date('Y-m-d-His') . '.csv"');
        } catch (\Exception $e) {
            Log::error('[v0] Error descargando CSV de notas: ' . $e->getMessage());
            return back()->with('error', 'Error al descargar reporte');
        }
    }

    /**
     * Descargar reporte de predicciones en CSV
     */
    public function downloadPredictionsCSV()
    {
        try {
            $user = Auth::user();
            if (!in_array($user->role, ['admin' ])) {
                return redirect()->back()->with('error', 'No tienes permiso');
            }

            $results = TestResult::with('user')
                ->where('academic_year', date('Y'))
                ->orWhereNull('academic_year')
                ->get();

            $csv = "ID Estudiante,Nombre,Grado,Escuela,Categoría Dominante,Puntuación Total,Carreras Recomendadas,Fecha\n";

            foreach ($results as $result) {
                $recommendedCareers = is_string($result->recommended_careers) 
                    ? json_decode($result->recommended_careers, true) 
                    : $result->recommended_careers;

                $careerNames = [];
                if (is_array($recommendedCareers)) {
                    foreach ($recommendedCareers as $career) {
                        if (is_array($career) && isset($career['category_name'])) {
                            $careerNames[] = $career['category_name'];
                        }
                    }
                }

                $csv .= "{$result->user->id},\"{$result->user->name}\",\"{$result->user->grade}\",\"{$result->user->school}\"," .
                    "\"" . implode('; ', $careerNames) . "\",{$result->total_score},\"" .
                    (is_array($recommendedCareers) ? json_encode($recommendedCareers) : $result->recommended_careers) . "\"," .
                    $result->created_at->format('Y-m-d H:i') . "\n";
            }

            return response($csv, 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="predicciones-' . date('Y-m-d-His') . '.csv"');
        } catch (\Exception $e) {
            Log::error('[v0] Error descargando CSV de predicciones: ' . $e->getMessage());
            return back()->with('error', 'Error al descargar reporte');
        }
    }

    /**
     * Generar reporte individual de estudiante en PDF
     */
    public function downloadStudentReportPDF($studentId)
    {
        try {
            $user = Auth::user();
            if (!in_array($user->role, ['admin' ]) && $user->id != $studentId) {
                return redirect()->back()->with('error', 'No tienes permiso');
            }

            $student = User::find($studentId);
            if (!$student || $student->role !== 'student') {
                return redirect()->back()->with('error', 'Estudiante no encontrado');
            }

            $grades = StudentGrade::where('user_id', $studentId)
                ->orderBy('academic_year', 'desc')
                ->first();

            $testResults = TestResult::where('user_id', $studentId)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            $pdf_content = $this->generatePDFContent($student, $grades, $testResults);

            return response($pdf_content, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="reporte-' . $student->name . '-' . date('Y-m-d') . '.pdf"');
        } catch (\Exception $e) {
            Log::error('[v0] Error generando PDF de estudiante: ' . $e->getMessage());
            return back()->with('error', 'Error al generar reporte');
        }
    }

    /**
     * Método privado para generar contenido PDF
     */
    private function generatePDFContent($student, $grades, $testResults)
    {
        // Basic HTML to PDF conversion using simple formatting
        // For production, use libraries like mPDF, TCPDF, or DomPDF
        
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Reporte de Estudiante</title>
            <style>
                body { font-family: Arial, sans-serif; color: #333; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
                .section { margin-top: 20px; padding: 15px; border-left: 4px solid #667eea; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Reporte Académico y Vocacional</h1>
                <p>FuturoSmart - Sistema de Orientación Vocacional</p>
            </div>
            
            <div class='section'>
                <h2>Información del Estudiante</h2>
                <table>
                    <tr>
                        <td><strong>Nombre:</strong> {$student->name}</td>
                        <td><strong>Grado:</strong> {$student->grade}</td>
                    </tr>
                    <tr>
                        <td><strong>Escuela:</strong> {$student->school}</td>
                        <td><strong>Email:</strong> {$student->email}</td>
                    </tr>
                </table>
            </div>
        ";

        if ($grades) {
            $average = (
                $grades->nota_matematica +
                $grades->nota_comunicacion +
                $grades->nota_ciencias_sociales +
                $grades->nota_ciencia_tecnologia +
                $grades->nota_desarrollo_personal +
                $grades->nota_ciudadania_civica +
                $grades->nota_educacion_fisica +
                $grades->nota_ingles +
                $grades->nota_educacion_trabajo
            ) / 9;

            $html .= "
            <div class='section'>
                <h2>Notas Académicas - Año {$grades->academic_year}</h2>
                <table>
                    <tr>
                        <th>Materia</th><th>Nota</th><th>Materia</th><th>Nota</th>
                    </tr>
                    <tr>
                        <td>Matemática</td><td>{$grades->nota_matematica}/20</td>
                        <td>Comunicación</td><td>{$grades->nota_comunicacion}/20</td>
                    </tr>
                    <tr>
                        <td>Ciencias Sociales</td><td>{$grades->nota_ciencias_sociales}/20</td>
                        <td>Ciencia y Tecnología</td><td>{$grades->nota_ciencia_tecnologia}/20</td>
                    </tr>
                    <tr>
                        <td>Desarrollo Personal</td><td>{$grades->nota_desarrollo_personal}/20</td>
                        <td>Ciudadanía y Cívica</td><td>{$grades->nota_ciudadania_civica}/20</td>
                    </tr>
                    <tr>
                        <td>Educación Física</td><td>{$grades->nota_educacion_fisica}/20</td>
                        <td>Inglés</td><td>{$grades->nota_ingles}/20</td>
                    </tr>
                    <tr>
                        <td>Educación y Trabajo</td><td>{$grades->nota_educacion_trabajo}/20</td>
                        <td><strong>Promedio</strong></td><td><strong>" . round($average, 2) . "/20</strong></td>
                    </tr>
                </table>
            </div>
            ";
        }

        if ($testResults->count() > 0) {
            $html .= "
            <div class='section'>
                <h2>Últimos Resultados de Tests</h2>
            ";
            
            foreach ($testResults as $result) {
                $html .= "<p><strong>Puntuación Total:</strong> {$result->total_score}</p>";
                $html .= "<p><strong>Análisis:</strong> " . strip_tags($result->analysis) . "</p>";
            }
            
            $html .= "</div>";
        }

        $html .= "
            <div class='section' style='border-left: 4px solid #10b981;'>
                <p><small>Reporte generado: " . date('d/m/Y H:i') . "</small></p>
                <p><small>FuturoSmart © " . date('Y') . " - Todos los derechos reservados</small></p>
            </div>
        </body>
        </html>
        ";

        return $html;
    }
}
