<div>
    <x-filament::card>
        <h2 class="text-lg font-bold mb-4">Sedang Cuti Hari Ini</h2>

        @forelse ($leaves as $leave)
            <div class="mb-2">
                <strong>{{ $leave->employee->name }}</strong>
                <span class="text-sm text-gray-500">({{ $leave->leaveType->name }})</span><br>
                <span class="text-sm">
                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                    →
                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                </span>

            </div>
        @empty
            <p class="text-sm text-gray-500">Tidak ada yang cuti hari ini.</p>
        @endforelse
    </x-filament::card>
</div>
