<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Sistema IA Vocacional') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #f1f5f9;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --light: #f8fafc;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-accent: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-success: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            --gradient-warning: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .module-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .module-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 102, 241, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: var(--gradient-accent);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
            color: white;
            text-decoration: none;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            padding: 12px 16px;
            border-radius: 8px;
            margin: 4px 0;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary);
        }
    </style>
</head>

<body class="antialiased">
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="sidebar w-64 flex-shrink-0">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 border-b border-white/20">
                    <div class="flex items-center space-x-2">
                        <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-white font-bold text-lg">FuturoSmart</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link flex items-center space-x-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('dashboard.tests') }}"
                        class="nav-link flex items-center space-x-3 {{ request()->routeIs('dashboard.tests') ? 'active' : '' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Tests Vocacionales</span>
                    </a>

                    <a href="{{ route('dashboard.careers') }}"
                        class="nav-link flex items-center space-x-3 {{ request()->routeIs('dashboard.careers') ? 'active' : '' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span>Carreras</span>
                    </a>

                    <a href="{{ route('dashboard.recommendations') }}"
                        class="nav-link flex items-center space-x-3 {{ request()->routeIs('dashboard.recommendations') ? 'active' : '' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span>Recomendaciones IA</span>
                    </a>

                    <a href="{{ route('dashboard.profile') }}"
                        class="nav-link flex items-center space-x-3 {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Mi Perfil</span>
                    </a>
                </nav>

                <!-- User info -->
                <div class="px-4 py-4 border-t border-white/20">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                            <p class="text-white/60 text-xs truncate">{{ Auth::user()->school }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-white/60 hover:text-white transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-gray-600 mt-1">@yield('page-description', 'Bienvenido a tu sistema de orientación vocacional')</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-400">{{ now()->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <!-- N8N Chat Integration -->
    <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />
    <style>
        /* Estilos mejorados para el chat */
        .n8n-chat {
            --n8n-chat-primary: #667eea;
            --n8n-chat-primary-hover: #5a67d8;
            --n8n-chat-bg-color: rgba(255, 255, 255, 0.95);
            --n8n-chat-border-radius: 20px;
            --n8n-chat-box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2);
            --n8n-chat-font-family: 'Inter', 'Poppins', sans-serif;
        }

        .n8n-chat-container {
            border-radius: var(--n8n-chat-border-radius) !important;
            box-shadow: var(--n8n-chat-box-shadow) !important;
            overflow: hidden;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .n8n-chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            padding: 1.5rem !important;
            position: relative;
            overflow: hidden;
        }

        .n8n-chat-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .n8n-chat-title {
            font-weight: 700 !important;
            font-size: 1.2rem !important;
        }

        .n8n-chat-subtitle {
            opacity: 0.9 !important;
            font-weight: 400 !important;
            margin-top: 0.25rem !important;
        }

        .n8n-chat-message-bot {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%) !important;
            border-radius: 20px 20px 20px 5px !important;
            max-width: 85% !important;
            border: 1px solid rgba(102, 126, 234, 0.1);
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
        }

        .n8n-chat-message-user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border-radius: 20px 20px 5px 20px !important;
            max-width: 85% !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .n8n-chat-input-container {
            border-top: 1px solid rgba(102, 126, 234, 0.1) !important;
            padding: 1.25rem !important;
            background: rgba(248, 249, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .n8n-chat-input {
            border-radius: 25px !important;
            padding: 1rem 1.5rem !important;
            border: 2px solid rgba(102, 126, 234, 0.2) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .n8n-chat-input:focus {
            outline: none !important;
            border-color: var(--n8n-chat-primary) !important;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2) !important;
            transform: translateY(-1px);
        }

        .n8n-chat-send-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 50% !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .n8n-chat-send-button:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .n8n-chat-new-conversation-button {
            background: rgba(255, 255, 255, 0.9) !important;
            color: var(--n8n-chat-primary) !important;
            border: 2px solid var(--n8n-chat-primary) !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            padding: 0.75rem 1.5rem !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .n8n-chat-new-conversation-button:hover {
            background: var(--n8n-chat-primary) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Dark mode para el chat */
        .dark-mode .n8n-chat-container {
            background: rgba(0, 0, 0, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark-mode .n8n-chat-message-bot {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
            color: #e2e8f0 !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark-mode .n8n-chat-input-container {
            background: rgba(0, 0, 0, 0.6) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .dark-mode .n8n-chat-input {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }

        /* Animaciones mejoradas */
        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .n8n-chat-message {
            animation: messageSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .n8n-chat-typing-indicator {
            animation: pulse 1.5s ease-in-out infinite;
        }
    </style>

    <script type="module">
        import {
            createChat
        } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

        createChat({
            webhookUrl: "{{ env('AI_ENDPOINT') }}",
            defaultLanguage: 'es',
            initialMessages: [
                '¡Hola! 👋 Bienvenido a FuturoSmart',
                'Soy tu asistente virtual inteligente. ¿En qué puedo ayudarte hoy?',
                '💡 Puedo ayudarte con información sobre tus intereses, predicciones, recomendaciones y más.'
            ],
            i18n: {
                es: {
                    title: '🤖 Asistente Virtual',
                    subtitle: 'Powered by AI • Siempre aquí para ayudarte',
                    footer: 'Respuestas generadas por IA avanzada con Machine Learning',
                    getStarted: '✨ Nueva conversación',
                    inputPlaceholder: 'Escribe tu pregunta aquí...',
                },
            },
            theme: {
                primaryColor: '#667eea',
                secondaryColor: '#f8f9ff',
                textColorOnPrimary: '#ffffff',
                textColor: '#2d3748',
            },
            settings: {
                avatarUrl: 'https://cdn-icons-png.flaticon.com/512/4712/4712035.png',
                botName: 'Asistente IA',
                userAvatarUrl: 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png',
                showWelcomeScreen: true,
                showPoweredBy: false,
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
