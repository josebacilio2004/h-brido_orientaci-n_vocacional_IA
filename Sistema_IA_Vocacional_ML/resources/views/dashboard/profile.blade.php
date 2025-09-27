
@extends('layouts.dashboard')

@section('title', 'Mi Perfil')
@section('page-title', 'Mi Perfil')
@section('page-description', 'Gestiona tu información personal y preferencias')

@section('content')
<div class="space-y-6">
    <!-- Información Personal -->
    <div class="glass-card rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Información Personal</h3>
            <button class="btn-primary text-sm px-4 py-2">Editar Perfil</button>
        </div>
        
        <div class="flex items-center space-x-6 mb-6">
            <div class="h-20 w-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                <span class="text-white text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="text-sm text-gray-500">{{ $user->school }} • {{ $user->grade }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre completo</label>
                <input type="text" value="{{ $user->name }}" disabled 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Correo electrónico</label>
                <input type="email" value="{{ $user->email }}" disabled 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Grado</label>
                <input type="text" value="{{ $user->grade }}" disabled 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Institución educativa</label>
                <input type="text" value="{{ $user->school }}" disabled 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
            </div>
        </div>
    </div>

    <!-- Progreso del Perfil -->
    <div class="glass-card rounded-xl p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Progreso del Perfil</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-gray-700">Información básica</span>
                <span class="text-green-600 font-medium">Completado</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-gray-700">Test vocacional</span>
                <span class="text-yellow-600 font-medium">En progreso</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-yellow-500 h-2 rounded-full" style="width: 60%"></div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-gray-700">Exploración de carreras</span>
                <span class="text-blue-600 font-medium">Iniciado</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-500 h-2 rounded-full" style="width: 30%"></div>
            </div>
        </div>
    </div>

    <!-- Configuraciones -->
    <div class="glass-card rounded-xl p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Configuraciones</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                <div>
                    <h4 class="font-medium text-gray-900">Notificaciones por email</h4>
                    <p class="text-sm text-gray-600">Recibe actualizaciones sobre nuevas carreras y tests</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                <div>
                    <h4 class="font-medium text-gray-900">Recomendaciones personalizadas</h4>
                    <p class="text-sm text-gray-600">Permite que la IA analice tu perfil para mejores sugerencias</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Acciones de Cuenta -->
    <div class="glass-card rounded-xl p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Acciones de Cuenta</h3>
        <div class="space-y-4">
            <button class="w-full md:w-auto btn-primary">Cambiar Contraseña</button>
            <button class="w-full md:w-auto btn-secondary ml-0 md:ml-4">Descargar Mis Datos</button>
            <div class="pt-4 border-t border-gray-200">
                <button class="text-red-600 hover:text-red-800 font-medium">Eliminar Cuenta</button>
            </div>
        </div>
    </div>
</div>
@endsection
