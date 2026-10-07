<?php
namespace App\Policies;
use App\Models\{User, Student};
class StudentPolicy {
    public function viewAny(User $u): bool { return $u->canAccess('students')||$u->canAccess('portal'); }
    public function view(User $u, Student $s): bool {
        if ($u->role==='super_admin') return true;
        if ($u->role==='student') return $s->user_id===$u->id;
        return $u->gym_id===$s->gym_id && $u->canAccess('students');
    }
    public function create(User $u): bool { return $u->canAccess('students'); }
    public function update(User $u, Student $s): bool { return $u->gym_id===$s->gym_id && $u->canAccess('students'); }
    public function delete(User $u, Student $s): bool { return in_array($u->role,['super_admin','owner','manager']) && $u->gym_id===$s->gym_id; }
}
