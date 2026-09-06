<?php

namespace App\Services;

use Exception;
use App\Enums\Ask;
use App\Models\User;
use App\Enums\Role as EnumRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Enums\Status;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\UserChangePasswordRequest;


class CustomerService
{
    public object $user;
    public array $phoneFilter = ['phone'];
    public array $roleFilter = ['role_id'];
    public array $userFilter = ['name', 'email', 'username', 'branch_id', 'status', 'phone'];
    public array $blockRoles = [EnumRole::ADMIN];


    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return User::with('media', 'addresses' , 'messages')->role(EnumRole::CUSTOMER)->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->userFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(CustomerRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->user = User::create([
                    'name'              => $request->name,
                    'email'             => $request->email,
                    'phone'             => $request->phone,
                    'username'          => $this->username($request->email, $request->phone, $request->name),
                    'password'          => bcrypt($request->password ?: Str::random(12)),
                    'branch_id'         => 0,
                    'email_verified_at' => now(),
                    'status'            => !blank($request->status) ? (int)$request->status : Status::ACTIVE,
                    'country_code'      => $request->country_code,
                    'is_guest'          => Ask::NO,
                ]);
                $this->user->assignRole(EnumRole::CUSTOMER);
            });
            return $this->user;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(CustomerRequest $request, User $customer)
    {
        try {
            if (!in_array(EnumRole::CUSTOMER, $this->blockRoles)) {
                DB::transaction(function () use ($customer, $request) {
                    $this->user               = $customer;
                    $this->user->name         = $request->name;
                    $this->user->email        = $request->email;
                    $this->user->phone        = $request->phone;
                    $this->user->status       = !blank($request->status) ? (int)$request->status : $customer->status;
                    $this->user->country_code = $request->country_code;
                    if ($request->password) {
                        $this->user->password = Hash::make($request->password);
                    }
                    $this->user->save();
                });
                return $this->user;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(User $customer): User
    {
        try {
            if (!in_array(EnumRole::CUSTOMER, $this->blockRoles)) {
                return $customer;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(User $customer)
    {
        try {
            if (!in_array(EnumRole::CUSTOMER, $this->blockRoles) && $customer->id != 2) {
                if ($customer->hasRole(EnumRole::CUSTOMER)) {
                    DB::transaction(function () use ($customer) {
                        $customer->addresses()->delete();
                        $customer->delete();
                    });
                } else {
                    throw new Exception(trans('all.message.permission_denied'), 422);
                }
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    private function username($email, $phone = null, $name = null): string
    {
        do {
            if (!empty($email)) {
                $emails = explode('@', $email);
                $uname = $emails[0] . mt_rand(100, 999);
            } elseif (!empty($phone)) {
                $cleanPhone = preg_replace('/\D/', '', $phone);
                $uname = 'c_' . substr($cleanPhone, -6) . mt_rand(100, 999);
            } else {
                $uname = Str::slug($name ?? 'cust') . '_' . mt_rand(1000, 9999);
            }
        } while (User::where('username', $uname)->exists());

        return $uname;
    }

    /**
     * @throws Exception
     */
    public function changePassword(UserChangePasswordRequest $request, User $customer): User
    {
        try {
            if (!in_array(EnumRole::CUSTOMER, $this->blockRoles)) {
                $customer->password = Hash::make($request->password);
                $customer->save();
                return $customer;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeImage(ChangeImageRequest $request, User $customer): User
    {
        try {
            if (!in_array(EnumRole::CUSTOMER, $this->blockRoles)) {
                if ($request->image) {
                    $customer->clearMediaCollection('profile');
                    $customer->addMediaFromRequest('image')->toMediaCollection('profile');
                }
                return $customer;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
