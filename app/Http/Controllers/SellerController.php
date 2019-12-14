<?php

namespace App\Http\Controllers;

use App\Seller;
use App\TypeDocument;
use Illuminate\Http\Request;
use App\Http\Requests\SaveSellerRequest;

class SellerController extends Controller
{


    public function index(Request $request)
    {
        $sellers = Seller::orderBy('name')
            ->seller($request->get('seller_id'))
            ->typedocument($request->get('type_document_id'))
            ->document($request->get('document'))
            ->email($request->get('email'))
            ->paginate(10);
        return view('sellers.index', [
            'sellers' => $sellers,
            'request' => $request,
            'side_effect' => 'Se borrarán todas sus facturas asociadas'
        ]);
    }

    public function create()
    {
        return view('sellers.create', [
            'seller' => new Seller,
        ]);
    }

    public function store(SaveSellerRequest $request)
    {
        Seller::create($request->validated());

        return redirect()->route('sellers.index')->with('message', 'Vendedor creado satisfactoriamente');
    }

    public function show(Seller $seller)
    {
        return view('sellers.show', [
            'seller' => $seller,
            'side_effect' => 'Se borrarán todas sus facturas asociadas'
        ]);
    }

    public function edit(Seller $seller)
    {
        return view('sellers.edit', [
            'seller' => $seller,
        ]);
    }

    public function update(SaveSellerRequest $request, Seller $seller)
    {
        $seller->update($request->validated());

        return redirect()->route('sellers.show', $seller)->with('message', 'Vendedor actualizado satisfactoriamente');
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();

        return redirect()->route('sellers.index')->with('message', 'Vendedor eliminado satisfactoriamente');
    }
}
