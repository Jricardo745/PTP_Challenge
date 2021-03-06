@extends('layouts.app')
@section('title', 'Editar Detalle')
@section('content')
    <div class="card">
        <div class="card-header">
            <h1>{{ __("Editar detalle") }} {{ $invoice->fullname }}</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('invoices.products.update', [$invoice, $product]) }}" class="form-group" method="POST">
                @method('PUT') @csrf
                <div class="row">
                    <div class="col">
                        <label>{{ __("Producto") }}</label>
                        <span class="form-control">
                            {{ $product->name }}
                        </span>
                    </div>
                    <div class="col">
                        <label>{{ __("Precio unitario") }}</label>
                        <span class="form-control">
                            {{ $product->price }}
                        </span>
                    </div>
                    @include('invoices.products.__form', [
                        'quantity' => $invoice->products->find($product->id)->pivot->quantity,
                    ])
                </div>
                <br>
                @include('invoices.products.__buttons')
            </form>
        </div>
    </div>
@endsection
