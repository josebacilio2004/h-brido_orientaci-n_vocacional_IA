@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Principal')
@section('page-description', 'Descubre tu futuro profesional con inteligencia artificial')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="glass-card rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">¡Hola, {{ $user->name }}! 👋</h2>
                <p class="text-gray-600">Estás a un paso de descubrir tu vocación ideal. Nuestro sistema de IA te ayudará a encontrar la carrera perfecta para ti.</p>
            </div>
            <div class="hidden md:block">
                <div class="h-20 w-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tests Realizados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->test_results ? count(json_decode($user->test_results, true)) : 0 }}</p>
                </div>
                <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stats-card" style="border-left-color: var(--accent);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Carreras Exploradas</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
                <div class="h-12 w-12 bg-cyan-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stats-card" style="border-left-color: var(--success);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Compatibilidad</p>
                    <p class="text-2xl font-bold text-gray-900">85%</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stats-card" style="border-left-color: var(--warning);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Progreso</p>
                    <p class="text-2xl font-bold text-gray-900">60%</p>
                </div>
                <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Modules -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Test Vocacional -->
        <div class="module-card">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Nuevo</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Test Vocacional IA</h3>
            <p class="text-gray-600 mb-4">Descubre tus intereses, habilidades y personalidad con nuestro test inteligente de última generación.</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    15-20 minutos
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Análisis con IA
                </div>
            </div>
            <a href="{{ route('dashboard.tests') }}" class="btn-primary w-full text-center">Comenzar Test</a>
        </div>

        <!-- Explorar Carreras -->
        <div class="module-card">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="bg-cyan-100 text-cyan-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Popular</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Explorar Carreras</h3>
            <p class="text-gray-600 mb-4">Conoce más de 200 carreras universitarias con información detallada sobre cada una.</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    200+ carreras
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Info detallada
                </div>
            </div>
            <a href="{{ route('dashboard.careers') }}" class="btn-secondary w-full text-center">Explorar Ahora</a>
        </div>

        <!-- Recomendaciones IA -->
        <div class="module-card">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">IA</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Recomendaciones IA</h3>
            <p class="text-gray-600 mb-4">Obtén sugerencias personalizadas basadas en tus resultados y preferencias.</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    Análisis inteligente
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Personalizado
                </div>
            </div>
            <a href="{{ route('dashboard.recommendations') }}" class="btn-primary w-full text-center" style="background: var(--gradient-success);">Ver Recomendaciones</a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('dashboard.profile') }}" class="flex items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors">
                <div class="h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Actualizar Perfil</p>
                    <p class="text-sm text-gray-500">Completa tu información</p>
                </div>
            </a>

            <a href="#" class="flex items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-cyan-300 transition-colors">
                <div class="h-10 w-10 bg-cyan-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="h-5 w-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Centro de Ayuda</p>
                    <p class="text-sm text-gray-500">Obtén soporte</p>
                </div>
            </a>

            <a href="#" class="flex items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-green-300 transition-colors">
                <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Becas y Ayudas</p>
                    <p class="text-sm text-gray-500">Encuentra financiamiento</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
