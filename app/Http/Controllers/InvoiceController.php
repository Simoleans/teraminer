<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceStoreRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\{Customer,Seller,Product};

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //dd($request->all(),json_encode($request->productsArray));


        $response = [
            'total' => $request->totalInvoice,
            'discount' => $request->discount,
            'subtotal' => $request->subTotal,
            'products' => $request->productsArray,
            'shipment_id' => $request->shipment_id,
            'customer_id' => $request->customer_id,
            'seller_id' => $request->seller_id,
        ];

        //create invoice
        $invoice = Invoice::create($response);

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    public function searchCustomer(Request $request)
    {
        $term = $request->input('term');
        $customers = Customer::where('name', 'like', '%'.$term.'%')->get();

        return response()->json($customers);
    }

    public function searchSeller(Request $request)
    {
        $term = $request->input('term');
        $sellers = Seller::where('name', 'like', '%'.$term.'%')->get();

        return response()->json($sellers);
    }

    public function searchProduct(Request $request)
    {
        $term = $request->input('term');
        $products = Product::where('name', 'like', '%'.$term.'%')->get();

        //map Product
        $products->map(function ($product) {
            $product->price = '$' . $product->unit_price;
            $product->unit_price = $product->unit_price;
            $product->code_number = $product->code_number;
            $product->name = $product->name;

        });

        return response()->json($products);
    }
}
