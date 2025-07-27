<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                INVOICE #{{ $order->id }}
            </h2>
            <div class="bg-gray-900 text-gray-100 px-3 py-1 rounded-full text-sm font-bold">
                {{ $order->created_at->format('d M Y H:i') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 max-w-5xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
            <!-- Customer Info -->
            <div class="bg-gray-900 px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Customer</h3>
                        <p class="mt-1 text-base font-bold text-white">{{ $order->customer->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Table</h3>
                        <p class="mt-1 text-base font-bold text-white">{{ $order->table->name ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Status</h3>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $order->status === 'completed' ? 'bg-green-800 text-green-100' : 'bg-amber-600 text-amber-100' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="px-6 py-6">
                <div class="flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            @php
                                $total = 0;
                                $totalDiscount = 0;
                                $normalItems = $order->items
                                    ->where('type', 'normal')
                                    ->merge($order->items->whereIn('type', ['discount_percent', 'discount_fixed']));
                                $bonusItems = $order->items->whereNotIn('type', [
                                    'normal',
                                    'discount_percent',
                                    'discount_fixed',
                                ]);
                            @endphp

                            <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">ORDER ITEMS</h2>
                            <table class="min-w-full divide-y divide-gray-200 mb-8">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Menu</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-gray-900 uppercase tracking-wider">Qty</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-gray-900 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-gray-900 uppercase tracking-wider">Discount</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-gray-900 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($normalItems as $item)
                                        @php
                                            $subtotal = $item->price * $item->quantity;
                                            $discount = 0;

                                            if ($item->type === 'discount_percent') {
                                                $discount = ($item->discount_value / 100) * $subtotal;
                                            } elseif ($item->type === 'discount_fixed') {
                                                $discount = $item->discount_value;
                                            }

                                            $total += $subtotal - $discount;
                                            $totalDiscount += $discount;
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->menu->name }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500">{{ $item->quantity }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-red-600 font-medium">-Rp{{ number_format($discount, 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-900 font-medium">Rp{{ number_format($subtotal - $discount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($bonusItems->count())
                                <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">BONUS ITEMS</h2>
                                <table class="min-w-full divide-y divide-gray-200 mb-6">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Menu</th>
                                            <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-gray-900 uppercase tracking-wider">Qty</th>
                                            <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-gray-900 uppercase tracking-wider">Price</th>
                                            <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-gray-900 uppercase tracking-wider">Type</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($bonusItems as $item)
                                            <tr>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->menu->name }}</td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500">{{ $item->quantity }}</td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500">FREE</td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-600 font-medium capitalize">{{ $item->type }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            <div class="mt-8 border-t border-gray-200 pt-6">
                                <div class="flex justify-between text-base font-bold text-gray-900">
                                    <p>Subtotal:</p>
                                    <p>Rp{{ number_format($total, 0, ',', '.') }}</p>
                                </div>
                                @if ($totalDiscount > 0)
                                    <div class="flex justify-between mt-2 text-sm font-bold text-green-600">
                                        <p>Discount:</p>
                                        <p>-Rp{{ number_format($totalDiscount, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between text-xl font-bold text-gray-900">
                    <p>TOTAL</p>
                    <p>Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                <p class="mt-1 text-sm text-gray-500">Taxes and service charges included</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row justify-between gap-4">
            <a href="{{ url('/menu') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-bold rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                ← Back to Menu
            </a>

            @if ($order->status !== 'completed')
                <div class="flex gap-4">
                    <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                        Pay Now
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 transition-colors">
                        Print Invoice
                    </a>
                </div>
            @else
                <div class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-green-700">
                    Payment Completed
                </div>
            @endif
        </div>
    </div>
</x-app-layout>