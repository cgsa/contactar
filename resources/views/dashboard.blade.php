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
                                <td id="clientId" rel="{{ $cliente->id }}">
                                    {{ $cliente->id }}
                                </td>
                                <td id="clientSecret" rel="{{ $cliente->secret }}">
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
                <div class="row">
                    <div class="ml-4 col-md-10 pt-6">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <h3 class="mb-0">{{ __('Prueba de Verificación') }}</h3>
                            </div>
                        </div>
                        <form action="" header="" method="GET" name="form-validate" id="form-validate">
                            @csrf
                            <input type="hidden" id="access_token" name="access_token" />
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="cod-pai" id="cod-pai" require>
                                            <option value="AR">{{_('AR - Argentina')}}</option>
                                            <option value="MX">{{_('AR - México')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="telefono" id="telefono-num" placeholder="{{_('Teléfono')}}" class="form-control" require />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-info btn-validate" value="{{ _('Probar')}}" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane tab-example-result mt-5 ml-4 show active" role="tabpanel" aria-labelledby="-component-tab">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">
                                    <i class="ni ni-cloud-upload-96 mr-2"></i>Format Json
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">
                                    <i class="ni ni-bell-55 mr-2"></i>Solicitudes
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                    <div class="alert alert-default" role="alert">
                                        <p class="mb-0">{{_('Ejemplo de respuesta')}}</p>
                                        <pre class="text-white" id="response_example_id">
                                            {
                                                "telefono": "0111559076538",
                                                "operador": "AMX ARGENTINA SOCIEDAD ANONIMA",
                                                "localidad": "AMBA",
                                                "es_movil": "SI"
                                            }
                                        </pre>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="table-responsive">
                                        <table class="table align-items-center" id="tbl_solicitudes">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">{{_('Teléfono')}}</th>
                                                    <th scope="col">{{_('Operador')}}</th>
                                                    <th scope="col">{{_('Localidad')}}</th>
                                                    <th scope="col">{{_('Es movil')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($solicitudes as $solicitud)
                                                <tr>
                                                    <td id="clientId" rel="{{ $cliente->id }}">
                                                        {{ $solicitud->numero_encontrado }}
                                                    </td>
                                                    <td id="clientSecret" rel="{{ $cliente->secret }}">
                                                        {{ $solicitud->operador }}
                                                    </td>
                                                    <td>
                                                        {{ $solicitud->localidad }}
                                                    </td>
                                                    <td>
                                                        {{ $solicitud->es_movil }}
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4">
                                                        {{_('No ha realizado ninguna solicitud')}}
                                                    </td>
                                                </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">

        <div class="modal-content">
            <form id="client-add-id" method="post" action="" autocomplete="off">
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
                    <button type="submit" class="btn btn-primary bt-add-client">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


@include('layouts.footers.auth')
</div>
@endsection
@push('js')
<script type="text/javascript">
    'use strict';

    const clientId = $('#clientId').attr('rel');
    const clientSecret = $('#clientSecret').attr('rel');
    const grantType = 'client_credentials';

    $('.bt-add-client').click(function(event) {

        var data = $('#client-add-id').serialize();

        $.ajax({
            url: "{{ route('passport.clients.store') }}",
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(_resp) {
                location.reload();
            },
            error: function() {
                alert('Hubo un error al crear el cliente');
            }
        });
    });

    $('.btn-validate').click(function(event) {

        event.preventDefault();

        try {

            if (!localStorage.getItem('client-token')) {
                login();
                return true;
            }

            validateNum();

        } catch (error) {
            alert('Hubo un error al intentar procesar la operación');
            console.debug(error);
        }


    });


    function validateNum() {

        let telefono = $('#telefono-num').val();
        let codPai = $('#cod-pai').val();

        if (telefono == '') {
            alert('Debe agregar un teléfono para hacer la prueba');
            return Error('Debe agregar un teléfono para hacer la prueba');
        }

        fetch("{{env('APP_URL')}}" + "/api/v1/validate", {
            method: 'POST',
            body: JSON.stringify({
                'cod-pai': codPai,
                'telefono': telefono
            }),
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('client-token'),
                'Content-Type': 'application/json'
            }
        }).then(response => {
            return response.json()
        }).then(data => {
            addRow(data.telefono);
        });
    }


    function addRow(phone) {
        let row = "<tr>" + td(phone.telefono) + td(phone.operador) + td(phone.localidad) + td(phone.es_movil) + "</tr>";
        $('#tbl_solicitudes tbody').prepend(row);
        $('#tbl_solicitudes tr:last').remove();
        $('#tabs-icons-text-2-tab').click();
    }


    function td(field) {
        return "<td>" + field + "</td>";
    }


    function login() {
        fetch("{{env('APP_URL')}}" + "/oauth/token", {
            method: 'POST',
            body: JSON.stringify({
                client_id: clientId,
                client_secret: clientSecret,
                grant_type: grantType,
                scope: ''
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(response => {
            return response.json()
        }).then(data => {
            localStorage.setItem('client-token', data.access_token);
            validateNum();
        });
    }
</script>
@endpush