<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Orders\Models\Order;

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
class product extends Model
{
    use HasFactory , SoftDeletes;
 /**
     * 
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'price'
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
        'deleted_at', 'updated_at', 'created_at'
    ];

     public function Orders()
     {
         return $this->belongsToMany(Order::class, 'order_product');
     }
}
