<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                🧾 Invoice Pesanan #{{ $order->id }}
            </h2>
            <div class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                📅 {{ $order->created_at->format('d M Y H:i') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <!-- Customer Info -->
            <div class="bg-indigo-50 px-6 py-4 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Pelanggan</h3>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $order->customer->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Meja</h3>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $order->table->name ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            <span
                                class="px-2 py-1 rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="px-6 py-4">
                <div class="flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                            Menu</th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Qty</th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Harga
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Subtotal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                                {{ $item->menu->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                                                Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="border-t border-gray-200 px-6 py-4">
                <div class="flex justify-between text-base font-medium text-gray-900">
                    <p>Total Pesanan</p>
                    <p>Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                <p class="mt-1 text-sm text-gray-500">Termasuk pajak dan biaya layanan</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row justify-between gap-4">
            <a href="{{ url('/menu') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                ← Kembali ke Menu
            </a>

            @if ($order->status !== 'completed')
                <div class="flex gap-3">
                    <a href="#"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        💳 Bayar Sekarang
                    </a>
                    <a href="#"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        🖨️ Cetak Invoice
                    </a>
                </div>
            @else
                <div
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-500">
                    ✅ Sudah Dibayar
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
