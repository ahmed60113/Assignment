<?php

namespace Modules\Products\Controllers;

use App\Http\Traits\ApiResponseTrait;
use Modules\BaseController;
use Modules\Users\Models\User;
use Modules\Products\Interfaces\ProductInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Products\Models\product;
use Modules\Products\Requests\ProductRequest;
use Modules\Products\Resources\ProductResource;

class ProductApiController extends BaseController implements ProductInterface
{
    use ApiResponseTrait;

    /**
     * @OA\Post(
     * path="/api/product/create",
     * summary="add new product",
     * description="add new product",
     * operationId="authLogin",
     * tags={"products"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Fill product Data",
     *    @OA\JsonContent(
     *       required={"title", "price"},
     *       @OA\Property(property="title", type="string", example="T-shirt"),
     *       @OA\Property(property="price", type="number",  example="50"),
     *    ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="admin", type="string", example="admin data"),
     *     )
     *  ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     *
     */
    public function create(ProductRequest $request)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('create', product::class);

        $product = product::create($request->all());
        return new ProductResource($product);
    }

    /**
     * @OA\Patch(
     * path="/api/product/edit/{id}",
     * summary="edit product",
     * description="edit product",
     * operationId="authLogin",
     * tags={"products"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Fill product Data",
     *    @OA\JsonContent(
     *       required={"title", "price"},
     *       @OA\Property(property="title", type="string", example="T-shirt"),
     *       @OA\Property(property="price", type="number", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="product", type="string", example="product data"),
     *     )
     *  )
     * )
     *
     */
    public function edit(product $product, ProductRequest $request)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('edit', product::class);

        $product->update($request->all());
        return new ProductResource($product);
    }

     /**
     * @OA\Delete(
     * path="/api/product/delete/{id}",
     * summary="softdelete products",
     * description="softdelete products ",
     * tags={"products"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="admin deleted successfully"),
     *     )
     *  )
     * )
     *
     */
    public function delete(product $product)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('delete', product::class);

        $delete = $product->delete();
        return $this->ApiResponse(200, 'Product deleted', null, null);
    }

    /**
     * @OA\Patch (
     * path="/api/product/restore/{id}",
     * summary="restore softdelete products",
     * description="restore softdelete products ",
     * tags={"products"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="product restored successfully"),
     *     )
     *  )
     * )
     *
     */
    public function restore($id)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('restore', product::class);

        $product = product::withTrashed()->find($id);
        $product->restore();
        return new ProductResource($product);

    }

        /**
     * @OA\patch (
     * path="/api/product/show/{id}",
     * summary=" show product",
     * description="show product ",
     * tags={"products"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="admin", type="string", example="product data"),
     *     )
     *  )
     * )
     *
     */
    public function show(product $product)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('show', product::class);

        return new ProductResource($product);
    }

     /**
     * @OA\Post(
     * path="/api/product/index",
     * summary="get all products",
     * tags={"products"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="all products"),
     *     )
     *  )
     * )
     *
     */
    public function index()
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('index', product::class);

        return ProductResource::collection(product::all());
    }
}
