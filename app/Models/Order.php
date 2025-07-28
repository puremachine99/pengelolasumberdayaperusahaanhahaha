<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = ['table_id', 'customer_id', 'status', 'total_price'];
    protected static function booted()
    {
        static::saving(function ($order) {
            $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
            $discount = $order->items->sum('discount_value');
            $order->total_price = $subtotal - $discount;
        });

        static::updated(function ($order) {
            if ($order->wasChanged('status') && $order->status === 'paid') {
                // Cek kalau transaksi belum pernah dibuat agar tidak double
                $exists = \App\Models\CashTransaction::where('source', 'orders')
                    ->where('source_id', $order->id)
                    ->exists();

                if (!$exists) {
                    \App\Models\CashTransaction::create([
                        'type' => 'in',
                        'amount' => $order->total_price,
                        'category' => 'Penjualan',
                        'payment_method' => 'Cash', // bisa dikembangkan jadi dynamic nanti
                        'transaction_date' => $order->created_at->startOfDay(),
                        'description' => "Pembayaran Order #{$order->id}",
                        'source' => 'orders',
                        'source_id' => $order->id,
                    ]);
                }
            }
        });

    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

