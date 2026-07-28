<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialInventorySeeder extends Seeder
{
    private const INITIAL_QUANTITY = 100;
    private const MOVEMENT_TYPE_IN = 1;

    public function run(): void
    {
        DB::transaction(function (): void {
            Product::query()->each(function (Product $product): void {
                $stock = Stock::query()->firstOrCreate(
                    ['product_id' => $product->id],
                    ['quantity' => 0]
                );

                $newBalance = $stock->quantity + self::INITIAL_QUANTITY;

                StockMovement::create([
                    'product_id' => $product->id,
                    'order_id' => null,
                    'user_id' => null,
                    'type' => self::MOVEMENT_TYPE_IN,
                    'balance' => $newBalance,
                    'quantity' => self::INITIAL_QUANTITY,
                ]);

                $stock->update(['quantity' => $newBalance]);
            });
        });
    }
}
