<?php
namespace App\Services;
use App\Models\{Invoice, Payment, Student, Membership, Checkin};
use Illuminate\Support\Facades\DB;
class ReportService {
    public function churn(): array {
        $cancelled = Membership::where('status','cancelled')->where('updated_at','>=',now()->subDays(30))->count();
        $active = Membership::where('status','active')->count();
        return ['cancelled'=>$cancelled,'active'=>$active,'rate'=>$active?round($cancelled/max(1,$active+$cancelled)*100,1):0];
    }
    public function delinquencyByRange(): array {
        $ranges = ['1-7'=>[1,7],'8-30'=>[8,30],'31-60'=>[31,60],'60+'=>[61,9999]];
        $out=[];
        foreach($ranges as $k=>[$a,$b]){
            $out[$k]=['count'=>(int)Invoice::whereIn('status',['pending','overdue'])->whereRaw('julianday(?) - julianday(due_date) BETWEEN ? AND ?',[now()->toDateString(),$a,$b])->count(),
                'total'=>(float)Invoice::whereIn('status',['pending','overdue'])->whereRaw('julianday(?) - julianday(due_date) BETWEEN ? AND ?',[now()->toDateString(),$a,$b])->sum('amount')];
        }
        return $out;
    }
    public function occupancy(): array {
        return \App\Models\GymClass::withCount(['reservations as confirmed'=>fn($q)=>$q->where('status','reserved')])
            ->orderBy('starts_at')->limit(20)->get()->map(fn($c)=>['name'=>$c->name,'date'=>$c->starts_at->format('d/m H:i'),'occ'=>$c->capacity?round($c->confirmed/$c->capacity*100):0])->all();
    }
}
