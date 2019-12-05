<?php

namespace App\Http\Controllers;

use App\State;
use App\Client;
use App\Seller;
use App\Invoice;
use Illuminate\Http\Request;
use App\Exports\InvoicesExport;
use App\Http\Requests\SaveInvoiceRequest;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $number = $request->get('number');
        $client = $request->get('client');
        $seller = $request->get('seller');
        $state_id = $request->get('state_id');
        $client_id = $request->get('client_id');
        $seller_id = $request->get('seller_id');
        $issued_init = $request->get('issued_init');
        $issued_final = $request->get('issued_final');
        $overdued_init = $request->get('overdued_init');
        $overdued_final = $request->get('overdued_final');

        $invoices = Invoice::orderBy('id', 'DESC')
            ->number($number)
            ->state($state_id)
            ->client($client_id)
            ->seller($seller_id)
            ->issuedDate($issued_init, $issued_final)
            ->overduedDate($overdued_init, $overdued_final)
            ->paginate(10);
        return view('invoices.index', [
            'invoices' => $invoices,
            'states' => State::all(),
            'request' => $request,
            'side_effect' => 'Se borrarán todos sus detalles asociados'
        ]);
    }

    public function create()
    {
        return view('invoices.create', [
            'invoice' => new Invoice,
            'states' => State::all(),
        ]);
    }

    public function store(SaveInvoiceRequest $request)
    {
        Invoice::create($request->validated());

        return redirect()->route('invoices.index')->with('message', 'Factura creada satisfactoriamente');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', [
            'invoice' => $invoice,
            'side_effect' => 'Se borrarán todos sus detalles asociados'
        ]);
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', [
            'invoice' => $invoice,
            'states' => State::all()
        ]);
    }

    public function update(SaveInvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->validated());

        return redirect()->route('invoices.show', $invoice)->with('message', 'Factura actualizada satisfactoriamente');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with('message', 'Factura eliminada satisfactoriamente');
    }

    public function exportExcel(){
        return Excel::download(new InvoicesExport, 'invoices-list.xlsx');
    }
}
