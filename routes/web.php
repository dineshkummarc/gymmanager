<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard\ExecutiveDashboard;
use App\Livewire\Students\{StudentTable, StudentForm, StudentProfile};
use App\Livewire\Checkin\CheckinScreen;
use App\Livewire\Finance\{PaymentTable, CashRegisterBoard, FinancialDashboard};
use App\Livewire\Crm\LeadPipeline;
use App\Livewire\Workouts\WorkoutBuilder;
use App\Livewire\Classes\ClassCalendar;
use App\Livewire\Stock\InventoryDashboard;
use App\Livewire\Plans\PlanManager;
use App\Livewire\Team\TeacherTable;
use App\Livewire\Reports\ReportsIndex;
use App\Livewire\Portal\StudentPortal;
use App\Livewire\Assessments\AssessmentManager;
use App\Livewire\Memberships\MembershipManager;
use App\Livewire\Contracts\ContractManager;
use App\Livewire\Sales\SaleManager;
use App\Livewire\Commissions\CommissionBoard;
use App\Livewire\Branches\BranchManager;
use App\Livewire\Users\UserManager;
use App\Livewire\Audit\ActivityTimeline;
use App\Livewire\History\CheckinHistory;

use App\Livewire\Boards\ReceptionBoard;
use App\Livewire\Boards\TeacherBoard;

Route::get('/', fn() => redirect(auth()->check() ? '/dashboard' : '/login'));
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/forgot-password', ForgotPassword::class)->middleware('guest')->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->middleware('guest')->name('password.reset');
Route::post('/logout', function(){ auth()->logout(); session()->invalidate(); session()->regenerateToken(); return redirect('/login'); });

Route::middleware(['auth','gym.context'])->group(function(){
    Route::get('/dashboard', ExecutiveDashboard::class)->name('dashboard');
    Route::get('/students', StudentTable::class)->name('students.index');
    Route::get('/students/create', StudentForm::class)->name('students.create');
    Route::get('/students/{student}/edit', StudentForm::class)->name('students.edit');
    Route::get('/students/{student}', StudentProfile::class)->name('students.show');
    Route::get('/plans', PlanManager::class)->name('plans.index');
    Route::get('/memberships', MembershipManager::class)->name('memberships.index');
    Route::get('/contracts', ContractManager::class)->name('contracts.index');
    Route::get('/checkin', CheckinScreen::class)->name('checkin');
    Route::get('/checkins', CheckinHistory::class)->name('checkins.index');
    Route::get('/payments', PaymentTable::class)->name('payments.index');
    Route::get('/cash', CashRegisterBoard::class)->name('cash.index');
    Route::get('/financial', FinancialDashboard::class)->name('financial.index');
    Route::get('/workouts/{planId?}', WorkoutBuilder::class)->name('workouts.index');
    Route::get('/assessments', AssessmentManager::class)->name('assessments.index');
    Route::get('/classes', ClassCalendar::class)->name('classes.index');
    Route::get('/leads', LeadPipeline::class)->name('leads.index');
    Route::get('/teachers', TeacherTable::class)->name('teachers.index');
    Route::get('/sales', SaleManager::class)->name('sales.index');
    Route::get('/commissions', CommissionBoard::class)->name('commissions.index');
    Route::get('/inventory', InventoryDashboard::class)->name('inventory.index');
    Route::get('/branches', BranchManager::class)->name('branches.index');
    Route::get('/users', UserManager::class)->name('users.index');
    Route::get('/audit', ActivityTimeline::class)->name('audit.index');
    Route::get('/reports', ReportsIndex::class)->name('reports.index');
    Route::get('/reception', ReceptionBoard::class)->name('reception');
    Route::get('/teacher', TeacherBoard::class)->name('teacher');
    Route::get('/student-portal', StudentPortal::class)->name('portal');
    Route::get('/reports/export/{type}', function(string $type){
        $map = ['students'=>\App\Models\Student::class,'invoices'=>\App\Models\Invoice::class,'checkins'=>\App\Models\Checkin::class,'payments'=>\App\Models\Payment::class];
        abort_unless(isset($map[$type]), 404);
        $rows = $map[$type]::limit(2000)->get();
        return response()->streamDownload(function() use ($rows){
            $out = fopen('php://output','w');
            if($rows->isNotEmpty()){ fputcsv($out, array_keys($rows->first()->toArray())); foreach($rows as $r) fputcsv($out, $r->toArray()); }
            fclose($out);
        }, "gymmanager-{$type}.csv", ['Content-Type'=>'text/csv']);
    })->name('reports.export');
});
