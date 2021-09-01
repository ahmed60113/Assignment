<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

/**
 *
 * @OA\Schema(
 * required={"name","email","password"},
 * @OA\Xml(name="Admin"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="name", type="string", description="Admin name",  example="admin name"),
 * @OA\Property(property="email", type="string", format="email", description="User unique email address", example="admin@gmail.com"),
 * )
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, HasRoles, Notifiable,SoftDeletes;

    protected $guard_name = 'admin';

    /**
     * 
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    protected $dates = [
        'deleted_at', 'updated_at', 'created_at'
    ];

    protected $appends = [
        'permission'
    ];

    protected $with =[
        'permissions',
         'roles'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getPermissionAttribute()
    {
        return $this->getAllPermissions();
    }
}
