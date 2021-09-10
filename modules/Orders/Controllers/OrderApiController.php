<?php

namespace Modules\Orders\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Product as AppProduct;
use Modules\BaseController;
use Modules\Orders\Interfaces\OrderInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Modules\Orders\Models\Order;
use Modules\Orders\Requests\OrderRequest;
use Modules\Products\Models\product;
use Modules\Products\Resources\ProductResource;
use function GuzzleHttp\json_decode;

class OrderApiController extends BaseController implements OrderInterface
{
  use ApiResponseTrait;

  public function index()
  {
  }

  public function create(OrderRequest $request)
  {
  }

  public function paymentStatus(OrderRequest $request, Order $order)
  {
  }

  public function orderStatus(OrderRequest $request, Order $order)
  {
  }

  /**
   * @OA\Post(
   * path="/api/cart/addToCart",
   * summary="add new item",
   * description="add new customer",
   * operationId="authLogin",
   * tags={"Orders"},
   * @OA\RequestBody(
   *    required=true,
   *    description="Fill item Data",
   *    @OA\JsonContent(
   *       required={"prodId", "quantity"},
   *       @OA\Property(property="prodId", type="number", example="1"),
   *       @OA\Property(property="quantity", type="number", format="email", example="1"),
   *    ),
   * ),
   * @OA\Response(
   *     response=200,
   *     description="Success",
   *     @OA\JsonContent(
   *        @OA\Property(property="order", type="string", example="item data"),
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
  public function addToCart(OrderRequest $order)
  {
    $userId = 2;

    $cart = Redis::get('cart' . $userId);
    $product = product::find($order->prodId);

    // if cart is empty then this the first product
    if (!$cart) {
      $cart = [
        $product->id => [
          "name" => $product->title,
          "quantity" => $order->quantity,
          "price" => ($order->quantity * $product->price),
        ]
      ];

      $encode = json_encode($cart);
      Redis::set('cart' . $userId, $encode);
      return $this->ApiResponse('200', 'product added to cart');
    }

    //if the item exists it will change its data by id , or it will add it if not exist
    $decode[$order->prodId] = [
      "name" => $product->title,
      "quantity" => $order->quantity,
      "price" => ($order->quantity * $product->price),
    ];

    $cart = json_encode($decode);
    Redis::set('cart' . $userId, $cart);
    return $this->ApiResponse('200', 'product added to cart');
  }

  /**
   * @OA\Post(
   * path="/api/cart/removeCart",
   * summary="add new item",
   * description="add new customer",
   * operationId="authLogin",
   * tags={"Orders"},
   * @OA\Response(
   *     response=200,
   *     description="Success",
   *     @OA\JsonContent(
   *        @OA\Property(property="order", type="string", example="item data"),
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
  public function removeCart()
  {
    $userId = 2;
    Redis::del('cart' . $userId);
    return $this->ApiResponse('200', 'Cart removed');
  }

  /**
   * @OA\Delete(
   * path="/api/cart/removeFromCart/{id}",
   * summary="add new item",
   * description="add new customer",
   * operationId="authLogin",
   * tags={"Orders"},
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
   *        @OA\Property(property="order", type="string", example="item data"),
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
  public function removeFromCart(product $product)
  {
    $userId = 2;
    $id = $product->id;

    $cart = Redis::get('cart' . $userId);
    $decodedCart = json_decode($cart, true);

    unset($decodedCart[$id]);

    $encode = json_encode($decodedCart);
    Redis::set('cart' . $userId, $encode);

    return $this->ApiResponse(200, 'The item removed from cart');
  }

  /**
   * @OA\patch(
   * path="/api/cart/edit/{id}",
   * summary="edit cart",
   * description="edit cart",
   * operationId="authLogin",
   * tags={"Orders"},
   *      * @OA\Parameter(
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
   *    description="Fill cart Data",
   *    @OA\JsonContent(
   *       required={"quantity"},
   *       @OA\Property(property="quantity", type="number", example="1"),
   *    ),
   * ),
   * @OA\Response(
   *     response=200,
   *     description="Success",
   *     @OA\JsonContent(
   *        @OA\Property(property="admin", type="string", example="admin data"),
   *     )
   *  )
   * )
   *
   */
  public function editCart(OrderRequest $order, product $product)
  {
    $userId = 2;
    $cart = Redis::get('cart' .$userId);

    $id = $product->id;
    $decodedCart = json_decode($cart , true);

    //if order quantity is zero remove this cart
    if ($order->quantity == 0) {
      return $this->removeFromCart($product);
    }
//else it will update this item
    $decodedCart[$id] = [
      "name" => $product->title,
      "quantity" => $order->quantity,
      "price" => ($order->quantity * $product->price),  
    ];    

    $cart = json_encode($decodedCart);
    Redis::set('cart' . $userId, $cart);
    return $this->ApiResponse('200', 'product updated to cart');

  }

  /**
   * @OA\Post(
   * path="/api/cart/showCart",
   * summary="add new item",
   * description="add new customer",
   * operationId="authLogin",
   * tags={"Orders"},
   * @OA\Response(
   *     response=200,
   *     description="Success",
   *     @OA\JsonContent(
   *        @OA\Property(property="order", type="string", example="item data"),
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
  public function showCart()
  {
    $userId = 2;
    $cart = Redis::get('cart' . $userId);
    return $this->ApiResponse(200, 'Cart data', null, $cart);
  }

  public function showOrder(Order $order)
  {
  }


  public function editOrder(OrderRequest $request, Order $order)
  {
  }
}
