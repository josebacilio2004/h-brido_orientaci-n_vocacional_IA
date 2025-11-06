@extends('layouts.dashboard')

@section('title', $test->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="bg-gradient-to-br from-pink-500 to-rose-500 text-white p-4 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $test->title }}</h1>
                    <p class="text-gray-300">{{ $test->description }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-white/5 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-pink-400">{{ $test->duration_minutes }}</div>
                    <div class="text-gray-400 text-sm">Minutos</div>
                </div>
                <div class="bg-white/5 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-pink-400">{{ $test->total_questions }}</div>
                    <div class="text-gray-400 text-sm">Preguntas</div>
                </div>
                <div class="bg-white/5 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-pink-400">3-5 min</div>
                    <div class="text-gray-400 text-sm">Por pregunta</div>
                </div>
            </div>

            <div class="bg-pink-500/20 border border-pink-500 rounded-lg p-4 mb-8">
                <p class="text-pink-100">Este test evaluará rasgos de tu personalidad para ayudarte a comprender mejor cómo eres y qué tipo de ambientes de trabajo te resultan más confortables.</p>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('tests.index') }}" class="flex-1 bg-white/10 text-white px-6 py-3 rounded-lg font-semibold hover:bg-white/20 transition-all text-center">
                    Cancelar
                </a>
                <a href="{{ route('tests.index', ['testId' => $test->id, 'questionNumber' => 1]) }}" class="flex-1 bg-gradient-to-r from-pink-600 to-rose-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-pink-700 hover:to-rose-700 transition-all text-center">
                    Comenzar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
