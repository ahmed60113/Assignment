<?php

namespace Modules\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class CustomerRequest extends FormRequest
{

    public function rules()
    {
        $name = Route::getCurrentRoute()->getName();
        switch ($name) {
            case 'mailLogin' :
                return [
                    'email' => 'required|email',
                    'password' => 'required|min:6'
                ];
                break;
            case 'registerByMail':
                return [
                    'name' => 'required|unique:customers,name',
                    'email' => 'required|unique:customers,email',
                    'password' => 'required|min:6',
                ];
                break;
            case 'updateProfile' :
                return [
                    'name' => 'required|unique:customers,name,'.Auth::guard('customer')->user()->id,
                    'email' => 'required|unique:customers,email,'.Auth::guard('customer')->user()->id,
                    'password' => 'required|min:6',
                ];
                break;

            default :
            return
            [
                'name' => 'required|unique:customers,name,'.$this->customer->id,
                'email' => 'required|unique:customers,email,'.$this->customer->id,
                'password' => 'required|min:6',
            ];
            break; 
        }
    }
}
