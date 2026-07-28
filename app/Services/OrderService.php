<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;

    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $products = Product::query()->whereIn('id', collect($data['products'])->pluck('product_id'))->get()->keyBy('id');
            $lastOrderNumber = Order::query()->orderByDesc('order_number')->lockForUpdate()->value('order_number');
            $order = Order::create([
                'order_number' => $lastOrderNumber ? $lastOrderNumber + 1 : 134500001,
                'customer_id' => $data['customer'],
                'order_date' => $data['date'],
                'status' => self::STATUS_PENDING,
            ]);

            foreach ($data['products'] as $item) {
                $product = $products->get($item['product_id']);
                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'amount' => $item['quantities'],
                    'price' => $product->price,
                ]);
            }

            return $order->load('orderItems.product');
        });
    }

    public function approve(int $orderId, ?int $userId): Order
    {
        return DB::transaction(function () use ($orderId, $userId): Order {
            $order = Order::query()->lockForUpdate()->findOrFail($orderId);
            if ((int) $order->status !== self::STATUS_PENDING) {
                throw ValidationException::withMessages(['order' => 'Only pending orders can be approved.']);
            }

            $items = $order->orderItems()->with('product')->get();
            $stocks = Stock::query()->whereIn('product_id', $items->pluck('product_id'))->lockForUpdate()->get()->keyBy('product_id');

            foreach ($items as $item) {
                $stock = $stocks->get($item->product_id);
                if (! $stock || $stock->quantity < $item->amount) {
                    throw ValidationException::withMessages(['stock' => "Insufficient stock for {$item->product->name}."]);
                }
            }

            foreach ($items as $item) {
                $stock = $stocks->get($item->product_id);
                $stock->decrement('quantity', $item->amount);
                $stock->refresh();
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'order_id' => $order->id,
                    'user_id' => $userId,
                    'type' => 2,
                    'balance' => $stock->quantity,
                    'quantity' => $item->amount,
                ]);
            }

            $order->update(['status' => self::STATUS_APPROVED]);
            return $order->refresh();
        });
    }
}