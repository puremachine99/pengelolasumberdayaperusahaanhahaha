<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {{-- mulai widget disini --}}
        @livewire(\App\Filament\Widgets\OrdersTodayCount::class)
        @livewire(\App\Filament\Widgets\PeakHoursTodayChart::class)
        @livewire(\App\Filament\Widgets\OrdersLast7DaysChart::class)
        @livewire(\App\Filament\Widgets\TopMenusChart::class)
        {{-- sampek kene --}}
    </div>
</x-filament::page>
 