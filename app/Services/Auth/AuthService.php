<?php
namespace App\Services\Auth;

use App\Http\Resources\User\UserResouce;
use App\Models\User;
use App\Enums\User\UserStatus;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\User\UserallResource;

class AuthService
{
 public function register(array $data)
    {
            try {
                User::create([
                    'name'=> $data['name'],
                    'email'=> $data['email'],
                    'password'=> Hash::make($data['password']),
                ]);
                return response()->json([
                    'message' => 'user has been created!'
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => $th->getMessage()
                ], 422);
            }
    }

 public function login(array $data)
    {
        try {
        $usertoken  =Auth::attempt(['username'=>$data['username'],'password'=>$data['password']]);
        if(!$usertoken){
            return response()->json([
                'message' => 'يوجد خطأ فى الاسم او الرقم السرى!',
            ],401);
        }
        if( $usertoken && Auth::user()->status === UserStatus::INACTIVE->value)
        {
            return response()->json([
                'message' => 'هذا الحساب غير مفعل!',
            ], 401);
        }
        $user=Auth()->user();
        $userRoles=$user->getRoleNames();
        $role=Role::findByName($userRoles[0]);
        return response()->json([
            "token"=>$usertoken,
            "profile"=>new UserResouce($user),
            "roles"=>new RoleResource($role),
        ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

    }

 public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'you have logged out']);
    }
}
 ?>
