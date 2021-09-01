<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Customers\Models\customer;
use Modules\Products\Models\product;

/**
 *
 * @OA\Schema(
 * required={"name","title","price"},
 * @OA\Xml(name="products"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="title", type="string", description="product title",  example="T-shirt"),
 * @OA\Property(property="price", type="number", description="product price", example="50"),
 * )
 */
class Order extends Model
{
    use HasFactory;
    /**
     * 
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'total',
        'payment',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    protected $dates = [
        'updated_at', 'created_at'
    ];

    public function products()
    {
        return $this->belongsToMany(product::class, 'order_product');
    }

    public function customer()
    {
        return $this->belongsTo(customer::class);
    }
}
