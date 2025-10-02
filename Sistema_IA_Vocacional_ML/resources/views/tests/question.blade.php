@extends('layouts.dashboard')

@section('title', 'Pregunta ' . $questionNumber . ' - ' . $test->title)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
        <div class="max-w-3xl mx-auto">
            {{-- Barra de progreso --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-300 text-sm font-medium">Pregunta {{ $questionNumber }} de
                        {{ $test->total_questions }}</span>
                    <span class="text-gray-300 text-sm font-medium">{{ number_format($progress, 0) }}% completado</span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 h-full rounded-full transition-all duration-500"
                        style="width: {{ $progress }}%"></div>
                </div>
            </div>

            {{-- Pregunta --}}
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
                <div class="flex items-start gap-4 mb-6">
                    <div
                        class="bg-gradient-to-br from-purple-600 to-pink-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                        {{ $questionNumber }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-white mb-2">{{ $question->question }}</h2>
                        <p class="text-gray-400 text-sm">Selecciona tu nivel de acuerdo con esta afirmación</p>
                    </div>
                </div>

                <form action="{{ route('tests.save-answer', ['id' => $test->id, 'question' => $questionNumber]) }}"
                    method="POST" id="answerForm">
                    @csrf

                    @if ($question->type === 'scale')
                        {{-- Escala de 1 a 5 --}}
                        <div class="space-y-4">
                            <div class="flex justify-between text-sm text-gray-400 px-2">
                                <span>Nada de acuerdo</span>
                                <span>Muy de acuerdo</span>
                            </div>
                            <div class="grid grid-cols-5 gap-3">
                                @php
                                    $labels = ['Nada', 'Poco', 'Neutral', 'De acuerdo', 'Muy de acuerdo'];
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group block">
                                        <input type="radio" name="answer" value="{{ $i }}" class="peer hidden"
                                            {{ $previousAnswer && (int) $previousAnswer->answer === $i ? 'checked' : '' }}
                                            required>
                                        <div
                                            class="bg-white/5 border-2 border-gray-500 rounded-xl p-6 text-center 
                                                transition-all duration-300 transform hover:scale-105
                                                peer-checked:border-purple-500 peer-checked:bg-purple-600/30 
                                                peer-checked:scale-110 peer-checked:shadow-lg">

                                            <div
                                                class="text-3xl font-bold text-gray-400 
                                                    peer-checked:text-purple-200 mb-2 transition-colors duration-300">
                                                {{ $i }}</div>
                                            <div
                                                class="text-xs text-gray-500 peer-checked:text-purple-100 transition-colors duration-300">
                                                {{ $labels[$i - 1] }}</div>
                                        </div>

                                    </label>
                                @endfor
                            </div>
                        </div>
                    @elseif($question->type === 'yes_no')
                        {{-- Sí o No --}}
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="answer" value="yes" class="peer sr-only"
                                    {{ $previousAnswer && $previousAnswer->answer == 'yes' ? 'checked' : '' }} required>
                                <div
                                    class="bg-white/5 border-2 border-gray-500 rounded-xl p-8 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-500/20 hover:border-green-400">
                                    <svg class="w-12 h-12 text-gray-400 peer-checked:text-green-400 mx-auto mb-3"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div class="text-xl font-bold text-gray-300 peer-checked:text-green-300">Sí</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="answer" value="no" class="peer sr-only"
                                    {{ $previousAnswer && $previousAnswer->answer == 'no' ? 'checked' : '' }} required>
                                <div
                                    class="bg-white/5 border-2 border-gray-500 rounded-xl p-8 text-center transition-all peer-checked:border-red-500 peer-checked:bg-red-500/20 hover:border-red-400">
                                    <svg class="w-12 h-12 text-gray-400 peer-checked:text-red-400 mx-auto mb-3"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <div class="text-xl font-bold text-gray-300 peer-checked:text-red-300">No</div>
                                </div>
                            </label>
                        </div>
                    @elseif($question->type === 'multiple_choice')
                        {{-- Opción múltiple --}}
                        <div class="space-y-3">
                            @foreach (json_decode($question->options, true) as $value => $label)
                                <label class="block cursor-pointer">
                                    <input type="radio" name="answer" value="{{ $value }}" class="peer sr-only"
                                        {{ $previousAnswer && $previousAnswer->answer == $value ? 'checked' : '' }}
                                        required>
                                    <div
                                        class="bg-white/5 border-2 border-gray-500 rounded-xl p-5 transition-all peer-checked:border-purple-500 peer-checked:bg-purple-500/20 hover:border-purple-400 hover:bg-white/10">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-6 h-6 rounded-full border-2 border-gray-500 peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white hidden peer-checked:block"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span
                                                class="text-gray-300 peer-checked:text-white font-medium">{{ $label }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    {{-- Botones de navegación --}}
                    <div class="flex gap-4 mt-8">
                        @if ($questionNumber > 1)
                            <a href="{{ route('tests.question', ['id' => $test->id, 'question' => $questionNumber - 1]) }}"
                                class="flex-1 bg-white/10 text-white px-6 py-4 rounded-lg font-semibold hover:bg-white/20 transition-all text-center flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Anterior
                            </a>
                        @else
                            <a href="{{ route('tests.index') }}"
                                class="flex-1 bg-white/10 text-white px-6 py-4 rounded-lg font-semibold hover:bg-white/20 transition-all text-center">
                                Cancelar
                            </a>
                        @endif

                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-4 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all flex items-center justify-center gap-2">
                            @if ($questionNumber < $test->total_questions)
                                Siguiente
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            @else
                                Finalizar Test
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            @endif
                        </button>
                    </div>
                </form>
            </div>

            {{-- Indicador de preguntas respondidas --}}
            <div class="bg-white/5 backdrop-blur-lg rounded-xl p-4 border border-white/20 text-center">
                <p class="text-gray-400 text-sm">
                    Has respondido <span class="text-purple-400 font-bold">{{ $answeredCount }}</span> de <span
                        class="text-white font-bold">{{ $test->total_questions }}</span> preguntas
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit cuando se selecciona una opción (opcional, comentado por defecto)
        // document.querySelectorAll('input[name="answer"]').forEach(input => {
        //     input.addEventListener('change', function() {
        //         setTimeout(() => {
        //             document.getElementById('answerForm').submit();
        //         }, 300);
        //     });
        // });
    </script>
@endsection
