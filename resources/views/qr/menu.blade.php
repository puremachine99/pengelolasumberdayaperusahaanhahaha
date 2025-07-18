<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-indigo-900">
                🍽️ Pesanan untuk <span class="text-indigo-600">{{ $order->customer->name }}</span>
                <span class="text-sm font-normal text-gray-500">| Meja
                    {{ $order->table->name ?? 'Belum ditentukan' }}</span>
            </h2>
            <div class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                📅 {{ now()->format('d M Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-5xl mx-auto">
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-green-500">🎉</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ url('/order/' . $order->id . '/submit') }}" id="order-form">
            @csrf

            <!-- Menu Grid Section -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="bg-indigo-100 text-indigo-800 p-2 rounded-lg mr-3">📋</span>
                    Daftar Menu
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($menus as $menu)
                        <div
                            class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition {{ !$menu->is_available ? 'opacity-50 pointer-events-none' : '' }}">
                            <!-- Image Slider Container -->
                            <div class="h-40 bg-gray-100 overflow-hidden relative group">
                                @if (!empty($menu->images) && count($menu->images) > 0)
                                    <!-- Slides -->
                                    <div class="relative h-full w-full">
                                        @foreach ($menu->images as $index => $image)
                                            <div class="absolute inset-0 transition-opacity duration-300 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                                data-slide="{{ $menu->id }}-{{ $index }}">
                                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $menu->name }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Slider Navigation Dots -->
                                    @if (count($menu->images) > 1)
                                        <div
                                            class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1">
                                            @foreach ($menu->images as $index => $image)
                                                <button type="button"
                                                    class="w-2 h-2 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition {{ $index === 0 ? 'bg-opacity-100' : '' }}"
                                                    data-target="{{ $menu->id }}-{{ $index }}"
                                                    aria-label="Go to slide {{ $index + 1 }}"></button>
                                            @endforeach
                                        </div>

                                        <!-- Navigation Arrows -->
                                        <button type="button"
                                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-30 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition"
                                            data-slider-prev="{{ $menu->id }}" aria-label="Previous image">
                                            &larr;
                                        </button>
                                        <button type="button"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-30 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition"
                                            data-slider-next="{{ $menu->id }}" aria-label="Next image">
                                            &rarr;
                                        </button>
                                    @endif
                                @else
                                    <!-- Fallback when no images -->
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50">
                                        <span class="text-4xl">🍔</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Menu Details -->
                            <div class="p-4">
                                <h4 class="font-bold text-gray-800 mb-1">
                                    {{ $menu->name }}
                                    @if (!$menu->is_available)
                                        <span class="text-xs text-red-600 font-medium">(Sold Out)</span>
                                    @endif
                                </h4>
                                <p class="text-indigo-600 font-medium mb-3">
                                    Rp{{ number_format($menu->price, 0, ',', '.') }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button type="button"
                                            class="decrement-btn px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                                            data-menu-id="{{ $menu->id }}"
                                            {{ !$menu->is_available ? 'disabled' : '' }}>
                                            -
                                        </button>
                                        <input type="number" name="items[{{ $menu->id }}][quantity]"
                                            value="0" min="0" data-price="{{ $menu->price }}"
                                            class="qty w-12 px-2 py-1 text-center border-x border-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                            data-menu-id="{{ $menu->id }}"
                                            {{ !$menu->is_available ? 'disabled' : '' }}>
                                        <button type="button"
                                            class="increment-btn px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                                            data-menu-id="{{ $menu->id }}"
                                            {{ !$menu->is_available ? 'disabled' : '' }}>
                                            +
                                        </button>
                                    </div>
                                    <div class="text-right">
                                        <span id="total-{{ $menu->id }}"
                                            class="text-sm font-medium text-indigo-600">Rp0</span>
                                    </div>
                                </div>
                                <input type="hidden" name="items[{{ $menu->id }}][menu_id]"
                                    value="{{ $menu->id }}">
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100 sticky bottom-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Ringkasan Pesanan</h3>
                    <div class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                        🛒 <span id="total-items">0</span> Item
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Total Harga:</span>
                        <span id="total-price" class="text-2xl font-bold text-indigo-600">Rp0</span>
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-2">
                        Tambahkan item favoritmu dan klik tombol di bawah untuk memesan!
                    </p>
                </div>

                <button type="submit"
                    class="w-full mt-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 font-bold text-lg flex items-center justify-center">
                    🚀 Kirim Pesanan
                    <span id="order-btn-text" class="ml-2">(0 item)</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const qtyInputs = document.querySelectorAll('.qty');
            const incrementBtns = document.querySelectorAll('.increment-btn');
            const decrementBtns = document.querySelectorAll('.decrement-btn');

            function updateTotals() {
                let totalItems = 0;
                let totalPrice = 0;

                qtyInputs.forEach(input => {
                    const qty = parseInt(input.value) || 0;
                    const price = parseInt(input.dataset.price);
                    const menuId = input.dataset.menuId;
                    const totalPerMenu = qty * price;

                    document.getElementById(`total-${menuId}`).innerText =
                        `Rp${totalPerMenu.toLocaleString('id-ID')}`;
                    totalItems += qty;
                    totalPrice += totalPerMenu;
                });

                document.getElementById('total-items').innerText = totalItems;
                document.getElementById('total-price').innerText = `Rp${totalPrice.toLocaleString('id-ID')}`;
                document.getElementById('order-btn-text').innerText =
                    `(${totalItems} item${totalItems !== 1 ? 's' : ''})`;

                // Update button state
                const submitBtn = document.querySelector('button[type="submit"]');
                if (totalItems > 0) {
                    submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                    submitBtn.disabled = false;
                } else {
                    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                    submitBtn.disabled = true;
                }
            }

            // Add increment/decrement functionality
            incrementBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const menuId = btn.dataset.menuId;
                    const input = document.querySelector(`.qty[data-menu-id="${menuId}"]`);
                    input.value = parseInt(input.value) + 1;
                    input.dispatchEvent(new Event('input'));
                });
            });

            decrementBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const menuId = btn.dataset.menuId;
                    const input = document.querySelector(`.qty[data-menu-id="${menuId}"]`);
                    if (parseInt(input.value) > 0) {
                        input.value = parseInt(input.value) - 1;
                        input.dispatchEvent(new Event('input'));
                    }
                });
            });

            qtyInputs.forEach(input => {
                input.addEventListener('input', updateTotals);
            });

            // Initialize
            updateTotals();
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sliders for each menu card
            document.querySelectorAll('[data-slider-prev], [data-slider-next]').forEach(button => {
                button.addEventListener('click', function() {
                    const menuId = this.getAttribute('data-slider-prev') || this.getAttribute(
                        'data-slider-next');
                    const slides = document.querySelectorAll(`[data-slide^="${menuId}-"]`);
                    const dots = document.querySelectorAll(`[data-target^="${menuId}-"]`);
                    let currentIndex = 0;

                    // Find current active slide
                    slides.forEach((slide, index) => {
                        if (slide.classList.contains('opacity-100')) {
                            currentIndex = index;
                        }
                    });

                    // Calculate new index
                    let newIndex;
                    if (this.getAttribute('data-slider-prev')) {
                        newIndex = (currentIndex - 1 + slides.length) % slides.length;
                    } else {
                        newIndex = (currentIndex + 1) % slides.length;
                    }

                    // Update slides and dots
                    updateSlider(menuId, newIndex);
                });
            });

            // Dot navigation
            document.querySelectorAll('[data-target]').forEach(dot => {
                dot.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    const [menuId, slideIndex] = target.split('-');
                    updateSlider(menuId, parseInt(slideIndex));
                });
            });

            function updateSlider(menuId, newIndex) {
                const slides = document.querySelectorAll(`[data-slide^="${menuId}-"]`);
                const dots = document.querySelectorAll(`[data-target^="${menuId}-"]`);

                // Hide all slides and reset all dots
                slides.forEach(slide => {
                    slide.classList.remove('opacity-100');
                    slide.classList.add('opacity-0');
                });
                dots.forEach(dot => {
                    dot.classList.remove('bg-opacity-100');
                    dot.classList.add('bg-opacity-50');
                });

                // Show selected slide and update dot
                slides[newIndex].classList.remove('opacity-0');
                slides[newIndex].classList.add('opacity-100');
                if (dots[newIndex]) {
                    dots[newIndex].classList.remove('bg-opacity-50');
                    dots[newIndex].classList.add('bg-opacity-100');
                }
            }
        });
    </script>
</x-app-layout>
