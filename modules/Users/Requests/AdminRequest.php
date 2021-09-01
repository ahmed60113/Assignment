<?php

namespace Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class AdminRequest extends FormRequest
{

    public function rules()
    {
        $name = Route::getCurrentRoute()->getName();
        switch ($name) {
            case 'login' :
                return [
                    'email' => 'required|email',
                    'password' => 'required|min:6'
                ];
                break;
            case 'create':
                return [
                    'name' => 'required|unique:users,name',
                    'email' => 'required|unique:users,email',
                    'password' => 'required|min:6',
                ];
                break;
            case 'updateProfile' :
                return [
                    'name' => 'required|unique:users,name,'.Auth::guard('admin')->user()->id,
                    'email' => 'required|unique:users,email,'.Auth::guard('admin')->user()->id,
                    'password' => 'required|min:6',
                ];
                break;

            default :
            return
            [
                'name' => 'required|unique:users,name,'.$this->admin->id,
                'email' => 'required|unique:users,email,'.$this->admin->id,
                'password' => 'required|min:6',
            ];
            break; 
        }
    }
}
