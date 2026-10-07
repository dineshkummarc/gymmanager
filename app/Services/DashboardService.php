<?php
namespace App\Services;
use App\Models\{Student, Membership, Invoice, Payment, Checkin, GymClass, Lead, Teacher};
use Illuminate\Support\Facades\DB;
class DashboardService {
    public function __construct(protected ?int $gymId = null, protected ?int $branchId = null) {
        $this->gymId ??= app()->bound('currentGymId') ? app('currentGymId') : null;
    }
    protected function scoped($q) { return $this->branchId ? $q->where('branch_id', $this->branchId) : $q; }
    public function stats(): array {
        $m = now()->startOfMonth();
        $revenue = (float) $this->scoped(Payment::where('status','paid')->where('paid_at','>=',$m))->sum('amount');
        $projected = (float) $this->scoped(Invoice::whereIn('status',['pending','partial','overdue'])->where('due_date','>=',now()->startOfMonth())->where('due_date','<=',now()->endOfMonth()))->sum('amount');
        $overdue = (float) Invoice::whereIn('status',['pending','overdue'])->where('due_date','<',now()->toDateString())->when($this->branchId,fn($q)=>$q->where('branch_id',$this->branchId))->sum('amount');
        return [
            'active_students' => (int) $this->scoped(Student::active())->count(),
            'new_students' => (int) $this->scoped(Student::where('created_at','>=',$m))->count(),
            'inactive' => (int) $this->scoped(Student::where('status','inactive'))->count(),
            'expiring' => (int) Membership::expiring(15)->when($this->branchId,fn($q)=>$q->where('branch_id',$this->branchId))->count(),
            'pending_invoices' => (int) $this->scoped(Invoice::pending())->count(),
            'revenue_month' => $revenue,
            'projected' => $projected,
            'overdue_total' => $overdue,
            'overdue_count' => (int) $this->scoped(Invoice::whereIn('status',['pending','overdue'])->where('due_date','<',now()->toDateString()))->count(),
            'checkins_today' => (int) $this->scoped(Checkin::whereDate('checked_in_at',today()))->count(),
            'classes_today' => (int) GymClass::whereDate('starts_at',today())->when($this->branchId,fn($q)=>$q->where('branch_id',$this->branchId))->count(),
            'teachers' => (int) Teacher::where('status','active')->when($this->branchId,fn($q)=>$q->where('branch_id',$this->branchId))->count(),
            'leads_open' => (int) Lead::whereNotIn('status',['won','lost'])->when($this->branchId,fn($q)=>$q->where('branch_id',$this->branchId))->count(),
        ];
    }
    public function revenueByMonth(int $months = 12): array {
        $labels=[]; $data=[];
        for($i=$months-1;$i>=0;$i--){ $d=now()->subMonths($i); $labels[]=$d->format('M/y');
            $data[]=(float) Payment::where('status','paid')->whereYear('paid_at',$d->year)->whereMonth('paid_at',$d->month)
                ->when($this->branchId,fn($q)=>$q->where('branch_id',$this->branchId))->sum('amount'); }
        return compact('labels','data');
    }
    public function checkinsByDay(int $days = 14): array {
        $labels=[];$data=[];
        for($i=$days-1;$i>=0;$i--){ $d=now()->subDays($i); $labels[]=$d->format('d/m');
            $data[]=(int) Checkin::whereDate('checked_in_at',$d)->when($this->branchId,fn($q)=>$q->where('branch_id',$this->branchId))->count(); }
        return compact('labels','data');
    }
    public function studentsByBranch(): array {
        $rows = Student::select('branches.name', DB::raw('count(*) as total'))
            ->join('branches','branches.id','=','students.branch_id')
            ->where('students.status','active')->groupBy('branches.name')->pluck('total','name');
        return ['labels'=>$rows->keys()->values()->all(),'data'=>$rows->values()->all()];
    }
}
