@extends('layouts.dashboard')

@section('title', 'Dashboard de Orientadores')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Dashboard de Orientadores</h1>
            <p class="text-slate-600">Panel de control y análisis vocacional</p>
        </div>

        <!-- Estadísticas Generales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-600">Total</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $totalStudents ?? 0 }}</h3>
                <p class="text-slate-600 text-sm">Estudiantes Registrados</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-600">Total</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $completedTests ?? 0 }}</h3>
                <p class="text-slate-600 text-sm">Tests Completados</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-600">Promedio</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ number_format($averageScore ?? 0, 1) }}</h3>
                <p class="text-slate-600 text-sm">Rendimiento Promedio</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-600">Total</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $totalPredictions ?? 0 }}</h3>
                <p class="text-slate-600 text-sm">Predicciones Realizadas</p>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('clustering.dashboard') }}" class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 text-white hover:from-indigo-600 hover:to-purple-700 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-2">Análisis de Clustering</h3>
                <p class="text-white/80">Visualiza patrones y grupos de estudiantes</p>
            </a>

            <a href="{{ route('counselor.students') }}" class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl p-6 text-white hover:from-emerald-600 hover:to-teal-700 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-2">Gestión de Estudiantes</h3>
                <p class="text-white/80">Administra y monitorea estudiantes</p>
            </a>

            <a href="{{ route('reports.index') }}" class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-6 text-white hover:from-amber-600 hover:to-orange-700 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold mb-2">Reportes y Estadísticas</h3>
                <p class="text-white/80">Genera informes detallados</p>
            </a>
        </div>

        <!-- Listado de Estudiantes Recientes -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-900">Estudiantes Recientes</h3>
                <p class="text-slate-600 text-sm mt-1">Últimas actividades y resultados</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Último Test</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($recentStudents ?? [] as $student)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center">
                                        <span class="text-sm font-medium text-slate-600">
                                            {{ strtoupper(substr($student->name ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $student->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-slate-500">{{ $student->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $student->last_test_name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-600">
                                    {{ $student->last_test_date ? date('d/m/Y', strtotime($student->last_test_date)) : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->status ?? false)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-600">
                                        Completado
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-600">
                                        En Proceso
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('reports.download.student-pdf', ['studentId' => $student->id ?? 0]) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">Ver Reporte</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-slate-500">
                                No hay estudiantes recientes
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
