@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Predicción de Carrera para {{ $student->name }}</h1>
        <p class="text-gray-600">Análisis basado en intereses y habilidades</p>
    </div>

    <!-- Resultados del Test -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-4">Resultados del Último Test</h2>
        @if($latestResult)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium mb-2">Puntaje Total</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $latestResult->total_score }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium mb-2">Fecha de Realización</h3>
                    <p class="text-gray-700">{{ $latestResult->completed_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        @else
            <p class="text-gray-600">No hay resultados disponibles</p>
        @endif
    </div>

    <!-- Intereses -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-4">Análisis de Intereses</h2>
        @foreach($interests as $category => $responses)
            <div class="mb-6">
                <h3 class="text-lg font-medium mb-2">{{ $category }}</h3>
                <div class="space-y-2">
                    @foreach($responses as $response)
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($response->value / 5) * 100 }}%"></div>
                            </div>
                            <span class="ml-2 text-sm text-gray-600">{{ $response->value }}/5</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $response->question->question_text }}</p>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Habilidades -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-4">Análisis de Habilidades</h2>
        @foreach($skills as $category => $responses)
            <div class="mb-6">
                <h3 class="text-lg font-medium mb-2">{{ $category }}</h3>
                <div class="space-y-2">
                    @foreach($responses as $response)
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($response->value / 5) * 100 }}%"></div>
                            </div>
                            <span class="ml-2 text-sm text-gray-600">{{ $response->value }}/5</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $response->question->question_text }}</p>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Carreras Recomendadas -->
    @if($latestResult && $latestResult->recommended_careers)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Carreras Recomendadas</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($latestResult->recommended_careers as $career)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-blue-600">{{ $career['name'] }}</h3>
                        <p class="text-sm text-gray-600">Coincidencia: {{ $career['match_percentage'] }}%</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection