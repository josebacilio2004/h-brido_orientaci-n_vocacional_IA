@extends('layouts.dashboard')

@section('title', 'Mis Notas Académicas')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Dashboard
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-slate-900 mb-2">Mis Notas Académicas</h1>
                    <p class="text-slate-600">Actualiza tus calificaciones para mejorar las predicciones de carrera</p>
                </div>
                <div class="hidden lg:block">
                    <svg class="w-20 h-20 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-emerald-900">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="font-semibold text-red-900 mb-2">Por favor revisa los siguientes errores:</p>
                <ul class="text-red-800 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Improved form with better UX: card layout, visual feedback, progress indicator -->
        <form method="POST" action="{{ route('grades.store') }}" class="space-y-6">
            @csrf

            <!-- Academic Year Selection -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <label class="block text-sm font-semibold text-slate-900 mb-3">Año Académico</label>
                <select name="academic_year" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ (old('academic_year', date('Y')) == $year) ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <!-- Grades Grid with Visual Indicators -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6">Ingresa tus Calificaciones</h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Added visual indicators and better labels for each subject -->
                    <!-- Matemática -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-slate-700">Matemática</label>
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-semibold">0-20</span>
                        </div>
                        <input type="number" name="nota_matematica" min="0" max="20" 
                            value="{{ old('nota_matematica', $grades->nota_matematica ?? '') }}" 
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" 
                            placeholder="0-20" required>
                        @error('nota_matematica')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Comunicación -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-slate-700">Comunicación</label>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-semibold">0-20</span>
                        </div>
                        <input type="number" name="nota_comunicacion" min="0" max="20" 
                            value="{{ old('nota_comunicacion', $grades->nota_comunicacion ?? '') }}" 
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" 
                            placeholder="0-20" required>
                        @error('nota_comunicacion')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Ciencias Sociales -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Ciencias Sociales</label>
                        <input type="number" name="nota_ciencias_sociales" min="0" max="20" 
                            value="{{ old('nota_ciencias_sociales', $grades->nota_ciencias_sociales ?? '') }}" 
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" 
                            placeholder="0-20" required>
                        @error('nota_ciencias_sociales')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Ciencia y Tecnología -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Ciencia y Tecnología</label>
                        <input type="number" name="nota_ciencia_tecnologia" min="0" max="20" 
                            value="{{ old('nota_ciencia_tecnologia', $grades->nota_ciencia_tecnologia ?? '') }}" 
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" 
                            placeholder="0-20" required>
                        @error('nota_ciencia_tecnologia')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Desarrollo Personal -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Desarrollo Personal</label>
                        <input type="number" name="nota_desarrollo_personal" min="0" max="20" 
                            value="{{ old('nota_desarrollo_personal', $grades->nota_desarrollo_personal ?? '') }}" 
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" 
                            placeholder="0-20" required>
                        @error('nota_desarrollo_personal')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Ciudadanía y Cívica -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Ciudadanía y Cívica</label>
                        <input type="number" name="nota_ciudadania_civica" min="0" max="20" 
                            value="{{ old('nota_ciudadania_civica', $grades->nota_ciudadania_civica ?? '') }}" 
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" 
                            placeholder="0-20" required>
                        @error('nota_ciudadania_civica')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Educación Física -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Educación Física</label>
                        <input type="number" name="nota_educacion_fisica" min="0" max="20" 
                            value="{{ old('nota_educacion_fisica', $grades->nota_educacion_fisica ?? '') }}" 
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" 
                            placeholder="0-20" required>
                        @error('nota_educacion_fisica')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Inglés -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Inglés</label>
                        <input type="number" name="nota_ingles" min="0" max="20" 
                            value="{{ old('nota_ingles', $grades->nota_ingles ?? '') }}" 
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" 
                            placeholder="0-20" required>
                        @error('nota_ingles')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Educación para el Trabajo -->
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Educación para el Trabajo</label>
                        <input type="number" name="nota_educacion_trabajo" min="0" max="20" 
                            value="{{ old('nota_educacion_trabajo', $grades->nota_educacion_trabajo ?? '') }}" 
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" 
                            placeholder="0-20" required>
                        @error('nota_educacion_trabajo')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('dashboard') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-900 px-6 py-3 rounded-lg font-semibold transition-colors text-center">
                    Cancelar
                </a>
                <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all">
                    Guardar Notas
                </button>
            </div>
        </form>

        <!-- Info Card -->
        <div class="mt-8 bg-indigo-50 border border-indigo-200 rounded-lg p-6">
            <h3 class="font-semibold text-indigo-900 mb-2">Recuerda</h3>
            <p class="text-indigo-800 text-sm">Tus notas académicas son utilizadas por nuestro modelo de Machine Learning para proporcionar recomendaciones de carreras más precisas y personalizadas.</p>
        </div>
    </div>
</div>
@endsection
