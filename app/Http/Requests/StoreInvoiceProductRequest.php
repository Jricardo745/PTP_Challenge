<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|numeric|exists:products,id',
            'quantity' => 'required|numeric|min:1|max:9999',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->repeatedProduct()) {
                $validator->errors()->add('product_id', '¡Este producto ya se encuentra registrado en la factura!');
            }
        });
    }

    public function repeatedProduct()
    {
        $availableProduct = DB::table('products as p')
            ->where('p.id', request('product_id'))
            ->whereNotExists(function ($query) {
                $query->select(DB::raw('ip.id'))
                    ->from('invoice_product as ip')
                    ->whereRaw('ip.product_id = p.id and ip.invoice_id ='.$this->invoice->id);
            })
            ->get()->toArray();
        if (isset($availableProduct[0])) {
            return false;
        } else {
            return true;
        }
    }
}
