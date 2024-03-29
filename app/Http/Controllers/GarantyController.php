<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Garanty;
use App\Models\GarantyProduct;

class GarantyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Garanty/Index', [
            'garanties' => Garanty::with('invoice','invoice.customer', 'garanty_products', 'garanty_products.product')
                ->whereHas('invoice', function ($query) {
                    $query->where('status', 1);
                })->get(),

            'flash' => [
                'error' => session('error'),
            ],
            'productsData' => session('productsData'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function serialUpdate(Request $request)
    {
        //rule serial unique
        $request->validate([
            'serial' => 'required|unique:garanty_products,serial',
        ],
        [
            'serial.unique' => 'El serial ya ha sido registrado.',
        ]);

        $garanty_products = GarantyProduct::find($request->id);
        $garanty_products->serial = $request->serial;
        $garanty_products->save();

        $garanty = Garanty::find($garanty_products->garanty_id);

       return redirect()->route('garanty.index');
    }

    public function createPDF(Garanty $garanty)
    {
        $sinSerial = 0;

        foreach ($garanty->garanty_products as $product) {
            if (is_null($product->serial)) {
                $sinSerial++;
            }
        }

        if ($sinSerial > 0) {
            return redirect()->route('garanty.index')
                            ->with([
                                    'error'=> "No se puede imprimir porque aún hay $sinSerial productos sin serial.",
                                    'productsData' => $garanty->garanty_products->load('product'),
                                ]);
        }

        // Continuar con la operación si todos los productos tienen serial


        return Inertia::render('Garanty/PDF', [
            'garanty' => $garanty->load(['invoice.customer', 'garanty_products', 'garanty_products.product']),
        ]);
    }

}
