@extends('layouts.dashboard')

@section('title', 'Predicción con IA')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <a href="{{ route('tests.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a Tests
        </a>

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900 mb-2">Predicción con Inteligencia Artificial</h1>
                <p class="text-slate-600">Ingresa tus notas académicas para obtener una recomendación personalizada basada en ML</p>
            </div>
            <div class="hidden lg:block">
                <svg class="w-20 h-20 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
        </div>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-start gap-3">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Enhanced form with info box, better validation messages, and organized layout -->
        <form action="{{ route('tests.grades.submit') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-1">¿Cómo funciona?</h3>
                        <p class="text-blue-800 text-sm leading-relaxed">Nuestro modelo avanzado de Machine Learning (Gradient Boosting) analiza tus calificaciones en todas las 9 materias del currículo y predice las carreras universitarias más adecuadas según tu perfil académico. Ingresa tus notas del 0 al 20.</p>
                    </div>
                </div>
            </div>

            <!-- Grades Form -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6">Ingresa tus Calificaciones</h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Matemática -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Matemática <span class="text-red-600">*</span></label>
                        <input type="number" name="nota_matematica" min="0" max="20" 
                               value="{{ old('nota_matematica', $grades->nota_matematica ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                               placeholder="0-20" required>
                        @error('nota_matematica')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comunicación -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Comunicación <span class="text-red-600">*</span></label>
                        <input type="number" name="nota_comunicacion" min="0" max="20"
                               value="{{ old('nota_comunicacion', $grades->nota_comunicacion ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                               placeholder="0-20" required>
                        @error('nota_comunicacion')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ciencias Sociales -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Ciencias Sociales <span class="text-red-600">*</span></label>
                        <input type="number" name="nota_ciencias_sociales" min="0" max="20"
                               value="{{ old('nota_ciencias_sociales', $grades->nota_ciencias_sociales ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                               placeholder="0-20" required>
                        @error('nota_ciencias_sociales')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ciencia y Tecnología -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Ciencia y Tecnología <span class="text-red-600">*</span></label>
                        <input type="number" name="nota_ciencia_tecnologia" min="0" max="20"
                               value="{{ old('nota_ciencia_tecnologia', $grades->nota_ciencia_tecnologia ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                               placeholder="0-20" required>
                        @error('nota_ciencia_tecnologia')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Desarrollo Personal -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Desarrollo Personal <span class="text-red-600">*</span></label>
                        <input type="number" name="nota_desarrollo_personal" min="0" max="20"
                               value="{{ old('nota_desarrollo_personal', $grades->nota_desarrollo_personal ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                               placeholder="0-20" required>
                        @error('nota_desarrollo_personal')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ciudadanía y Cívica -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Ciudadanía y Cívica <span class="text-red-600">*</span></label>
                        <input type="number" name="nota_ciudadania_civica" min="0" max="20"
                               value="{{ old('nota_ciudadania_civica', $grades->nota_ciudadania_civica ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                               placeholder="0-20" required>
                        @error('nota_ciudadania_civica')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Educación Física -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Educación Física <span class="text-red-600">*</span></label>
                        <input type="number" name="nota_educacion_fisica" min="0" max="20"
                               value="{{ old('nota_educacion_fisica', $grades->nota_educacion_fisica ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                               placeholder="0-20" required>
                        @error('nota_educacion_fisica')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Inglés -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Inglés <span class="text-red-600">*</span></label>
                        <input type="number" name="nota_ingles" min="0" max="20"
                               value="{{ old('nota_ingles', $grades->nota_ingles ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                               placeholder="0-20" required>
                        @error('nota_ingles')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Educación para el Trabajo -->
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Educación para el Trabajo <span class="text-red-600">*</span></label>
                        <input type="number" name="nota_educacion_trabajo" min="0" max="20"
                               value="{{ old('nota_educacion_trabajo', $grades->nota_educacion_trabajo ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                               placeholder="0-20" required>
                        @error('nota_educacion_trabajo')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4">
                <a href="{{ route('tests.index') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-900 px-6 py-3 rounded-lg font-semibold transition-colors text-center">
                    Cancelar
                </a>
                <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-6 py-4 rounded-lg font-semibold transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Obtener Predicción con IA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
