<?php

namespace Modules\Users\Controllers;

use App\Http\Traits\ApiResponseTrait;
use Modules\Users\Resources\AdminResource;
use Modules\BaseController;
use Modules\Users\Models\User;
use Modules\Users\Requests\AdminRequest;
use Modules\Users\Interfaces\AdminInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AdminApiController extends BaseController implements AdminInterface
{
    use ApiResponseTrait;

    /**
     * @OA\Post(
     * path="/api/admin/create",
     * summary="add new Admin",
     * description="add new admin",
     * operationId="authLogin",
     * tags={"admins"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Fill admin Data",
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
    public function create(AdminRequest $request)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('create', User::class);

        $admin = User::create($request->all());
        $role = Role::where('name', 'admin')->first();
        $admin->assignRole($role);

        return new AdminResource($admin);
    }

    /**
     * @OA\Post(
     * path="/api/admin/login",
     * summary="admin Sign in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"admins"},
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
    public function Login(AdminRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $token = Auth::guard('admin')->attempt($credentials);
        if (!$token) {
            return $this->apiResponse(400, 'invalid credentials');
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'name' => Auth::guard('admin')->user()->name,
            'expires_in' => auth()->factory()->getTTL() * 3600
        ];
        return $this->apiResponse(200, 'Login success', NULL, $data);
    }

    /**
     * @OA\Post(
     * path="/api/admin/profile",
     * summary="admin profile",
     * description="profile ",
     * operationId="authLogin",
     * tags={"admins"},
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
        $logged = Auth::guard('admin')->user();
        return new AdminResource($logged);
    }

    /**
     * @OA\Post(
     * path="/api/admin/updateProfile",
     * summary="edit admin profile",
     * description="profile ",
     * operationId="authLogin",
     * tags={"admins"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Fill admin Data",
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
    public function updateProfile(AdminRequest $request)
    {
        $logged = Auth::guard('admin')->user();
        $logged->update($request->all());
        return new AdminResource($logged);
    }

    /**
     * @OA\Post(
     * path="/api/admin/logout",
     * summary="admin logout",
     * description="logout ",
     * operationId="authLogin",
     * tags={"admins"},
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
        $logged = Auth::guard('admin')->logout();
        return $this->ApiResponse(200, 'Admin logged out', null, null);
    }

    /**
     * @OA\Post(
     * path="/api/admin/index",
     * summary="get all admins",
     * description="logout ",
     * operationId="authLogin",
     * tags={"admins"},
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
        $logged = Auth::guard('admin')->user();
        $this->authorize('index', User::class);
        return AdminResource::collection(User::all());
    }

    /**
     * @OA\Delete(
     * path="/api/admin/delete/{id}",
     * summary="softdelete admins",
     * description="softdelete admins ",
     * operationId="authLogin",
     * tags={"admins"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="doctor id",
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
    public function delete(User $admin)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('delete', User::class);

        $delete = $admin->delete();
        return $this->ApiResponse(200, 'Admin deleted', null, null);
    }

    /**
     * @OA\Patch (
     * path="/api/admin/restore/{id}",
     * summary="restore softdelete admins",
     * description="restoresoftdelete admins ",
     * operationId="authLogin",
     * tags={"admins"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="admin id",
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
        $logged = Auth::guard('admin')->user();
        $this->authorize('restore', User::class);
        $admin = User::withTrashed()->find($id);
        $admin->restore();
        return new AdminResource($admin);
    }

    /**
     * @OA\patch (
     * path="/api/admin/show/{id}",
     * summary=" show admins",
     * description="show admins ",
     * operationId="authLogin",
     * tags={"admins"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *          name="id",
     *          description="admin id",
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
    public function show(User $admin)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('show', User::class);
        return new AdminResource($admin);
    }

    /**
     * @OA\patch(
     * path="/api/admin/edit/{id}",
     * summary="edit admin",
     * description="edit admin",
     * operationId="authLogin",
     * tags={"admins"},
     * security={ {"sanctum": {} }},
     *      * @OA\Parameter(
     *          name="id",
     *          description="admin id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Fill admin Data",
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
    public function edit(AdminRequest $request, User $admin)
    {
        $logged = Auth::guard('admin')->user();
        $this->authorize('edit', User::class);
        $admin->update($request->all());
        return new AdminResource($admin);
    }
}
