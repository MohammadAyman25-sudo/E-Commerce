<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderItemController extends Controller
{
    public function index() {
        $orderItems = OrderItem::paginate(15);
        return response()->json([
            'status' => 200,
            'message' => 'Order Items Fetched',
            'data' => $orderItems,
        ]);
    }
    public function show (string $id) {
        $orderItem = OrderItem::find($id);
        if ($orderItem) {
            return response()->json([
                'status' => 200,
                'message' => 'Order Item Fetched',
                'data' => $orderItem,
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Order Item Not Found',
            'data' => null,
        ], 404);
    }

    public function store(Request $request, string $orderId, string $productId) {
        $validator = validator($request->all(), [
            'quantity' => 'required|numeric|integer',
            'price_at_purchase' => 'required|numeric|decimal:0, 2',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid Inputs',
                'data' => $validator->errors()->all(),
            ], 400);
        }
        $order = Order::find($orderId);
        if(! $order) {
            return response()->json([
                'status' => 404,
                'message' => 'Order Not Found',
                'data' => null,
            ], 404);
        }
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product Not Found',
                'data' => null,
            ], 404);
        } 
        // $order_item = OrderItem::create([...$validator->validated(), 'product_id'=>$product_id, 'order_id'=> $order_id]);
        $orderItem = new OrderItem($validator->validated());
        $orderItem->products()->associate($product);
        $orderItem->orders()->associate($order);
        $orderItem->save();
        return response()->json([
            'status' => 201,
            'message' => 'Order Item Created',
            'data' => $orderItem,
        ], 201);
    }

    public function update(Request $request, string $id) {
        $validator = validator($request->all(), [
            'quantity' => 'required|numeric|integer',
            'price_at_purchase' => 'required|numeric|decimal:0, 2',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid Inputs',
                'data' => $validator->errors()->all(),
            ], 400);
        }
        $orderItem = OrderItem::find($id);
        if (! $orderItem) {
            return response()->json([
                'status' => 404,
                'message' => 'Order Item Not Found',
                'data' => null,
            ], 404);
        }
        $orderItem->update($validator->validated());
        return response()->json([
            'status' => 200,
            'message' => 'Order Item Updated',
            'data' => $orderItem,
        ]);
    }

    public function destroy(string $id) {
        $orderItem = OrderItem::find($id);
        if (! $orderItem) {
            return response()->json([
                'status' => 404,
                'message' => 'Order Item Not Found',
                'data' => null,
            ], 404);
        }
        $orderItem->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Order Item Deleted',
            'data' => null,
        ]);
    }
}
