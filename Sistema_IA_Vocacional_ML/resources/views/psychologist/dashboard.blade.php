@extends('layouts.dashboard')

@section('title', 'Dashboard de Psicólogos')
@section('page-title', 'Panel de Evaluación Psicológica')
@section('page-description', 'Evalúa y analiza los perfiles de personalidad de los estudiantes')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Panel de Evaluación Psicológica</h1>
            <p class="text-slate-600">Análisis integral de personalidad y recomendaciones vocacionales</p>
        </div>

        <!-- Modified stats to show psychology-related metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-600">Total</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $totalStudents ?? 0 }}</h3>
                <p class="text-slate-600 text-sm">Estudiantes Evaluados</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-pink-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-600">Análisis</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $totalPersonalityTests ?? 0 }}</h3>
                <p class="text-slate-600 text-sm">Tests de Personalidad</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-600">Tipos</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ isset($personalityDistribution) ? count($personalityDistribution) : 0 }}</h3>
                <p class="text-slate-600 text-sm">Tipos de Personalidad</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-600">Recomendaciones</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $totalRecommendations ?? 0 }}</h3>
                <p class="text-slate-600 text-sm">Sesiones de Orientación</p>
            </div>
        </div>

        <!-- Personality distribution chart and quick actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Distribución de Tipos de Personalidad</h3>
                @if($personalityDistribution ?? false)
                    <div class="space-y-4">
                        @foreach($personalityDistribution as $type => $count)
                        <div class="flex items-center">
                            <span class="w-24 text-sm font-medium text-slate-700">{{ $type }}</span>
                            <div class="flex-1 bg-slate-200 rounded-full h-3 mx-4">
                                <div class="bg-purple-600 h-3 rounded-full" style="width: {{ ($count / max(...array_values($personalityDistribution)) ?? 0) * 100 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-600">No hay datos de personalidad disponibles</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Acciones Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('counselor.students') }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-center">
                        Ver Estudiantes
                    </a>
                    <a href="{{ route('clustering.dashboard') }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-center">
                        Análisis de Clustering
                    </a>
                    <a href="{{ route('reports.index') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-center">
                        Generar Reportes
                    </a>
                </div>
            </div>
        </div>

        <!-- Replaced analytics section with student evaluation table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Estudiantes para Evaluar</h3>
                    <p class="text-slate-600 text-sm mt-1">Lista de estudiantes con resultados disponibles para análisis psicológico</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tipo Personalidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha Evaluación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($students ?? [] as $student)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-purple-200 flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-medium text-purple-800">
                                            {{ strtoupper(substr($student->name ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-900">{{ $student->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-slate-600">{{ $student->email ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $personalityType = $student->personalityType ?? 'No disponible';
                                    $typeColor = match($personalityType) {
                                        'INTJ' => 'bg-red-100 text-red-800',
                                        'INTP' => 'bg-orange-100 text-orange-800',
                                        'ENTJ' => 'bg-yellow-100 text-yellow-800',
                                        'ENTP' => 'bg-green-100 text-green-800',
                                        'INFJ' => 'bg-blue-100 text-blue-800',
                                        'INFP' => 'bg-indigo-100 text-indigo-800',
                                        'ENFJ' => 'bg-purple-100 text-purple-800',
                                        'ENFP' => 'bg-pink-100 text-pink-800',
                                        default => 'bg-slate-100 text-slate-800',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $typeColor }}">
                                    {{ $personalityType }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-slate-600">
                                    {{ $student->last_evaluation_date ? date('d/m/Y', strtotime($student->last_evaluation_date)) : 'Pendiente' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->evaluated ?? false)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-600">
                                        Evaluado
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-600">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('student.prediction', ['id' => $student->id ?? 0]) }}" 
                                   class="text-purple-600 hover:text-purple-900 font-medium">
                                    Ver Análisis
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-slate-500">
                                No hay estudiantes disponibles para evaluar
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Added notes and recommendations section -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Notas y Recomendaciones</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border-l-4 border-purple-600 pl-4 py-2">
                    <p class="text-sm font-semibold text-slate-900">Próximas Evaluaciones:</p>
                    <p class="text-sm text-slate-600 mt-1">Revisa regularmente el estado de nuevos estudiantes que completen los tests de personalidad.</p>
                </div>
                <div class="border-l-4 border-indigo-600 pl-4 py-2">
                    <p class="text-sm font-semibold text-slate-900">Seguimiento:</p>
                    <p class="text-sm text-slate-600 mt-1">Utiliza la vista de análisis detallado para evaluar intereses, habilidades y personalidad combinadas.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
