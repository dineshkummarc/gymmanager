<?php
namespace App\Livewire\Dashboard;
use Livewire\Component;
use App\Services\DashboardService;
class ExecutiveDashboard extends Component {
    public ?int $branchId = null;
    public function render() {
        $svc = new DashboardService(branchId: $this->branchId ?: null);
        return view('livewire.dashboard.executive-dashboard', [
            'stats'=>$svc->stats(), 'revenue'=>$svc->revenueByMonth(),
            'checkins'=>$svc->checkinsByDay(), 'branches'=>$svc->studentsByBranch(),
            'branchList'=>\App\Models\Branch::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
