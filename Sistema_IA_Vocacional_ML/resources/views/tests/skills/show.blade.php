@extends('layouts.dashboard')

@section('title', $test->title)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-8">
                <div class="flex items-start gap-4 mb-6">
                    <div class="bg-gradient-to-br from-orange-500 to-red-500 text-white p-4 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $test->title }}</h1>
                        <p class="text-gray-300">{{ $test->description }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="bg-white/5 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-orange-400">{{ $test->duration_minutes }}</div>
                        <div class="text-gray-400 text-sm">Minutos</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-orange-400">{{ $test->total_questions }}</div>
                        <div class="text-gray-400 text-sm">Preguntas</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-orange-400">3-5 min</div>
                        <div class="text-gray-400 text-sm">Por pregunta</div>
                    </div>
                </div>

                <div class="bg-orange-500/20 border border-orange-500 rounded-lg p-4 mb-8">
                    <p class="text-orange-100">Este test evaluará tus habilidades naturales en diferentes competencias para
                        identificar en qué áreas tienes mayor fortaleza.</p>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('tests.index') }}"
                        class="flex-1 bg-white/10 text-white px-6 py-3 rounded-lg font-semibold hover:bg-white/20 transition-all text-center">
                        Cancelar
                    </a>
                    <a href="{{ route('tests.skill.question', ['id' => $test->id, 'question' => 1]) }}"
                        class="flex-1 bg-gradient-to-r from-orange-600 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-orange-700 hover:to-red-700 transition-all text-center">
                        Comenzar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
