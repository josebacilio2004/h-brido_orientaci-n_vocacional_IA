@php
$progress = ($currentQuestion / $totalQuestions) * 100;
$answeredCount = $currentQuestion - 1;
@endphp

@extends('layouts.dashboard')

@section('title', 'Pregunta ' . $currentQuestion . ' - ' . $test->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-300 text-sm font-medium">Pregunta {{ $currentQuestion }} de {{ $totalQuestions }}</span>
                <span class="text-gray-300 text-sm font-medium">{{ number_format($progress, 0) }}% completado</span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-pink-600 to-rose-600 h-full rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <div class="flex items-start gap-4 mb-6">
                <div class="bg-gradient-to-br from-pink-600 to-rose-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                    {{ $currentQuestion }}
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-white mb-2">{{ $question->question }}</h2>
                    <p class="text-gray-400 text-sm">Indica tu grado de acuerdo</p>
                </div>
            </div>

            <form action="{{ route('tests.personality.save-answer', ['id' => $test->id, 'question' => $currentQuestion]) }}" method="POST" id="answerForm">
                @csrf

                <div class="space-y-4 mb-8">
                    <div class="flex justify-between text-sm text-gray-400 px-2">
                        <span>Totalmente en desacuerdo</span>
                        <span>Totalmente de acuerdo</span>
                    </div>
                    <div class="grid grid-cols-5 gap-3">
                        @php $labels = ['Muy bajo', 'Bajo', 'Neutral', 'Alto', 'Muy alto']; @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer group block">
                                <input type="radio" name="answer" value="{{ $i }}" class="peer hidden" {{ $previousAnswer && (int)$previousAnswer->answer === $i ? 'checked' : '' }} required>
                                <div class="bg-white/5 border-2 border-gray-500 rounded-xl p-6 text-center transition-all duration-300 transform hover:scale-105 peer-checked:border-pink-500 peer-checked:bg-pink-600/30 peer-checked:scale-110 peer-checked:shadow-lg">
                                    <div class="text-3xl font-bold text-gray-400 peer-checked:text-pink-200 mb-2 transition-colors duration-300">{{ $i }}</div>
                                    <div class="text-xs text-gray-500 peer-checked:text-pink-100 transition-colors duration-300">{{ $labels[$i - 1] }}</div>
                                </div>
                            </label>
                        @endfor
                    </div>
                </div>

                <div class="flex gap-4">
                    @if ($currentQuestion > 1)
                        <a href="{{ route('tests.personality.question', ['id' => $test->id, 'question' => $currentQuestion - 1]) }}" class="flex-1 bg-white/10 text-white px-6 py-4 rounded-lg font-semibold hover:bg-white/20 transition-all text-center flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Anterior
                        </a>
                    @else
                        <a href="{{ route('tests.index') }}" class="flex-1 bg-white/10 text-white px-6 py-4 rounded-lg font-semibold hover:bg-white/20 transition-all text-center">Cancelar</a>
                    @endif

                    @if ($currentQuestion < $totalQuestions)
                        <button type="submit" class="flex-1 bg-gradient-to-r from-pink-600 to-rose-600 text-white px-6 py-4 rounded-lg font-semibold hover:from-pink-700 hover:to-rose-700 transition-all flex items-center justify-center gap-2">
                            Siguiente
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    @else
                        <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-4 rounded-lg font-semibold hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                            Finalizar Test
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white/5 backdrop-blur-lg rounded-xl p-4 border border-white/20 text-center">
            <p class="text-gray-400 text-sm">
                Has respondido <span class="text-pink-400 font-bold">{{ $answeredCount }}</span> de <span class="text-white font-bold">{{ $totalQuestions }}</span> preguntas
            </p>
        </div>
    </div>
</div>

<script>
    // Users must now click the "Next" button to proceed, allowing them to review their selection
</script>
@endsection
