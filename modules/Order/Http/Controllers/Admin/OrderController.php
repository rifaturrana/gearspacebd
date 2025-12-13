<?php

namespace Modules\Order\Http\Controllers\Admin;

use Modules\Order\Entities\Order;
use Modules\Admin\Traits\HasCrudActions;
use Illuminate\Http\Request;
use Modules\Support\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController
{
    use HasCrudActions;

    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['products', 'coupon', 'taxes'];

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'order::orders.order';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'order::admin.orders';

        public function removeProduct(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'product_id' => 'required|exists:order_products,id',
    ]);

    $order = Order::findOrFail($request->order_id);
    $orderProduct = $order->products()->findOrFail($request->product_id);

    // Check if order status allows modification
    if (in_array($order->status, [Order::COMPLETED, Order::CANCELED, Order::REFUNDED])) {
        return response()->json([
            'success' => false,
            'message' => trans('order::messages.cannot_modify_completed_order')
        ], 422);
    }

    // Get the product to restore stock
    $product = $orderProduct->product;
    $quantity = $orderProduct->qty;

    // Start transaction
    \DB::beginTransaction();
    try {
        // Delete the order product
        $orderProduct->delete();

        // Restore stock
        if ($product) {
            $product->increment('qty', $quantity);
        }

        // Recalculate order totals
        $order->recalculateTotals();

        \DB::commit();

       return response()->json([
    'success' => true,
    'data' => [
        'order' => [
            'sub_total' => $order->sub_total->format(),
            'shipping_cost' => $order->shipping_cost->format(),
            'tax' => $order->tax ? $order->tax->format() : Money::inDefaultCurrency(0)->format(),
            'discount' => $order->discount ? $order->discount->format() : Money::inDefaultCurrency(0)->format(),
            'total' => $order->total->format(),
        ],
        'remaining_products' => $order->products()->count(),
        'order_status' => $order->status, // Add this line
        'order_status_label' => $order->status() // Assuming you have a status() method that returns the translated status
    ]
]);

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Error removing product from order: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => trans('order::messages.error_removing_product')
        ], 500);
    }
}
}
