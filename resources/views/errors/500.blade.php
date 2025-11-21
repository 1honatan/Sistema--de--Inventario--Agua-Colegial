<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error del Servidor | Agua Colegial</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --azul-oscuro-institucional: #073d71;
            --azul-claro-institucional: #1a8cff;
        }

        body {
            background: linear-gradient(135deg, #92400e 0%, #d97706 50%, #f59e0b 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .error-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-header {
            background: linear-gradient(135deg, #92400e 0%, #d97706 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .error-number {
            font-size: 6rem;
            font-weight: 900;
            line-height: 1;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: spin 3s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .btn-home {
            background: linear-gradient(135deg, #073d71 0%, #0a4d8f 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(7, 61, 113, 0.3);
        }

        .btn-home:hover {
            background: linear-gradient(135deg, #0a4d8f 0%, #1a8cff 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(7, 61, 113, 0.4);
        }

        .btn-back {
            background: transparent;
            color: #073d71;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: 2px solid #073d71;
        }

        .btn-back:hover {
            background: #073d71;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <!-- Header -->
            <div class="error-header">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="error-number">500</div>
                <p class="text-xl font-semibold mt-2">Error del Servidor</p>
            </div>

            <!-- Body -->
            <div class="p-8 text-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                    Algo salió mal en el servidor
                </h1>

                <p class="text-gray-600 mb-6">
                    Lo sentimos, estamos experimentando problemas técnicos. Nuestro equipo ha sido notificado y está trabajando para resolver el problema.
                </p>

                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 text-left">
                    <div class="flex items-start">
                        <i class="fas fa-tools text-yellow-500 text-xl mr-3 mt-1"></i>
                        <div>
                            <p class="text-sm text-gray-700">
                                <strong>¿Qué puedes hacer?</strong>
                            </p>
                            <ul class="text-sm text-gray-600 mt-2 space-y-1">
                                <li>• Intenta recargar la página en unos momentos</li>
                                <li>• Verifica tu conexión a internet</li>
                                <li>• Si el problema persiste, contacta al soporte técnico</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button onclick="window.location.reload()" class="btn-back">
                        <i class="fas fa-redo"></i>
                        Recargar Página
                    </button>

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn-home">
                            <i class="fas fa-home"></i>
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-home">
                            <i class="fas fa-sign-in-alt"></i>
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>

                <!-- Información adicional -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-code mr-1"></i>
                        Código de error: <strong>500 - Internal Server Error</strong>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Fecha: {{ now()->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-200">
                <div class="flex items-center justify-center gap-2 text-gray-600">
                    <i class="fas fa-tint text-blue-600"></i>
                    <span class="font-semibold">Agua Colegial</span>
                    <span class="text-gray-400">|</span>
                    <span class="text-sm">Sistema de Gestión</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
