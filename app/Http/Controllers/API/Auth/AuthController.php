<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
class AuthController extends Controller
{
    protected $authservice;
   public function __construct(AuthService $AuthService){
     $this->middleware('auth:api')->except(['register','login']);
     $this->authservice = $AuthService;
   }
    public function register(RegisterRequest $registerRequest)
    {
       return $this->authservice->register($registerRequest->validated());
    }
    public function login(LoginRequest $loginRequest)
    {
       return $this->authservice->login($loginRequest->validated());
    }
    public function logout()
    {
        return $this->authservice->logout();
    }

}
