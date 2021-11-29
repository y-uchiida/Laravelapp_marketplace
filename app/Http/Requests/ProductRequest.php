<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        return ([
            'name' => 'required|string|max:50',
            'information' => 'required|string|max:1000',
            'price' => 'required|integer',
            'sort_order' => 'nullable|integer',
            'quantity' => 'required|integer|between:0,99',
            'shop_id' => 'required|exists:shops,id', /* shops テーブルのid カラムに、一致する値のレコードがあるか */
            'category' => 'required|exists:secondary_categories,id', /* secondary_categories テーブルのid カラムに、一致する値のレコードがあるか */
            'image1' => 'nullable|exists:images,id', /* null またはimages テーブルのid カラム */
            'image2' => 'nullable|exists:images,id', /* null またはimages テーブルのid カラム */
            'image3' => 'nullable|exists:images,id', /* null またはimages テーブルのid カラム */
            'image4' => 'nullable|exists:images,id', /* null またはimages テーブルのid カラム */
            'is_selling' => 'required',
        ]);
    }
}
