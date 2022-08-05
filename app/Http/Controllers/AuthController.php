<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Авторизация пользователя
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }

    /**
     * Регистрация пользователя
     */
    public function register(Request $request){
        $validate = $request->validate([
            'surname' => 'required',
            'name' => 'required',
            'patronymic' => 'sometimes',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'organization' => 'required',
            'password' => 'required',
        ]);

        $validate['password'] = Hash::make($validate['password']);

        $user = User::query()->create($validate);
        $user->assignRole('partner');

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Регистрация прошла успешно',
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Выход
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Успешный выход из системы',
        ]);
    }

    /**
     * Профиль
     */
    public function profile()
    {
        return response()->json(Auth::user());
    }

    /**
     * Удаление токена и генерация нового
     */
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
