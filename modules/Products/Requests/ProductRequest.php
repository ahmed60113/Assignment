<?php

namespace Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ProductRequest extends FormRequest
{

    public function rules()
    {
        $name = Route::getCurrentRoute()->getName();
        switch ($name) {
            case 'create':
                return [
                    'title' => 'required|unique:products,title',
                    'price' => 'required|numeric',
                ];
                break;
            case 'edit' :
                return [
                    'title' => 'required|unique:products,title,'.$this->product->id,
                    'price' => 'required|numeric',
                ];
                break; 
        }
    }
}
