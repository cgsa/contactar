@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Hello') . ' '. auth()->user()->first_name,
'description' => __('Este es su dashboard, desde aquí puede gestionar los servicios que le ofrecemos'),
'class' => 'col-lg-7'
])

<div class="container-fluid mt--7">
    <div class="row">

        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">{{ __('Información de acceso') }}</h3>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <p class="mt-0 mb-2">
                                En está sección puede crear un cliente para tener acceso al API
                            </p>
                        </div>
                    </div>

                    @if(!$cliente)
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Crear Cliente
                    </button>
                    @endif
                </div>
                @if($cliente)
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Client Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Redirect</th>
                                <th scope="col">Secret</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{ $cliente->id }}
                                </td>
                                <td>
                                    {{ $cliente->name }}
                                </td>
                                <td>
                                    {{ $cliente->redirect }}
                                </td>
                                <td>
                                    {{ $cliente->secret }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="tab-pane tab-example-result mt-5 ml-4 show active" role="tabpanel" aria-labelledby="-component-tab">
                    <blockquote class="blockquote">
                        <p class="mb-0">A continuación se muestra un ejemplo para realizar una peteción al API, para generar un access token.</p>
                        <footer class="blockquote-footer">
                        <pre>
                        $client = new Http\Client
                        $response = $client->post('http://contactar.com.ar/oauth/token', [
                            'form_params' => [
                                'grant_type' => 'client_credentials',
                                'client_id' => 'client-id',
                                'client_secret' => 'client-secret',
                                'scope' => '',
                            ],
                        ])
                        </pre>
                        </footer>
                    </blockquote>
                </div>
                @endif
                <hr class="my-4" />

            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">

        <div class="modal-content">
            <form method="post" action="{{ route('passport.clients.store') }}" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', auth()->user()->name) }}" required autofocus>

                            @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('redirect') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-redirect">{{ __('Web Site') }}</label>
                            <input type="text" name="redirect" id="input-redirect" class="form-control form-control-alternative{{ $errors->has('redirect') ? ' is-invalid' : '' }}" placeholder="{{ __('redirect') }}" value="{{ old('redirect') }}" required>

                            @if ($errors->has('redirect'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('redirect') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.footers.auth')
</div>
@endsection