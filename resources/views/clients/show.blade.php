@extends('layouts.show')
@section('Title', 'Ver Cliente')
@section('Back')
    @can('viewAny', App\Entities\Client::class)
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> {{ __("Volver") }}
        </a>
    @endcan
@endsection
@section('Name')
    {{ $client->fullname }}
@endsection
@section('Buttons')
    @include('clients.__buttons')
@endsection
@section('Body')
    <div class="shadow">
        <div class="card-header text-center"><h3>{{ __("Datos generales") }}</h3></div>
        <table class="table border-rounded table-sm">
            <tr>
                <td class="table-dark td-title">{{ __("Tipo de documento:") }}</td>
                <td class="td-content">{{ $client->type_document->fullname }}</td>

                <td class="table-dark td-title">{{ __("Número de documento:") }}</td>
                <td class="td-content">{{ $client->document }}</td>
            </tr>
            <tr>
                <td class="table-dark td-title">{{ __("Fecha de creación:")}}</td>
                <td class="td-content">{{ $client->created_at->isoFormat('Y-MM-DD hh:mma') }}</td>

                <td class="table-dark td-title">{{ __("Fecha de modificación:")}}</td>
                <td class="td-content">{{ $client->updated_at->isoFormat('Y-MM-DD hh:mma') }}</td>
            </tr>
            <tr>
                <td class="table-dark td-title">{{ __("Creado por:") }}</td>
                <td class="td-content">
                    <a @can('view', $client->creator)
                       href="{{ route('users.show', $client->creator) }}"
                        @endcan>
                        {{ $client->creator->fullname }}
                    </a>
                </td>

                <td class="table-dark td-title">{{ __("Modificado por:")}}</td>
                <td class="td-content">
                    <a @can('view', $client->updater)
                       href="{{ route('users.show', $client->updater) }}"
                        @endcan>
                        {{ $client->updater->fullname }}
                    </a>
                </td>
            </tr>
            <tr>
                <td class="table-dark td-title">{{ __("Número telefónico:")}}</td>
                <td class="td-content">{{ $client->phone }}</td>

                <td class="table-dark td-title">{{ __("Celular:")}}</td>
                <td class="td-content">{{ $client->cellphone }}</td>
            </tr>
            <tr>
                <td class="table-dark td-title">{{ __("Dirección:")}}</td>
                <td class="td-content">{{ $client->address }}</td>

                <td class="table-dark td-title">{{ __("Correo electrónico:")}}</td>
                <td class="td-content">{{ $client->email }}</td>
            </tr>
        </table>
    </div>
    <br>
    <div class="shadow">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    @can('create', App\Entities\Invoice::class)
                        <a class="btn btn-success"
                           href="{{ route('invoices.create', [
                                            'client_id' => $client->id,
                                            'client' => $client->fullname
                                        ]) }}" >
                            <i class="fa fa-plus"></i>
                        </a>
                    @endcan
                </div>
                <div class="col">
                    <h3 class="text-center">{{ __("Facturas asociadas") }}</h3>
                </div>
                <div class="col d-flex justify-content-end">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>{{ __("Título") }}</th>
                    <th>{{ __("Fecha de expedición") }}</th>
                    <th>{{ __("Fecha de vencimiento") }}</th>
                    <th>{{ __("Valor") }}</th>
                    <th>{{ __("Estado") }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                @can('view', $invoice)
                    <tr>
                        <td>
                            <a href="{{ route('invoices.show', $invoice) }}">
                                {{ __("Factura de venta No.")}} {{ $invoice->id }}
                            </a>
                        </td>
                        <td>{{ $invoice->issued_at->toDateString() }}</td>
                        <td>{{ $invoice->expires_at->toDateString() }}</td>
                        <td>${{ number_format($invoice->total, 2) }}</td>
                        @include('invoices.status_label')
                    </tr>
                @endcan
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
