<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Sellers/Index', [
            'sellers' => Seller::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('Sellers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        Seller::create($request->all());

        return redirect()->route('sellers.index')
            ->with('success', 'Seller created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seller $seller)
    {
        return Inertia::render('Sellers/Edit', [
            'seller' => $seller,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $seller->update($request->all());

        return redirect()->route('sellers.index')
            ->with('success', 'Seller updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller)
    {

        if($seller->invoices->count() >= 1){
            return redirect()->route('sellers.index')->with('error', 'No se puede eliminar el Vendedor, tiene facturas asociadas');
        }
        $seller->delete();
        return redirect()->route('sellers.index')->with('success', 'Vendedor eliminado con éxito');
    }
}
