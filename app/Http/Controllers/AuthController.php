<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::find(Auth::user()->id);
            $token = $user->createToken('authToken')->plainTextToken;

            return $this->sendResponse(['token' => $token, 'user' => $user]);
        }

        return $this->sendError('Unauthorized.', ['error' => 'Unauthorized']);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi harus minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        Auth::loginUsingId($user->id);

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->sendResponse(['token' => $token, 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->tokens()->delete();

        return $this->sendResponse([], 'Logout successfully!');
    }
}
