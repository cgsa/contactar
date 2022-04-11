@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>{{ __('Verificamos su dirección de correo electrónico') }}</small>
                        </div>
                        <div>
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('La verificación de cuenta de correo electrónico se realizó de manera satisfactoria.') }}
                                </div>
                            @endif
                            
                            {{ __('Ya puede ingresar a nuestra plataforma y disfrutar de los recursos que tenemos para ofrecerle.') }}
                            <a href="{{ route('login') }}">{{ __('Ir al login') }}</a>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
