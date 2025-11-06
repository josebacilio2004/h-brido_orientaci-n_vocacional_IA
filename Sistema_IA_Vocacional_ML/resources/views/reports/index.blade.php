@extends('layouts.dashboard')

@section('title', 'Centro de Reportes')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-slate-900 mb-2">Centro de Reportes</h1>
                    <p class="text-slate-600">Descarga reportes y estadísticas académicas</p>
                </div>
                <div class="hidden lg:block">
                    <svg class="w-20 h-20 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v10m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Report download options with modern card design -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <!-- Students Report -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Reporte de Estudiantes</h3>
                        <p class="text-slate-600 text-sm mt-1">Listado de todos los estudiantes registrados</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3 mb-6">
                    <p class="text-sm text-slate-600">Formato: <span class="font-semibold text-slate-900">CSV</span></p>
                    <p class="text-sm text-slate-600">Incluye: ID, Nombre, Grado, Escuela, Email, Fecha de registro</p>
                </div>
                <a href="{{ route('reports.download.students-csv') }}" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Descargar CSV
                </a>
            </div>

            <!-- Grades Report -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Reporte de Notas</h3>
                        <p class="text-slate-600 text-sm mt-1">Notas académicas de todos los estudiantes</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3 mb-6">
                    <p class="text-sm text-slate-600">Formato: <span class="font-semibold text-slate-900">CSV</span></p>
                    <p class="text-sm text-slate-600">Incluye: Todas las materias, promedios y datos de estudiante</p>
                </div>
                <a href="{{ route('reports.download.grades-csv') }}" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-6 py-3 rounded-lg font-semibold transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Descargar CSV
                </a>
            </div>

            <!-- Predictions Report -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Reporte de Predicciones</h3>
                        <p class="text-slate-600 text-sm mt-1">Resultados de predicciones ML y tests</p>
                    </div>
                    <div class="p-3 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3 mb-6">
                    <p class="text-sm text-slate-600">Formato: <span class="font-semibold text-slate-900">CSV</span></p>
                    <p class="text-sm text-slate-600">Incluye: Predicciones, puntuaciones y análisis vocacionales</p>
                </div>
                <a href="{{ route('reports.download.predictions-csv') }}" class="w-full bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white px-6 py-3 rounded-lg font-semibold transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Descargar CSV
                </a>
            </div>

            <!-- Individual Student Report -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Reporte Individual</h3>
                        <p class="text-slate-600 text-sm mt-1">Reporte completo de un estudiante</p>
                    </div>
                    <div class="p-3 bg-pink-100 rounded-lg">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3 mb-6">
                    <p class="text-sm text-slate-600">Formato: <span class="font-semibold text-slate-900">PDF</span></p>
                    <p class="text-sm text-slate-600">Incluye: Datos académicos, notas y predicciones personalizadas</p>
                </div>
                <button disabled class="w-full bg-slate-300 text-slate-600 px-6 py-3 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Seleccionar Estudiante
                </button>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 mb-8">
            <h3 class="font-semibold text-indigo-900 mb-2">Información sobre Reportes</h3>
            <ul class="text-indigo-800 text-sm space-y-1">
                <li>• Los reportes se generan en tiempo real con datos actualizados</li>
                <li>• Formato CSV compatible con Excel, Google Sheets y otras herramientas</li>
                <li>• Solo administradores y orientadores pueden descargar reportes</li>
                <li>• Los datos se exportan del año académico actual</li>
            </ul>
        </div>

        <!-- Back Button -->
        <a href="{{ route('dashboard') }}" class="inline-block bg-slate-100 hover:bg-slate-200 text-slate-900 px-6 py-3 rounded-lg font-semibold transition-colors">
            Volver al Dashboard
        </a>
    </div>
</div>
@endsection
