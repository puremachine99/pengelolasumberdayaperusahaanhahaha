<div>
    <p><strong>ID Order:</strong> {{ $order->id }}</p>
    <p><strong>Meja:</strong> {{ $order->table?->name ?? '-' }}</p>
    <p><strong>Pelanggan:</strong> {{ $order->customer?->name ?? '-' }}</p>
    <p><strong>Total Harga:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

    <hr class="my-2" />

    <h4 class="font-bold mb-2">Item Pemesanan:</h4>
    <ul class="list-disc list-inside space-y-1">
        @foreach ($order->items as $item)
            <li>{{ $item->menu->name }} × {{ $item->quantity }}</li>
        @endforeach
    </ul>
</div>
