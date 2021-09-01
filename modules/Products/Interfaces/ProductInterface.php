<?php

namespace Modules\Products\Interfaces;

use Modules\Products\Models\product;
use Modules\Products\Requests\ProductRequest;

interface ProductInterface
{
    public function index ();

    public function delete (product $product);

    public function restore ($id);

    public function create(ProductRequest $request);

    public function show (product $product);

    public function edit (product $product,ProductRequest $request);
}