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
                                <p class="text-purple-100">Completa los 4 tests para obtener predicción basada en ML</p>
                            </div>
                        </div>
                        <p class="text-white/90 mb-6">
                            Después de completar todos los tests (RIASEC, Intereses, Habilidades y Personalidad) junto con tus notas académicas, 
                            nuestro modelo de Machine Learning analizará toda tu información para predecir las carreras más adecuadas para ti.
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

            {{-- Tests Disponibles - 4 Tests Completos --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-6">Tests Disponibles (4/4)</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Test RIASEC --}}
                    @forelse($tests as $test)
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:border-purple-400 transition-all hover:transform hover:scale-105">
                            <div class="flex items-start justify-between mb-4">
                                <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                @if (in_array($test->id, $completedTests))
                                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                        ✓ Completado
                                    </span>
                                @else
                                    <span class="bg-yellow-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                        Pendiente
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-white mb-2">Test RIASEC</h3>
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
                                <a href="{{ route('tests.result', $test->id) }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center">Ver Resultados</a>
                            @else
                                <a href="{{ route('tests.show', $test->id) }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center">Comenzar Test</a>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-400 text-lg">No hay tests RIASEC disponibles.</p>
                        </div>
                    @endforelse

                    {{-- Agregando Test de Intereses --}}
                    @forelse($interestTests as $test)
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:border-blue-400 transition-all hover:transform hover:scale-105">
                            <div class="flex items-start justify-between mb-4">
                                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                @if (in_array($test->id, $completedInterestTests))
                                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                        ✓ Completado
                                    </span>
                                @else
                                    <span class="bg-yellow-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                        Pendiente
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-white mb-2">Test de Intereses</h3>
                            <p class="text-gray-300 text-sm mb-4">Identifica qué actividades y áreas del conocimiento te motivan más.</p>

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

                            @if (in_array($test->id, $completedInterestTests))
                                <a href="{{ route('tests.interest.result', $test->id) }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center">Ver Resultados</a>
                            @else
                                <a href="{{ route('tests.interest.show', $test->id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center">Comenzar Test</a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-400 text-sm">Test de Intereses no disponible</p>
                        </div>
                    @endforelse

                    {{-- Agregando Test de Habilidades --}}
                    @forelse($skillTests as $test)
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:border-orange-400 transition-all hover:transform hover:scale-105">
                            <div class="flex items-start justify-between mb-4">
                                <div class="bg-gradient-to-br from-orange-500 to-red-500 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                @if (in_array($test->id, $completedSkillTests))
                                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                        ✓ Completado
                                    </span>
                                @else
                                    <span class="bg-yellow-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                        Pendiente
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-white mb-2">Test de Habilidades</h3>
                            <p class="text-gray-300 text-sm mb-4">Evalúa tus fortalezas y competencias naturales.</p>

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

                            @if (in_array($test->id, $completedSkillTests))
                                <a href="{{ route('tests.skill.result', $test->id) }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center">Ver Resultados</a>
                            @else
                                <a href="{{ route('tests.skill.show', $test->id) }}" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center">Comenzar Test</a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-400 text-sm">Test de Habilidades no disponible</p>
                        </div>
                    @endforelse

                    {{-- Agregando Test de Personalidad --}}
                    @forelse($personalityTests as $test)
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:border-pink-400 transition-all hover:transform hover:scale-105">
                            <div class="flex items-start justify-between mb-4">
                                <div class="bg-gradient-to-br from-pink-500 to-rose-500 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                @if (in_array($test->id, $completedPersonalityTests))
                                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                        ✓ Completado
                                    </span>
                                @else
                                    <span class="bg-yellow-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                        Pendiente
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-white mb-2">Test de Personalidad</h3>
                            <p class="text-gray-300 text-sm mb-4">Conoce tu tipo de personalidad y cómo influye en tu carrera.</p>

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

                            @if (in_array($test->id, $completedPersonalityTests))
                                <a href="{{ route('tests.personality.result', $test->id) }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center">Ver Resultados</a>
                            @else
                                <a href="{{ route('tests.personality.show', $test->id) }}" class="w-full bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center">Comenzar Test</a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-400 text-sm">Test de Personalidad no disponible</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Información sobre los 4 Tests --}}
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold text-white mb-6">¿Por qué 4 Tests?</h3>
                <div class="grid md:grid-cols-2 gap-6 text-gray-300">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500/20 text-purple-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white mb-1">Análisis Integral</h4>
                            <p class="text-sm">Los 4 tests analizan diferentes aspectos de tu perfil vocacional para dar recomendaciones más precisas.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500/20 text-blue-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white mb-1">Machine Learning Avanzado</h4>
                            <p class="text-sm">El modelo ML analiza datos de los 4 tests junto con tus notas académicas para predicciones más acertadas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
