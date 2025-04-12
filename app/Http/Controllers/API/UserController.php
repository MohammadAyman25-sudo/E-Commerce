<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function show(string $id) {
        $user = User::find($id);
        if ($user) {
            return response()->json([
                "status" => 200,
                "message" => "User Fetched",
                "data" => $user,
            ]);
        }
        return response()->json([
            "status" => 404,
            "message" => "User Not Found",
            "data" => null,
        ], 404);
    }


    public function update (Request $request, string $id) {
        $validator = validator($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation Error',
                'data' => $validator->errors()->all(),
            ], 400);
        }
        if (auth()->id() != $id) {
            return response()->json([
                'status' => 403,
                'message' => 'Unauthorized Action.',
                'data' => null,
            ], 403);
        }
        if ($request->password) {
            auth()->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        }
        else {
            auth()->user()->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'User Updated',
            'data' => auth()->user(),
        ]);
    }

    public function destroy(string $id) {
        if (auth()->id() != $id) {
            return response()->json([
                'status' => 403,
                'message' => 'Unauthorized Action',
                'data' => null,
            ], 403);
        }
        auth()->user()->tokens()->delete();
        auth()->user()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User Deleted',
            'data' => null,
        ]);
    }
}
