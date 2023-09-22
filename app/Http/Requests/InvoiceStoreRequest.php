<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'productsArray' => 'required|array|min:1',
            'customer_id' => 'required',
            'seller_id' => 'required',
            'shipment_id' => 'required',
            'discount' => 'numeric|min:1'
        ];


    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El cliente es requerido.',
            'seller_id.required' => 'El vendedor es requerido.',
            'shipment_id.required' => 'El tipo de envio es requerido.',
            'productsArray.required' => 'Los productos son requeridos.',
            'productsArray.array' => 'El campo Productos debe ser un array.',
            'productsArray.min' => 'El campo productsArray debe tener al menos un elemento.',
            'discount.numeric' => 'El campo descuento debe ser un numero.',
            'discount.min' => 'El campo descuento debe ser mayor o igual a 1.',
        ];
    }
}
