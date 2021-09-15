<?php

namespace Modules\Orders\Controllers;

use Illuminate\Http\Request;
use Modules\BaseController;

class PaymentController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function checkout()
    {
        return view('checkout');
    }
}