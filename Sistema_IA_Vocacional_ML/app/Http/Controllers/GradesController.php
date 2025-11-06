<?php

namespace App\Http\Controllers;

use App\Models\StudentGrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradesController extends Controller
{
    /**
     * Mostrar formulario para cargar notas
     */
    public function form()
    {
        try {
            $user = Auth::user();
            $grades = StudentGrade::where('user_id', $user->id)
                ->orderBy('academic_year', 'desc')
                ->first();

            return view('grades.form', compact('grades'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in GradesController@form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar formulario de notas');
        }
    }

    /**
     * Guardar o actualizar notas académicas
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nota_matematica' => 'required|integer|min:0|max:20',
                'nota_comunicacion' => 'required|integer|min:0|max:20',
                'nota_ciencias_sociales' => 'required|integer|min:0|max:20',
                'nota_ciencia_tecnologia' => 'required|integer|min:0|max:20',
                'nota_desarrollo_personal' => 'required|integer|min:0|max:20',
                'nota_ciudadania_civica' => 'required|integer|min:0|max:20',
                'nota_educacion_fisica' => 'required|integer|min:0|max:20',
                'nota_ingles' => 'required|integer|min:0|max:20',
                'nota_educacion_trabajo' => 'required|integer|min:0|max:20',
                'academic_year' => 'required|integer|min:2020|max:' . date('Y'),
            ]);

            $user = Auth::user();

            // Guardar notas usando DAO
            $grade = StudentGrade::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'academic_year' => $validated['academic_year'],
                ],
                $validated
            );

            Log::info('[v0] Grades saved for user: ' . $user->id);

            return redirect()->route('dashboard')
                ->with('success', 'Notas académicas guardadas correctamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('[v0] Validation error in GradesController@store: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('[v0] Error in GradesController@store: ' . $e->getMessage());
            return back()->with('error', 'Error al guardar notas académicas');
        }
    }

    /**
     * Obtener notas del usuario actual
     */
    public function getMyGrades()
    {
        try {
            $user = Auth::user();
            $grades = StudentGrade::where('user_id', $user->id)
                ->orderBy('academic_year', 'desc')
                ->first();

            if (!$grades) {
                return response()->json(['error' => 'No hay notas registradas'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $grades
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in GradesController@getMyGrades: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener notas'], 500);
        }
    }

    /**
     * Ver promedio de notas
     */
    public function getAverageGrades()
    {
        try {
            $user = Auth::user();
            $grades = StudentGrade::where('user_id', $user->id)
                ->orderBy('academic_year', 'desc')
                ->first();

            if (!$grades) {
                return response()->json(['error' => 'No hay notas registradas'], 404);
            }

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

            return response()->json([
                'success' => true,
                'average' => round($average, 2),
                'grades' => $grades
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in GradesController@getAverageGrades: ' . $e->getMessage());
            return response()->json(['error' => 'Error al calcular promedio'], 500);
        }
    }

    /**
     * Dashboard para visualizar estadísticas de notas (admin/orientador)
     */
    public function statisticsDashboard()
    {
        try {
            // Verificar que sea admin u orientador
            if (!in_array(Auth::user()->role, ['admin'])) {
                return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder');
            }

            $statistics = DB::select('
                SELECT 
                    COUNT(DISTINCT user_id) as total_students,
                    ROUND(AVG(nota_matematica), 2) as avg_matematica,
                    ROUND(AVG(nota_comunicacion), 2) as avg_comunicacion,
                    ROUND(AVG(nota_ciencias_sociales), 2) as avg_sociales,
                    ROUND(AVG(nota_ciencia_tecnologia), 2) as avg_tecnologia,
                    ROUND(AVG(nota_desarrollo_personal), 2) as avg_personal,
                    ROUND(AVG(nota_ciudadania_civica), 2) as avg_civica,
                    ROUND(AVG(nota_educacion_fisica), 2) as avg_fisica,
                    ROUND(AVG(nota_ingles), 2) as avg_ingles,
                    ROUND(AVG(nota_educacion_trabajo), 2) as avg_trabajo
                FROM student_grades
                WHERE academic_year = ?
            ', [date('Y')]);

            $topStudents = DB::select('
                SELECT 
                    u.id,
                    u.name,
                    u.grade,
                    u.school,
                    sg.academic_year,
                    ROUND((
                        sg.nota_matematica +
                        sg.nota_comunicacion +
                        sg.nota_ciencias_sociales +
                        sg.nota_ciencia_tecnologia +
                        sg.nota_desarrollo_personal +
                        sg.nota_ciudadania_civica +
                        sg.nota_educacion_fisica +
                        sg.nota_ingles +
                        sg.nota_educacion_trabajo
                    ) / 9, 2) as average_grade
                FROM users u
                JOIN student_grades sg ON u.id = sg.user_id
                WHERE sg.academic_year = ?
                AND u.role = "student"
                ORDER BY average_grade DESC
                LIMIT 10
            ', [date('Y')]);

            return view('grades.statistics', compact('statistics', 'topStudents'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in GradesController@statisticsDashboard: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar estadísticas');
        }
    }

    /**
     * Exportar reporte de notas (admin)
     */
    public function exportReport()
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return redirect()->back()->with('error', 'No tienes permiso');
            }

            $grades = StudentGrade::with('user')
                ->where('academic_year', date('Y'))
                ->get();

            $csv = "ID,Nombre,Grado,Escuela,Matemática,Comunicación,Sociales,Tecnología,Personal,Cívica,Física,Inglés,Trabajo,Promedio\n";

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

                $csv .= "{$grade->user->id},{$grade->user->name},{$grade->user->grade},{$grade->user->school}," .
                    "{$grade->nota_matematica},{$grade->nota_comunicacion},{$grade->nota_ciencias_sociales}," .
                    "{$grade->nota_ciencia_tecnologia},{$grade->nota_desarrollo_personal},{$grade->nota_ciudadania_civica}," .
                    "{$grade->nota_educacion_fisica},{$grade->nota_ingles},{$grade->nota_educacion_trabajo}," .
                    round($average, 2) . "\n";
            }

            return response($csv, 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="reporte-notas-' . date('Y-m-d') . '.csv"');
        } catch (\Exception $e) {
            Log::error('[v0] Error in GradesController@exportReport: ' . $e->getMessage());
            return back()->with('error', 'Error al exportar reporte');
        }
    }
}
