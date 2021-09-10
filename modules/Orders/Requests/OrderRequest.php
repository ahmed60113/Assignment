<?php

namespace Modules\Orders\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class OrderRequest extends FormRequest
{

    public function rules()
    {
        $name = Route::getCurrentRoute()->getName();
        switch ($name) {
            case 'addToCart' :
                return [
                    'prodId' =>'required | exists:products,ID',
                    'quantity' => 'required | min:1',
                ];
                break; 

                case 'editCart' :
                    return [
                        'quantity' => 'required | min:0',
                    ];
                    break;  
            }
    }
}