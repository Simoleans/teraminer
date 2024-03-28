<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceStoreRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\{Customer,Seller,Product,Shipment,Data,Garanty,GarantyProduct};
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Invoices/Index', [
            'invoices' => Invoice::with(['customer', 'seller', 'shipment'])->get(),
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
    public function store(InvoiceStoreRequest $request)
    {

        $response = [
            'total' => $request->totalInvoice,
            'discount' => $request->discount,
            'subtotal' => $request->subTotal,
            'products' => $request->productsArray,
            'shipment_id' => $request->shipment_id,
            'customer_id' => $request->customer_id,
            'seller_id' => $request->seller_id,
            'correlative' => $request->correlative,
        ];

        //create invoice
        $invoice = Invoice::create($response);

        //create garanties
        $garanty = Garanty::create([
            'invoice_id' => $invoice->id,
        ]);

        foreach ($request->productsArray as $product) {
            for($i = 0; $i < $product['quantity']; $i++) {
                GarantyProduct::create([
                    'garanty_id' => $garanty->id,
                    'product_id' => $product['id'],
                    'serial' => $product['serial'],
                ]);
            }
        }

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        return Inertia::render('Invoices/Edit', [
            'invoice' => $invoice->load(['customer', 'seller', 'shipment']),
            'shipments' => Shipment::all(),
            'customers' => Customer::all(),
            'sellers' => Seller::all(),
            'products' => Product::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //dd($request->all());
/*
        $response = [
            'total' => $request->totalInvoice,
            'discount' => $request->discount,
            'subtotal' => $request->subTotal,
            'products' => $request->productsArray,
            'shipment_id' => $request->shipment_id,
            'customer_id' => $request->customer_id,
            'seller_id' => $request->seller_id,
            'correlative' => $invoice->correlative,
        ]; */

        //$response update status to 0
        $response = [
            'status' => 0,
        ];

        //update invoice
        $invoice->update($response);

        return redirect()->route('invoices.index');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    /* public function createPDF(Invoice $invoice)
    {
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        return $pdf->download('invoice.pdf');
    } */

    public function createPDF(Invoice $invoice)
    {
        return Inertia::render('Invoices/PDF', [
            'invoice' => $invoice->load(['customer', 'seller', 'shipment']),
            'data' => Data::first(),
        ]);
    }

    public function createGaranty(Invoice $invoice)
    {
        return Inertia::render('Invoices/Garanty', [
            'invoice' => $invoice->load(['customer', 'seller', 'shipment']),
            'data' => Data::first(),
        ]);
    }

}
