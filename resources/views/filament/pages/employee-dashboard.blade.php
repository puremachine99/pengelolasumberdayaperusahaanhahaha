<x-filament::page>
    <div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @livewire(\App\Filament\Widgets\EmployeeMovementStats::class)
            @livewire(\App\Filament\Widgets\EmployeeStatusChart::class)
            @livewire(\App\Filament\Widgets\ContractTypeChart::class)
        </div>

        <div class="grid grid-cols-1 gap-4 mt-6">
            @livewire(\App\Filament\Widgets\TopDepartmentsChart::class)
            @livewire(\App\Filament\Widgets\CurrentLeaves::class)
        </div>
    </div>
</x-filament::page>
