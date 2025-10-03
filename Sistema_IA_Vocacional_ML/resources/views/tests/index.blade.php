@extends('layouts.dashboard')

@section('title', 'Tests Vocacionales')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
        <div class="max-w-7xl mx-auto">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Tests Vocacionales</h1>
                <p class="text-gray-300">Descubre tu vocación a través de nuestras evaluaciones especializadas</p>
            </div>

            @if (session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-100 px-6 py-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-500/20 border border-blue-500 text-blue-100 px-6 py-4 rounded-lg mb-6">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Actualizado para mostrar el botón de iniciar test correctamente --}}
            {{-- Test con IA basado en notas --}}
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl p-8 mb-8 shadow-2xl">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-white/20 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Predicción con Inteligencia Artificial</h2>
                                <p class="text-purple-100">Basado en tus notas académicas</p>
                            </div>
                        </div>
                        <p class="text-white/90 mb-6">
                            Nuestro modelo de Machine Learning analiza tus calificaciones en todas las materias
                            para predecir las carreras más adecuadas según tu rendimiento académico.
                        </p>
                        <a href="{{ route('tests.grades') }}"
                            class="inline-flex items-center gap-2 bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-purple-50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Obtener Predicción IA
                        </a>
                    </div>
                </div>
            </div>

            {{-- Tests Disponibles --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tests as $test)
                    <div
                        class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:border-purple-400 transition-all hover:transform hover:scale-105">
                        <div class="flex items-start justify-between mb-4">
                            <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            @if (in_array($test->id, $completedTests))
                                <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                    Completado
                                </span>
                            @endif
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2">{{ $test->title }}</h3>
                        <p class="text-gray-300 text-sm mb-4">{{ $test->description }}</p>

                        <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $test->duration_minutes }} min
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                {{ $test->total_questions }} preguntas
                            </span>
                        </div>

                        @if (in_array($test->id, $completedTests))
                            <a href="{{ route('tests.result', $test->id) }}">Ver Resultados</a>
                        @else
                            <a href="{{ route('tests.show', $test->id) }}">Comenzar Test</a>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-gray-400 text-lg">No hay tests disponibles. Ejecuta el seeder para crear el test
                            vocacional.</p>
                        <p class="text-gray-500 text-sm mt-2">Comando: php artisan db:seed --class=VocationalTestSeeder</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
