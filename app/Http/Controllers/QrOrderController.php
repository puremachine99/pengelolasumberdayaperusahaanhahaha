<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class QrOrderController extends Controller
{
    public function showForm($tableId)
    {
        $table = Table::findOrFail($tableId);
        return view('qr.start-order', compact('table'));
    }

    public function startOrder(Request $request, $tableId)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        $table = Table::findOrFail($tableId);

        $customer = Customer::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => $request->name, 'last_visited_at' => now()]
        );

        $order = Order::create([
            'table_id' => $table->id,
            'customer_id' => $customer->id,
            'status' => 'pending',
        ]);

        return redirect()->route('qr.menu', ['order' => $order->id]);
    }
    public function showMenu(Order $order)
    {
        $menus = Menu::orderByDesc('is_available')->get(); // Prioritaskan yg tersedia
        return view('qr.menu', compact('order', 'menus'));
    }


    public function submitOrder(Request $request, Order $order)
    {
        $items = collect($request->items)
            ->filter(fn($item) => $item['quantity'] > 0);

        $total = 0;

        foreach ($items as $item) {
            $menu = Menu::find($item['menu_id']);
            if (!$menu)
                continue;

            $subtotal = $menu->price * $item['quantity'];
            $total += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'price' => $menu->price,
            ]);
        }

        $order->update(['total_price' => $total]);

        return redirect()->route('orders.invoice', $order->id)
            ->with('success', 'Pesanan berhasil, lanjut ke invoice.');
    }
    public function showInvoice(Order $order)
    {
        $order->load('items.menu'); // pastikan relasi item → menu ada
        return view('qr.invoice', compact('order'));
    }

}
