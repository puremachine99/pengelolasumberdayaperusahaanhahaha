<x-filament::page>
    <div class="space-y-6">

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @livewire(\App\Filament\Widgets\FinanceSummaryStats::class)
        </div>

        {{-- (Optional) Chart atau Table bisa disisipkan di bawah sini --}}

        {{-- Contoh Layout Chart Future --}}
        {{-- 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                @livewire(\App\Filament\Widgets\ExpenseCategoryChart::class)
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                @livewire(\App\Filament\Widgets\RevenueVsExpenseChart::class)
            </div>
        </div>
        --}}

    </div>
</x-filament::page>
