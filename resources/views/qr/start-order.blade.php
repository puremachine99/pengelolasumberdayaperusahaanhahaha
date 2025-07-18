<x-app-layout>
    <div class="max-w-lg mx-auto mt-10">
        <h2 class="text-xl font-bold mb-4">Pemesanan Meja #{{ $table->number }}</h2>

        <form method="POST" action="{{ url('/table/' . $table->id) }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block">Nama:</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label for="phone" class="block">Nomor HP:</label>
                <input type="tel" name="phone" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                Mulai Pesan
            </button>
        </form>
    </div>
</x-app-layout>