<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Order\IndexRequest;
use App\Http\Requests\Api\Order\StoreRequest;
use App\Http\Requests\Api\Order\UpdateRequest;
use App\Http\Requests\Api\Order\DeleteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends ApiBaseController
{
    protected $model = Order::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    // public function modifyIndex($query) {
    //     $request = request();

    //     return $query->with('client', 'user');
    // }

    public function stored($order)
    {
        return $this->saveOrderItems($order);
    }

    public function updated($order)
    {
        return $this->saveOrderItems($order);
    }    private function saveOrderItems($order)
    {
        try {
            DB::beginTransaction();

            // First delete all existing items (for update case)
            OrderItem::where('order_id', $order->id)->delete();

            // Process order items if available
            if (request()->has('order_items') && is_array(request()->order_items)) {
                foreach (request()->order_items as $item) {
                    if (isset($item['product_id']) && isset($item['quantity']) && isset($item['price'])) {
                        $productId = $item['product_id'];
                        if ($productId) {
                            $orderItem = new OrderItem();
                            $orderItem->order_id = $order->id;
                            $orderItem->product_id = $productId;
                            $orderItem->quantity = $item['quantity'];
                            $orderItem->price = $item['price'];
                            $orderItem->save();

                            // Decrease product stock_quantity
                            $product = \App\Models\Product::find($productId);
                            if ($product) {
                                $product->stock_quantity = max(0, $product->stock_quantity - $item['quantity']);
                                $product->save();
                            }
                        }
                    }
                }
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function exportPdf($id)
    {
        // Get ID from hash
        $id = $this->getIdFromHash($id);
        
        // Get order with related data
        $order = Order::with(['client', 'user', 'orderItems.product'])->find($id);
        
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        $data = [
            'order' => $order,
            'date' => date('d/m/Y'),
            'orderItems' => $order->orderItems,
        ];
        
        // Generate PDF
        $pdf = PDF::loadView('pdf_templates.order', $data);
        
        // Set PDF options if needed
        $pdf->setPaper('letter', 'portrait');
        
        // Return PDF for download
        return $pdf->download('order_' . $order->id . '.pdf');
    }
}
