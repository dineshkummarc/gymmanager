<?php
namespace App\Policies;
use App\Models\User;
class MembershipPolicy {
    public function viewAny(User $u): bool { return $u->canAccess('students')||$u->canAccess('memberships'); }
    public function create(User $u): bool { return $u->canAccess('students'); }
    public function update(User $u): bool { return $u->canAccess('students'); }
}
