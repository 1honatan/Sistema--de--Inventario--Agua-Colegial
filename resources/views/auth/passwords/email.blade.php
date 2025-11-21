@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" style="border-radius: 20px;">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%); border-radius: 20px 20px 0 0;">
                    <h3 class="text-white mb-0 font-weight-bold">
                        <i class="fas fa-key mr-2"></i>
                        Recuperar Contraseña
                    </h3>
                </div>

                <div class="card-body p-5">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4 text-center">
                        Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                    </p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="email" class="font-weight-bold text-dark">
                                <i class="fas fa-envelope mr-2"></i>Correo Electrónico
                            </label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tu@correo.com" style="border-radius: 10px;">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <button type="submit" class="btn btn-lg btn-block text-white font-weight-bold" style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%); border-radius: 10px; padding: 12px;">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Enviar Enlace
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-muted">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Volver al inicio de sesión
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
