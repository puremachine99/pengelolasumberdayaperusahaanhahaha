@props(['menu'])
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
                <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1">
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
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50">
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
                    data-menu-id="{{ $menu->id }}" {{ !$menu->is_available ? 'disabled' : '' }}>
                    -
                </button>
                <input type="number" name="items[{{ $menu->id }}][quantity]" value="0" min="0"
                    data-price="{{ $menu->price }}"
                    class="qty w-12 px-2 py-1 text-center border-x border-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    data-menu-id="{{ $menu->id }}" {{ !$menu->is_available ? 'disabled' : '' }}>
                <button type="button"
                    class="increment-btn px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                    data-menu-id="{{ $menu->id }}" {{ !$menu->is_available ? 'disabled' : '' }}>
                    +
                </button>
            </div>
            <div class="text-right">
                <span id="total-{{ $menu->id }}" class="text-sm font-medium text-indigo-600">Rp0</span>
            </div>
        </div>
        <input type="hidden" name="items[{{ $menu->id }}][menu_id]" value="{{ $menu->id }}">
    </div>
</div>
