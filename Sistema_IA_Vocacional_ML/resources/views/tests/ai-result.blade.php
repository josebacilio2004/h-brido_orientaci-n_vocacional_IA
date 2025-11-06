@extends('layouts.dashboard')

@section('title', 'Resultados de Predicción - IA')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Dashboard
                </a>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-slate-900 mb-2">Análisis de Predicción con IA</h1>
                        <p class="text-slate-600">Basado en tu modelo de Machine Learning y desempeño académico</p>
                    </div>
                    <div class="hidden lg:block">
                        <svg class="w-20 h-20 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                    <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Main content area with prediction results from ML model -->
            <div class="grid lg:grid-cols-3 gap-6 mb-8">
                <!-- Left Column - Prediction Summary -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Model Prediction Card -->
                    @if ($prediction && is_array($prediction))
                        @php
                            $careerPredictions = $prediction['predictions'] ?? ($prediction['top_careers'] ?? []);
                            $confidence = $prediction['confidence'] ?? ($prediction['score'] ?? 85);
                            $analysis = $prediction['analysis'] ?? 'Análisis basado en tu desempeño académico agregado';
                        @endphp

                        <!-- Display ML model prediction results with confidence score -->
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-white">Predicción del Modelo ML</h2>
                                    <div class="text-right">
                                        <p class="text-indigo-100 text-sm">Confianza del Modelo</p>
                                        <p class="text-3xl font-bold text-white">{{ round($confidence, 1) }}%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-slate-700 mb-6">{{ $analysis }}</p>

                                <!-- Confidence Bar -->
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-slate-700">Precisión de Predicción</span>
                                        <span
                                            class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-semibold">{{ round($confidence, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 h-2 rounded-full"
                                            style="width: {{ $confidence }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Career predictions from ML model with descriptions -->
                        @if (is_array($careerPredictions) && count($careerPredictions) > 0)
                            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                                <div class="bg-slate-100 px-6 py-4 border-b border-slate-200">
                                    <h3 class="text-lg font-bold text-slate-900">Top Carreras Recomendadas</h3>
                                    <p class="text-sm text-slate-600 mt-1">Basadas en tu análisis de Machine Learning</p>
                                </div>
                                <div class="p-6 space-y-4">
                                    @php $index = 1; @endphp
                                    @foreach ($careerPredictions as $career)
                                        @php
                                            $careerName = is_array($career)
                                                ? $career['name'] ?? ($career['career'] ?? '')
                                                : (string) $career;
                                            $probability = is_array($career)
                                                ? $career['probability'] ?? ($career['score'] ?? 0)
                                                : 0;
                                            $icon_colors = [
                                                'bg-indigo-100 text-indigo-600',
                                                'bg-blue-100 text-blue-600',
                                                'bg-emerald-100 text-emerald-600',
                                                'bg-amber-100 text-amber-600',
                                                'bg-rose-100 text-rose-600',
                                            ];
                                            $color_class = $icon_colors[($index - 1) % count($icon_colors)];
                                        @endphp

                                        @if ($careerName)
                                            <div
                                                class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="flex items-center justify-center w-12 h-12 rounded-lg {{ $color_class }} font-bold text-lg">
                                                        {{ $index }}
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-slate-900">{{ $careerName }}</h4>
                                                    <p class="text-xs text-slate-600 mt-1">Probabilidad de compatibilidad
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="text-right">
                                                        <p class="text-lg font-bold text-indigo-600">
                                                            {{ round($probability, 1) }}%</p>
                                                        <div class="w-16 h-1.5 bg-slate-200 rounded-full mt-2">
                                                            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 h-1.5 rounded-full"
                                                                style="width: {{ $probability }}%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @php $index++; @endphp
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- No Prediction Message -->
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                            <div class="flex items-start gap-4">
                                <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4v2m0 4v2M7.08 6.06A9 9 0 1020.94 17.94M7.08 6.06A9 9 0 1020.94 17.94">
                                    </path>
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-amber-900">Predicción No Disponible</h3>
                                    <p class="text-amber-800 text-sm mt-1">No pudimos conectar con el servicio de IA. Tus
                                        notas han sido guardadas exitosamente.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Academic Summary -->
                <div class="space-y-6">
                    <!-- Grades Summary Card -->
                    @if ($grades)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="bg-emerald-50 px-6 py-4 border-b border-slate-200">
                                <h3 class="font-bold text-slate-900">Resumen de Notas</h3>
                            </div>
                            <div class="p-6 space-y-3">
                                @php
                                    $average =
                                        (($grades->nota_matematica ?? 0) +
                                            ($grades->nota_comunicacion ?? 0) +
                                            ($grades->nota_ciencias_sociales ?? 0) +
                                            ($grades->nota_ciencia_tecnologia ?? 0) +
                                            ($grades->nota_desarrollo_personal ?? 0) +
                                            ($grades->nota_ciudadania_civica ?? 0) +
                                            ($grades->nota_educacion_fisica ?? 0) +
                                            ($grades->nota_ingles ?? 0) +
                                            ($grades->nota_educacion_trabajo ?? 0)) /
                                        9;
                                @endphp

                                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                                    <span class="text-sm font-semibold text-slate-700">Promedio General</span>
                                    <span class="text-2xl font-bold text-emerald-600">{{ round($average, 1) }}/20</span>
                                </div>

                                <div class="space-y-2">
                                    @php
                                        $subjects = [
                                            ['name' => 'Matemática', 'key' => 'nota_matematica', 'color' => 'indigo'],
                                            ['name' => 'Comunicación', 'key' => 'nota_comunicacion', 'color' => 'blue'],
                                            [
                                                'name' => 'Sociales',
                                                'key' => 'nota_ciencias_sociales',
                                                'color' => 'emerald',
                                            ],
                                            [
                                                'name' => 'Tecnología',
                                                'key' => 'nota_ciencia_tecnologia',
                                                'color' => 'amber',
                                            ],
                                        ];
                                    @endphp
                                    @foreach ($subjects as $subject)
                                        @php
                                            $grade = $grades->{$subject['key']} ?? 0;
                                            $percentage = ($grade / 20) * 100;
                                        @endphp
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span
                                                    class="text-xs font-semibold text-slate-700">{{ $subject['name'] }}</span>
                                                <span
                                                    class="text-xs font-bold text-slate-900">{{ $grade }}/20</span>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5">
                                                <div class="bg-{{ $subject['color'] }}-500 h-1.5 rounded-full"
                                                    style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Info Card -->
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                        <h4 class="font-semibold text-indigo-900 mb-2">¿Qué es esto?</h4>
                        <p class="text-indigo-800 text-sm">Este análisis combina tus resultados de tests vocacionales con tu
                            desempeño académico mediante un modelo de Machine Learning avanzado para brindarte
                            recomendaciones personalizadas.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="{{ route('dashboard.careers') }}"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg text-center transition-colors">
                            Ver Carreras Detalladas
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="w-full bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold py-3 rounded-lg text-center transition-colors">
                            Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Next Steps Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Próximos Pasos</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 font-bold">
                                1</div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-900 mb-1">Explorar Carreras</h4>
                            <p class="text-sm text-slate-600">Conoce más detalles sobre las carreras recomendadas.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-600 font-bold">
                                2</div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-900 mb-1">Consultar con Orientador</h4>
                            <p class="text-sm text-slate-600">Habla con un orientador educativo sobre las opciones.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 font-bold">
                                3</div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-900 mb-1">Actualizar Información</h4>
                            <p class="text-sm text-slate-600">Actualiza tus notas para mejores predicciones.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
