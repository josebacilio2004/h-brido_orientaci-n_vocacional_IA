<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Sistema IA Vocacional') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

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
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 102, 241, 0.3);
        }
        
        .btn-secondary {
            background: var(--gradient-accent);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(6, 182, 212, 0.3);
        }
    </style>
</head>
<body class="antialiased">
    <div id="app">
        @yield('content')
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
