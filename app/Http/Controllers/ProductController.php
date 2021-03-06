<?php

namespace App\Http\Controllers;

use Exception;
use App\Entities\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SaveProductRequest;
use App\Actions\Products\GetProductsAction;
use App\Actions\Products\StoreProductsAction;
use App\Actions\Products\UpdateProductsAction;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class);
    }

    /**
     * Display a listing of the resource.
     * @param GetProductsAction $action
     * @param Request $request
     * @return Response
     */
    public function index(GetProductsAction $action, Request $request)
    {
        $products = $action->execute(new Product(), $request->all())
            ->paginate($request->get('per_page'));

        return response()->view('products.index', compact('products', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Product $product
     * @return Response
     */
    public function create(Product $product)
    {
        return response()->view('products.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductsAction $action
     * @param SaveProductRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProductsAction $action, SaveProductRequest $request)
    {
        $product = $action->execute(new Product(), $request->validated());

        return redirect()->route('products.show', $product->id)
            ->with('success', ('Producto creado satisfactoriamente'));
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product)
    {
        return response()->view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        return response()->view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductsAction $action
     * @param SaveProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(
        UpdateProductsAction $action,
        Product $product,
        SaveProductRequest $request
    ) {
        $product = $action->execute($product, $request->validated());

        return redirect()->route('products.show', $product)
            ->with('success', ('Producto actualizado satisfactoriamente'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', ('Producto eliminado satisfactoriamente'));
    }
}
