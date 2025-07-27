<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Table;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\MenuCategory;
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

        $token = bin2hex(random_bytes(16));

        session([
            'active_order_id' => $order->id,
            'order_access_token' => $token,
            'guest_name' => $request->name, // <-- TAMBAHKAN INI
            'guest_phone' => $request->phone, // <-- DAN INI
        ]);

        $order->update(['access_token' => $token]);

        return redirect()->route('qr.menu', [
            'order' => $order->id,
            'access_token' => $token,
        ]);
    }


    public function showMenu(Order $order, Request $request)
    {
        $token = $request->query('access_token');

        if ($token !== session('order_access_token')) {
            abort(403, 'Token tidak valid.');
        }

        $menus = Menu::orderByDesc('is_available')->get();
        $categories = MenuCategory::orderBy('name')->get();
        $categoryCount = $categories->count();

        return view('qr.menu', compact('order', 'menus', 'categories', 'categoryCount'));
    }
    public function showMenus(Request $request)
    {
        $token = $request->query('access_token');
        $orderId = session('active_order_id');

        // Validasi token
        if (!$token || $token !== session('order_access_token')) {
            abort(403, 'Token tidak valid.');
        }

        // Ambil order dari session
        $order = Order::with(['customer', 'table'])->findOrFail($orderId);

        // Filter berdasarkan kategori (opsional)
        $categoryId = $request->query('category');

        $menus = Menu::query()
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderByDesc('is_available')
            ->get();

        $categories = MenuCategory::withCount('menus')->orderBy('name')->get();
        $categoryCount = $categories->count();

        return view('qr.menu', compact('order', 'menus', 'categories', 'categoryCount'));
    }


    public function showInvoice(Order $order, Request $request)
    {
        $token = $request->query('access_token');

        if ($token !== session('order_access_token')) {
            abort(403, 'Token tidak valid.');
        }

        // ✅ Ambil relasi menu + ambil kolom tambahan diskon (kalau pakai select manual, pastikan diskon_value juga kebawa)
        $order->load(['items.menu']);

        session()->forget('order_access_token'); // Optional: hanya 1 kali pakai

        return view('qr.invoice', compact('order'));
    }


    public function submitOrder(Request $request, Order $order)
    {
        $items = collect($request->items)->filter(fn($item) => $item['quantity'] > 0);
        $total = 0;

        $promos = Promo::activeNow()->get();

        foreach ($items as $item) {
            $menu = Menu::find($item['menu_id']);
            if (!$menu)
                continue;

            $itemType = 'normal';
            $discountValue = null;
            $finalPrice = $menu->price;

            foreach ($promos as $promo) {
                $conditions = collect($promo->conditions)->pluck('value', 'key');

                if ($promo->type === 'percent' && $conditions->get('menu_id') == $menu->id) {
                    $itemType = 'discount_percent';
                    $discountValue = $promo->value;
                    $finalPrice = $menu->price - ($menu->price * ($promo->value / 100));
                    break; // ambil promo pertama yg cocok
                }

                if ($promo->type === 'fixed' && $conditions->get('menu_id') == $menu->id) {
                    $itemType = 'discount_fixed';
                    $discountValue = $promo->value;
                    $finalPrice = $menu->price - $promo->value;
                    break;
                }
            }

            $subtotal = $finalPrice * $item['quantity'];
            $total += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'price' => $finalPrice,
                'type' => $itemType,
                'discount_value' => $discountValue,
            ]);
        }

        $appliedPromo = $this->applyPromoToOrder($order, $items, $total, $promos);

        $order->update([
            'total_price' => $total - ($appliedPromo['discount'] ?? 0),
            'promo_id' => $appliedPromo['promo_id'] ?? null,
            'discount' => $appliedPromo['discount'] ?? 0,
        ]);

        return redirect()->route('orders.invoice', [
            'order' => $order->id,
            'access_token' => session('order_access_token'),
        ])->with('success', 'Pesanan berhasil, lanjut ke invoice.');
    }

    private function applyPromoToOrder(Order $order, $items, $total, $promos = null)
    {
        $promos = $promos ?? Promo::activeNow()->get();

        $applied = [
            'promo_id' => null,
            'discount' => 0,
        ];

        foreach ($promos as $promo) {
            $conditions = collect($promo->conditions)->pluck('value', 'key');
            $type = $promo->type;


            // ✅ Buy 1 Get 1
            if ($type === 'b1g1' && $conditions->has('menu_id')) {
                foreach ($items as $item) {
                    if ($item['menu_id'] == $conditions['menu_id'] && $item['quantity'] >= 1) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'menu_id' => $item['menu_id'],
                            'quantity' => $item['quantity'],
                            'price' => 0,
                            'type' => 'b1g1',
                            'discount_value' => $promo->value,
                        ]);
                        $applied['promo_id'] = $promo->id;
                    }
                }
            }

            // Bonus item
            if ($type === 'bonus' && $conditions->has('bonus_menu_id')) {
                foreach ($items as $item) {
                    if (
                        !$conditions->has('menu_id') ||
                        $item['menu_id'] == $conditions['menu_id']
                    ) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'menu_id' => $conditions['bonus_menu_id'],
                            'quantity' => $item['quantity'],
                            'price' => 0,
                            'type' => 'bonus',
                            'discount_value' => $promo->value,
                        ]);
                        $applied['promo_id'] = $promo->id;
                    }
                }
            }

            // Cashback
            if ($type === 'cashback' && $conditions->has('min_total')) {
                if ($total >= $conditions['min_total']) {
                    $applied = [
                        'promo_id' => $promo->id,
                        'discount' => $promo->value,
                    ];
                }
            }
        }

        return $applied;
    }




}
