<?php

namespace Modules\Orders\Interfaces;

use App\Product as AppProduct;
use Modules\Orders\Models\Order;
use Modules\Orders\Requests\OrderRequest;
use Illuminate\Http\Request;
use Modules\Products\Models\product;


interface OrderInterface
{
    public function index();

    public function create(OrderRequest $request);

    public function paymentStatus(OrderRequest $request, Order $order);

    public function orderStatus(OrderRequest $request, Order $order);

    public function addToCart(OrderRequest $orde);

    public function removeFromCart(product $product);

    public function editCart(OrderRequest $order, product $product);

    public function showCart();

    public function showOrder(Order $order);

    public function editOrder(OrderRequest $request, Order $order);
}
