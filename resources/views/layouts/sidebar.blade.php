<!-- Sidebar Component -->
<div
    class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg border-r border-gray-200 flex flex-col z-10 transition-all duration-300 ease-in-out">
    <!-- App Name/Logo -->
    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
        <div class="flex items-center space-x-3">
            <span class="text-2xl text-indigo-600">🍽️</span>
            <div>
                <h1 class="text-xl font-bold text-indigo-800">RestoApp</h1>
                <p class="text-xs text-indigo-500 mt-0.5">Sistem Pemesanan Restoran</p>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="flex-1 overflow-y-auto p-4 bg-gray-50">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 px-2">Kategori Menu</h3>

        <nav class="space-y-1.5">
            {{-- Semua Menu --}}
            <a href="{{ route('menus.index') }}"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ request()->routeIs('menus.index') && !request('category')
                          ? 'bg-indigo-100 text-indigo-800 shadow-inner border border-indigo-200'
                          : 'text-gray-700 hover:bg-white hover:shadow-sm hover:border-gray-200 border border-transparent' }}">
                <span class="flex-1">Semua Menu</span>
                <span class="ml-2 bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full font-semibold">
                    {{ $categoryCount }}
                </span>
            </a>

            {{-- Per Kategori --}}
            @foreach ($categories as $category)
                <a href="{{ route('menus.index', [
                    'category' => $category->id,
                    'access_token' => session('order_access_token'),
                ]) }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request('category') == $category->id
                              ? 'bg-indigo-100 text-indigo-800 shadow-inner border border-indigo-200'
                              : 'text-gray-700 hover:bg-white hover:shadow-sm hover:border-gray-200 border border-transparent' }}">
                    <span class="flex-1">{{ $category->name }}</span>
                    <span class="ml-2 bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full font-semibold">
                        {{ $category->menus_count }}
                    </span>
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Footer/User Section -->
    <div class="p-4 border-t border-gray-200 bg-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <span
                    class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-800 border-2 border-indigo-200">
                    @if (Auth::check())
                        👨‍🍳
                    @else
                        👤
                    @endif
                </span>
            </div>

            <div class="ml-3">
                @if (Auth::check())
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->role }}</p>
                @elseif(session()->has('guest_name'))
                    <p class="text-sm font-medium text-gray-900">{{ session('guest_name') }}</p>
                    <p class="text-xs text-gray-500">
                        {{ session('guest_phone') ?? 'Pelanggan' }}
                    </p>
                @else
                    <p class="text-sm font-medium text-gray-900">Tamu</p>
                    <p class="text-xs text-gray-500">Belum login</p>
                @endif
            </div>
        </div>
    </div>
</div>
