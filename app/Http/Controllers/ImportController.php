<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\InvoicesImport;
use App\Imports\ClientsImport;
use App\Imports\SellersImport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    /**
     * Imports a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function invoices(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        $file = $request->file('file');
        try {
            $import = new InvoicesImport();
            Excel::import($import, $file);
            $cant = $import->getRowCount();
            return redirect()->route('invoices.index')->withSuccess(__("Se importaron {$cant} facturas satisfactoriamente"));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $err) {
            return $this->displayErrors($err, 'invoices.index');
        }
    }

    /**
     * Imports a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function clients(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        $file = $request->file('file');
        try {
            $import = new ClientsImport();
            Excel::import($import, $file);
            $cant = $import->getRowCount();
            return redirect()->route('clients.index')->withSuccess(__("Se importaron {$cant} clientes satisfactoriamente"));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $err) {
            return $this->displayErrors($err, 'clients.index');
        }
    }

    public function sellers(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        $file = $request->file('file');
        try {
            $import = new SellersImport();
            Excel::import($import, $file);
            $cant = $import->getRowCount();
            return redirect()->route('sellers.index')->withSuccess(__("Se importaron {$cant} vendedores satisfactoriamente"));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $err) {
            return $this->displayErrors($err, 'sellers.index');
        }
    }

    public function products(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        $file = $request->file('file');
        try {
            $import = new ProductsImport();
            Excel::import($import, $file);
            $cant = $import->getRowCount();
            return redirect()->route('sellers.index')->withSuccess(__("Se importaron {$cant} productos satisfactoriamente"));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $err) {
            return $this->displayErrors($err, 'products.index');
        }
    }

    public function displayErrors($err, $route)
    {
        $failures_unsorted = $err->failures();
        $failures_sorted = array();
        foreach ($failures_unsorted as $failure) {
            $failures_sorted[$failure->row()][$failure->attribute()] = $failure->errors()[0];
        }
        return response()->view('imports.errors', [
            'failures' => $failures_sorted,
            'route' => $route
        ]);
    }
}