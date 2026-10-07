<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{Student, Membership};
use App\Policies\{StudentPolicy, MembershipPolicy};
class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        \Illuminate\Pagination\Paginator::useTailwind();
        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(Membership::class, MembershipPolicy::class);
        Gate::before(fn($u,$a)=> $u->role==='super_admin' ? true : null);
    }
}
