@extends('layouts.dashboard')

@section('title', $test->title)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <a href="{{ route('tests.index') }}"
                        class="text-gray-300 hover:text-white flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Tests
                    </a>
                </div>

                <div class="text-center">
                    <div
                        class="mx-auto h-20 w-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-3">{{ $test->title }}</h1>
                    <p class="text-gray-300 text-lg mb-6">{{ $test->description }}</p>

                    <div class="bg-white/5 rounded-lg p-6 mb-6">
                        <p class="text-gray-300 text-left mb-4">{{ $test->instructions }}</p>
                    </div>
                </div>
            </div>

            {{-- Información del Test --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 text-center">
                    <div class="h-12 w-12 bg-blue-500/20 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-1">{{ $test->duration_minutes }} min</h3>
                    <p class="text-gray-400 text-sm">Duración estimada</p>
                </div>

                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 text-center">
                    <div class="h-12 w-12 bg-purple-500/20 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-1">{{ $test->total_questions }}</h3>
                    <p class="text-gray-400 text-sm">Preguntas</p>
                </div>

                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 text-center">
                    <div class="h-12 w-12 bg-green-500/20 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-1">Paso a paso</h3>
                    <p class="text-gray-400 text-sm">Pregunta por pregunta</p>
                </div>
            </div>

            {{-- Botón de inicio --}}
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 text-center">
                <h2 class="text-2xl font-bold text-white mb-4">¿Listo para comenzar?</h2>
                <p class="text-gray-300 mb-6">El test se guardará automáticamente después de cada pregunta. Puedes pausar y
                    continuar cuando quieras.</p>

                <div class="flex gap-4 justify-center">
                    <a href="{{ route('tests.index') }}"
                        class="bg-white/10 text-white px-8 py-4 rounded-lg font-semibold hover:bg-white/20 transition-all">
                        Cancelar
                    </a>

                    <a href="{{ route('tests.start', $test->id) }}"
                        class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl">
                        Iniciar Test
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
