<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(): \Illuminate\Http\JsonResponse
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('api-token')->plainTextToken;

            return response()->json(['success' => $success], $this->httpStatusOk);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('api-token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json(['success' => $success], $this->httpStatusOk);
    }

    public function list(): \Illuminate\Http\JsonResponse
    {
        $users = User::all();

        return response()->json(['success' => $users], $this->httpStatusOk);
    }

    public function get_user(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        return response()->json(['success' => $user], $this->httpStatusOk);
    }

    public function get_user_details_by_id($id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            return response()->json(['success' => $user], $this->httpStatusOk);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function update_user(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'notifications' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        $user->update($request->only(['name', 'email', 'notifications']));

        return response()->json(['success' => $user], $this->httpStatusOk);
    }
}
