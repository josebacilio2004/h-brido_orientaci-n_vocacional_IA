@extends('layouts.dashboard')

@section('title', 'Estadísticas de Notas - Dashboard Orientadores')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Improved header with better typography and layout -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-slate-900 mb-2">Estadísticas Académicas</h1>
                    <p class="text-slate-600">Dashboard de Orientadores - Año {{ date('Y') }}</p>
                </div>
                <div class="hidden lg:block">
                    <svg class="w-20 h-20 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        @if($statistics)
            @php $stats = $statistics[0]; @endphp
            
            <!-- Modern stats cards with consistent colors and better spacing -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                    <div class="text-slate-600 text-sm font-semibold mb-2">Total Estudiantes</div>
                    <div class="text-3xl font-bold text-slate-900">{{ $stats->total_students }}</div>
                    <div class="mt-2 text-xs text-slate-500">Con notas registradas</div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                    <div class="text-slate-600 text-sm font-semibold mb-2">Promedio Matemática</div>
                    <div class="text-3xl font-bold text-indigo-600">{{ number_format($stats->avg_matematica, 2) }}</div>
                    <div class="mt-2 text-xs text-slate-500">/20</div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                    <div class="text-slate-600 text-sm font-semibold mb-2">Promedio Comunicación</div>
                    <div class="text-3xl font-bold text-emerald-600">{{ number_format($stats->avg_comunicacion, 2) }}</div>
                    <div class="mt-2 text-xs text-slate-500">/20</div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                    <div class="text-slate-600 text-sm font-semibold mb-2">Promedio Tecnología</div>
                    <div class="text-3xl font-bold text-amber-600">{{ number_format($stats->avg_tecnologia, 2) }}</div>
                    <div class="mt-2 text-xs text-slate-500">/20</div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                    <div class="text-slate-600 text-sm font-semibold mb-2">Promedio General</div>
                    @php
                        $generalAverage = (
                            $stats->avg_matematica +
                            $stats->avg_comunicacion +
                            $stats->avg_sociales +
                            $stats->avg_tecnologia +
                            $stats->avg_personal +
                            $stats->avg_civica +
                            $stats->avg_fisica +
                            $stats->avg_ingles +
                            $stats->avg_trabajo
                        ) / 9;
                    @endphp
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($generalAverage, 2) }}</div>
                    <div class="mt-2 text-xs text-slate-500">/20</div>
                </div>
            </div>

            <!-- Subjects performance chart with better visual design -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 mb-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Desempeño por Materia</h2>
                <div class="space-y-4">
                    @php
                        $subjects = [
                            ['name' => 'Matemática', 'value' => $stats->avg_matematica, 'color' => 'indigo'],
                            ['name' => 'Comunicación', 'value' => $stats->avg_comunicacion, 'color' => 'emerald'],
                            ['name' => 'Ciencias Sociales', 'value' => $stats->avg_sociales, 'color' => 'blue'],
                            ['name' => 'Ciencia y Tecnología', 'value' => $stats->avg_tecnologia, 'color' => 'amber'],
                            ['name' => 'Desarrollo Personal', 'value' => $stats->avg_personal, 'color' => 'pink'],
                            ['name' => 'Ciudadanía y Cívica', 'value' => $stats->avg_civica, 'color' => 'red'],
                            ['name' => 'Educación Física', 'value' => $stats->avg_fisica, 'color' => 'green'],
                            ['name' => 'Inglés', 'value' => $stats->avg_ingles, 'color' => 'purple'],
                            ['name' => 'Educación y Trabajo', 'value' => $stats->avg_trabajo, 'color' => 'cyan'],
                        ];
                    @endphp
                    @foreach($subjects as $subject)
                        @php 
                            $percentage = ($subject['value'] / 20) * 100;
                            $color_classes = [
                                'indigo' => 'from-indigo-500 to-indigo-600',
                                'emerald' => 'from-emerald-500 to-emerald-600',
                                'blue' => 'from-blue-500 to-blue-600',
                                'amber' => 'from-amber-500 to-amber-600',
                                'pink' => 'from-pink-500 to-pink-600',
                                'red' => 'from-red-500 to-red-600',
                                'green' => 'from-green-500 to-green-600',
                                'purple' => 'from-purple-500 to-purple-600',
                                'cyan' => 'from-cyan-500 to-cyan-600',
                            ];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-slate-900 font-semibold text-sm">{{ $subject['name'] }}</span>
                                <span class="text-slate-700 font-bold">{{ number_format($subject['value'], 2) }}/20</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r {{ $color_classes[$subject['color']] }} h-full rounded-full transition-all duration-500" 
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top students table with professional styling and export button -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Top 10 Estudiantes</h2>
                        <p class="text-slate-600 text-sm mt-1">Estudiantes con mejor desempeño académico</p>
                    </div>
                    <a href="{{ route('grades.export') }}" class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Descargar CSV
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-slate-600 border-b border-slate-200">
                                <th class="pb-4 font-semibold">Posición</th>
                                <th class="pb-4 font-semibold">Nombre</th>
                                <th class="pb-4 font-semibold">Grado</th>
                                <th class="pb-4 font-semibold">Escuela</th>
                                <th class="pb-4 font-semibold text-right">Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topStudents as $index => $student)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                    <td class="py-4">
                                        <span class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white px-3 py-1 rounded-full font-semibold text-sm">
                                            #{{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-slate-900 font-medium">{{ $student->name }}</td>
                                    <td class="py-4 text-slate-600">{{ $student->grade }}</td>
                                    <td class="py-4 text-slate-600">{{ $student->school }}</td>
                                    <td class="py-4 text-right">
                                        <span class="font-bold text-slate-900">{{ number_format($student->average_grade, 2) }}/20</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-8 bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                <h3 class="font-semibold text-indigo-900 mb-2">Notas sobre estos datos</h3>
                <p class="text-indigo-800 text-sm">Estas estadísticas se actualizan en tiempo real conforme los estudiantes registran sus notas. Puedes exportar el reporte completo en formato CSV para análisis adicional.</p>
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
                <p class="text-amber-800">No hay datos de notas disponibles aún. Los estudiantes deben completar el registro de calificaciones.</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-8 flex gap-4">
            <a href="{{ route('dashboard') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-900 px-6 py-3 rounded-lg font-semibold transition-colors text-center">
                Volver al Dashboard
            </a>
            <a href="{{ route('tests.index') }}" class="flex-1 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all text-center">
                Ver Tests Vocacionales
            </a>
        </div>
    </div>
</div>
@endsection
