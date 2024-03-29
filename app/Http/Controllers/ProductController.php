<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Products/Index', [
            'products' => Product::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code_number' => 'required|unique:products',
            'name' => ['required'],
            'unit_price' => ['required', 'numeric'],
        ],
        [
            'code_number.required' => 'Code number is required',
            'code_number.numeric' => 'Code number must be numeric',
            'code_number.unique' => 'Ya existe un producto con este codigo',
            'name.required' => 'Name is required',
            'unit_price.required' => 'Unit price is required',
            'unit_price.numeric' => 'Unit price must be numeric',
        ]);


        Product::create($request->all());

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

        $request->validate([
            'code_number' => 'required|unique:products,code_number,' . $product->id,
            'name' => ['required'],
            'unit_price' => ['required', 'numeric'],
            //'serial' => 'unique:products,serial,' . $product->id,
        ],
        [
            'code_number.required' => 'Code number is required',
            'code_number.numeric' => 'Code number must be numeric',
            'code_number.unique' => 'Ya existe un producto con este codigo',
            'name.required' => 'Name is required',
            'unit_price.required' => 'Unit price is required',
            'unit_price.numeric' => 'Unit price must be numeric',
            'serial.unique' => 'Ya existe un producto con este serial',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index');
    }

    public function updateSerial(Request $request, Product $product)
    {
        $request->validate([
            'serial' => 'required|unique:products,serial,' . $product->id,
        ],
        [
            'serial.required' => 'Serial es requerido',
            'serial.unique' => 'Ya existe un producto con este serial',
        ]);

        $product->serial = $request->serial;
        $product->save();


        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->invoices->count() > 0) {
            return redirect()->route('products.index')->with('error', 'No se puede eliminar el producto porque tiene ordenes asociadas');
        }

        $product->delete();

        return redirect()->route('products.index');
    }

    public function camara()
    {
        return Inertia::render('Products/Camara');
    }
}
