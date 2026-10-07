<?php
namespace App\Services;
use App\Models\{Student, Checkin};
class CheckinService {
    public function __construct(protected AccessControlService $access = new AccessControlService) {}
    public function checkin(Student $s, string $method='search', ?int $branchId=null): Checkin {
        $v = $this->access->validate($s, $branchId ?? $s->branch_id);
        return Checkin::create(['branch_id'=>$branchId??$s->branch_id,'student_id'=>$s->id,'method'=>$method,
            'checked_in_at'=>now(),'allowed'=>$v['allowed'],'deny_reason'=>$v['reason'],'user_id'=>auth()->id()]);
    }
}
