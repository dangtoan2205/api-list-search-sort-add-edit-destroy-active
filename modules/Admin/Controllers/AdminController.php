<?php

namespace Modules\Admin\Controllers;

use App\Exceptions\DDException;
use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Requests\SearchRequest;
use Modules\Admin\Requests\AuthRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\Admin;
use Modules\Admin\Requests\AdminRequest;
use Modules\Admin\Resources\AdminResource;
use Modules\Admin\Services\AdminService;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTAuth;


class AdminController extends Controller
{
    /**
     * @var \Modules\Admin\Services\AdminService $adminService AdminService.
     */
    protected $adminService;

    /**
     * Admin controller construct.
     *
     * @param AdminService  $adminService AdminService.
     */
    public function __construct(
        AdminService $adminService
    )
    {
        $this->adminService = $adminService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param SearchRequest $request
     * @return mixed
     */
    public function index(SearchRequest $request)
    {
        try {
            $data = $request->all();
            $admins = $this->adminService->list($data);
            $result = AdminResource::collection($admins);

            return response()->success($result);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminRequest $request Request.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function store(AdminRequest $request)
    {
        try {
            $isExistAccount = $this->adminService->isExistAccount();
            if ($isExistAccount) {
                return response()->failure('admin has only one account');
            }

            $isExistEmail = $this->adminService->isExistEmail($request->email);
            if ($isExistEmail) {
                return response()->failure(self::DUPLICATE_EMAIL);
            }
            $admin = $this->adminService->register($request);

            $result = new AdminResource($admin);

            return response()->success($result);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Admin $admin Admin.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function show(Admin $admin)
    {
        try {
            $result = new AdminResource($admin);

            return response()->success($result);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminRequest $request Request.
     * @param Admin        $admin   Admin.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function update(AdminRequest $request, Admin $admin)
    {
        try {
            $input = $request->only('name', 'email', 'username');
            $isCheckedEmail = $this->adminService->checkDuplicateEmail($input['email'], $admin->id);
            if ($isCheckedEmail) {
                return response()->failure(self::DUPLICATE_EMAIL);
            }
            if ($request->has('password')) {
                $input['password'] = bcrypt($request->password);
            }
            $admin->update($input);

            $result = new AdminResource($admin);

            return response()->success($result);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Admin $admin Admin.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function destroy(Admin $admin)
    {
        try {
            $result = $this->adminService->destroy($admin);
            return response()->success($result);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }
    }

    /**
     * Login admin.
     *
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        try {
            if (! $token = auth()->attempt([
                'email' => $request->email,
                'password' => $request->password,
                'is_active' => 1
            ])) {
                // Authentication was failure...
                return response()->failure('Email and/or password invalid.', self::LOGIN);
            }

            return response()->success(['access_token' => $token]);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }
    }

    /**
     * Logout admin.
     *
     * @return Response
     *
     */
    public function logout()
    {
        try {
            auth()->logout();

            return response()->success(null);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }
    }


    /**
     * Get auth admin info.
     *
     * @return AdminResource
     *
     */
    public function getProfile()
    {
        try {
            $admin = auth()->user();
            $result = new AdminResource($admin);

            return response()->success($result);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }
    }
}
