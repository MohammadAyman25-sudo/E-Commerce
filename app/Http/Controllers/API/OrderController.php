<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::paginate(15);
        return response()->json([
            "status" => 200,
            "message" => "Orders Fetched",
            "data" => $orders
        ]);
    }
    public function show(string $id) {
        $order = Order::find($id);
        if ($order) {
            return response()->json([
                'status' => 200,
                'message' => 'Order Fetched',
                'data' => $order,
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Order Not Found',
            'data' => null,
        ], 404);
    }
    public function store(Request $request) {
        $validator = validator($request->all(), [
            'total_amount' => 'required|numeric|decimal:0, 2',
            'status' => 'nullable',
            'shipping_address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'invalid inputs',
                'data' => $validator->errors()->all(),
            ], 400);
        }
        $order = auth()->user()->orders()->create($validator->validated());
        return response()->json([
            'status' => 201,
            'message' => 'Order Created',
            'data' => $order,
        ], 201);
    }
    public function update(Request $request, string $id) {
        $validator = validator($request->all(), [
            'total_amount' => 'required|numeric|decimal:0, 2',
            'status' => 'nullable',
            'shipping_address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'invalid inputs',
                'data' => $validator->errors()->all(),
            ], 400);
        }
        $order = Order::find($id);
        if ($order) {
            $order->update($validator->validated());
            return response()->json([
                'status' => 200,
                'message' => 'Order Updated',
                'data' => $order,
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Order Not Found',
            'data' => null,
        ], 404);
    }
    public function destroy(string $id) {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Order Deleted',
                'data' => null,
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Order Not Found',
            'data' => null,
        ], 404);
    }
}
