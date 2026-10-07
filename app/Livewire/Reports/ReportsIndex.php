<?php
namespace App\Livewire\Reports;
use Livewire\Component;
use App\Services\{ReportService, DashboardService};
class ReportsIndex extends Component {
    public function render(){
        $svc = new ReportService; $d = new DashboardService;
        return view('livewire.reports.reports-index',['churn'=>$svc->churn(),'delinq'=>$svc->delinquencyByRange(),'occ'=>$svc->occupancy(),'revenue'=>$d->revenueByMonth()])->layout('layouts.app');
    }
}
