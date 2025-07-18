<?php
namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class CurrentLeaves extends Widget
{
    protected static string $view = 'filament.widgets.current-leaves';
    protected static ?int $sort = 2;
    public $leaves;

    public function mount(): void
    {
        $today = Carbon::today();

        $this->leaves = LeaveRequest::with('employee', 'leaveType')
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date')
            ->get();
    }
}

