<?php
namespace App\Services;
use App\Models\Student;
class AccessControlService {
    public function validate(Student $s, ?int $branchId = null): array {
        $m = $s->activeMembership;
        if (!$m) return ['allowed'=>false,'reason'=>'Sem matrícula ativa'];
        if ($m->ends_at->isPast()) return ['allowed'=>false,'reason'=>'Matrícula expirada em '.$m->ends_at->format('d/m/Y')];
        $overdue = $s->invoices()->whereIn('status',['pending','overdue'])->where('due_date','<',now()->toDateString())->exists();
        if ($overdue) return ['allowed'=>false,'reason'=>'Mensalidade vencida'];
        if ($s->status==='suspended') return ['allowed'=>false,'reason'=>'Matrícula suspensa'];
        if ($branchId && $m->plan && !$m->plan->all_branches && $s->branch_id !== $branchId) return ['allowed'=>false,'reason'=>'Plano não permite esta unidade'];
        return ['allowed'=>true,'reason'=>null,'membership'=>$m];
    }
}
