<?php

namespace Modules\Customers\Controllers;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Customers\Models\customer;
use Modules\Customers\Requests\CustomerRequest;
use Modules\BaseController;
use Modules\Customers\Resources\CustomerResource;
use Spatie\Permission\Contracts\Role;

class CustomerApiController extends BaseController
{
    use ApiResponseTrait;
  
    /**
     * @OA\Post(
     * path="/api/customer/index",
     * summary="get all customers",
     * description="logout ",
     * operationId="authLogin",
     * tags={"customers"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="all admins"),
     *     )
     *  )
     * )
     *
     */
    public function index()
    {
         $logged = Auth::guard('customer')->user();
         $this->authorize('index', customer::class);
        
        return customer::all();
        return CustomerResource::collection(Customer::all());
    }

    /**
     * @OA\Delete(
     * path="/api/customer/delete/{id}",
     * summary="softdelete customer",
     * description="softdelete customer ",
     * operationId="authLogin",
     * tags={"customers"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="customer id",
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
    public function delete(Customer $customer)
    {
        $logged = Auth::guard('customer')->user();
        $this->authorize('delete', customer::class);

        $delete = $customer->delete();
        return $this->ApiResponse(200, 'Customer deleted', null, null);
    }

    /**
     * @OA\Patch (
     * path="/api/customer/restore/{id}",
     * summary="restore softdelete customer",
     * description="restoresoftdelete customer ",
     * operationId="authLogin",
     * tags={"customers"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="customer id",
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
     *        @OA\Property(property="message", type="string", example="admin restored successfully"),
     *     )
     *  )
     * )
     *
     */
    public function restore($id)
    {
        $logged = Auth::guard('customer')->user();
        $this->authorize('restore', customer::class);
        $customer = Customer::withTrashed()->find($id);
        $customer->restore();
        return new CustomerResource($customer);
    }

    /**
     * @OA\Post(
     * path="/api/customer/mailLogin",
     * summary="customer Sign in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"customers"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass admin credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="Admin@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
     *       @OA\Property(property="persistent", type="boolean", example="true"),
     *    ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="token", type="string", example="1|***************"),
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
     * )
     *
     */
    public function loginByMail(CustomerRequest $request)
    {
        Config::set('jwt.user','Modules\Customers\Models\customer' ); 
		Config::set('auth.providers.users.model', \Modules\Customers\Models\customer::class);
       //return 'request is '.$request;
        $credentials = $request->only(['email', 'password']);
        $token = Auth::guard()->attempt($credentials);
        if (!$token) {
            return $this->apiResponse(400, 'invalid credentials');
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'name' => Auth::guard('customer')->user()->name,
            'expires_in' => auth()->factory()->getTTL() * 3600
        ];
        return $this->apiResponse(200, 'Login success', NULL, $data);
    }


    /**
     * @OA\Post(
     * path="/api/customer/mailRegister",
     * summary="add new customer",
     * description="add new customer",
     * operationId="authLogin",
     * tags={"customers"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Fill customer Data",
     *    @OA\JsonContent(
     *       required={"name", "email","password"},
     *       @OA\Property(property="name", type="string", example="Admin"),
     *       @OA\Property(property="email", type="string", format="email", example="Admin@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="123456")
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
    public function registerByMail(CustomerRequest $request)
    {
        $role = Role::findByName('customer', 'customer')->first();

        $customer = customer::create($request->all());
        $customer->assignRole($role);

        return new CustomerResource($customer);
    }


    /**
     * @OA\Post(
     * path="/api/customer/profile",
     * summary="customer profile",
     * description="profile ",
     * operationId="authLogin",
     * tags={"customers"},
     * security={ {"sanctum": {} }},
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
    public function profile(Request $request)
    {
        $logged = Auth::guard('customer')->user();
        return new CustomerResource($logged);
    }

    /**
     * @OA\Post(
     * path="/api/customer/updateProfile",
     * summary="edit customer profile",
     * description="profile ",
     * operationId="authLogin",
     * tags={"customers"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Fill customer Data",
     *    @OA\JsonContent(
     *       required={"name", "email","password"},
     *       @OA\Property(property="name", type="string", example="Admin"),
     *       @OA\Property(property="email", type="string", format="email", example="Admin@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
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
    public function updateProfile(Request $request)
    {
        $logged = Auth::guard('customer')->user();
        $logged->update($request->all());
        return new CustomerResource($logged);
    }

    /**
     * @OA\Post(
     * path="/api/customer/create",
     * summary="add new customer",
     * description="add new customer",
     * operationId="authLogin",
     * tags={"customers"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Fill customer Data",
     *    @OA\JsonContent(
     *       required={"name", "email","password"},
     *       @OA\Property(property="name", type="string", example="Admin"),
     *       @OA\Property(property="email", type="string", format="email", example="Admin@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="123456")
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
    public function create(CustomerRequest $request)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('create', Customer::class);

        $customer = customer::create($request->all());
        $role = Role::where('name', 'customer')->first();
        $customer->assignRole($role);

        return new CustomerResource($customer);
    }

    /**
     * @OA\Post(
     * path="/api/customer/logout",
     * summary="customer logout",
     * description="logout ",
     * operationId="authLogin",
     * tags={"customers"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="you logeed out successfully"),
     *     )
     *  )
     * )
     *
     */
    public function logout(Request $request)
    {
        $logged = Auth::guard('customer')->logout();
        return $this->ApiResponse(200, 'customer logged out', null, null);
    }

    /**
     * @OA\patch (
     * path="/api/cusomer/show/{id}",
     * summary=" show customers",
     * description="show customers ",
     * operationId="authLogin",
     * tags={"customers"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="customer id",
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
     *        @OA\Property(property="admin", type="string", example="admin data"),
     *     )
     *  )
     * )
     *
     */
    public function show(Customer $customer)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('show', Customer::class);
        return new CustomerResource($customer);
    }

    /**
     * @OA\patch(
     * path="/api/customer/edit/{id}",
     * summary="edit customer",
     * description="edit customer",
     * operationId="authLogin",
     * tags={"customers"},
     * security={ {"sanctum": {} }},
     *      * @OA\Parameter(
     *          name="id",
     *          description="customer id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Fill customer Data",
     *    @OA\JsonContent(
     *       required={"name", "email","password"},
     *       @OA\Property(property="name", type="string", example="customer"),
     *       @OA\Property(property="email", type="string", format="email", example="customer@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
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
    public function edit(CustomerRequest $request, Customer $customer)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('edit', customer::class);
        $customer->update($request->all());
        return new CustomerResource($customer);
    }
}
