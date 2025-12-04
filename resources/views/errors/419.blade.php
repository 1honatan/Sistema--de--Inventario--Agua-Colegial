@extends('layouts.app')

@section('title', 'Sesión Expirada')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-yellow-800 mb-2">
                        Sesión Expirada
                    </h2>
                    <p class="text-yellow-700 mb-4">
                        Tu sesión ha expirado por seguridad. Por favor, vuelve a intentarlo.
                    </p>
                    <p class="text-sm text-yellow-600 mb-6">
                        No te preocupes, tus datos están seguros. Simplemente haz clic en el botón de abajo para continuar.
                    </p>
                    <div class="space-y-2">
                        <button onclick="window.history.back()"
                                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                            ← Volver e Intentar Nuevamente
                        </button>
                        <a href="{{ route('admin.dashboard') }}"
                           class="block w-full text-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                            Ir al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-blue-800 mb-2">💡 Consejo:</h3>
            <p class="text-xs text-blue-700">
                Para evitar que esto vuelva a pasar, intenta enviar tus formularios dentro de las siguientes 24 horas después de abrirlos.
            </p>
        </div>
    </div>
</div>

<script>
// Auto-preservar datos del formulario si es posible
window.addEventListener('load', function() {
    // Si venimos de un formulario, intentar restaurar datos
    const formData = sessionStorage.getItem('lastFormData');
    if (formData) {
        console.log('Datos del formulario preservados:', JSON.parse(formData));
    }
});
</script>
@endsection
