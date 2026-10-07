<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;
use App\Models\{Gym, Branch, User, Student, Plan, Teacher, Membership, Invoice, Exercise, GymClass, Lead, Sale, Product, Commission, Contract, CashRegister, FinancialTransaction, PhysicalAssessment};
use App\Services\MembershipService;
use App\Support\QrToken;

class InteractionsTest extends TestCase {
    use RefreshDatabase;
    protected Gym $gym; protected Branch $branch; protected User $owner; protected Plan $plan; protected Teacher $teacher;

    protected function setUp(): void {
        parent::setUp();
        $this->gym = Gym::create(['name'=>'G','slug'=>'g','active'=>true]);
        $this->branch = Branch::create(['gym_id'=>$this->gym->id,'name'=>'M','code'=>'M-01','active'=>true]);
        $this->owner = User::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'name'=>'O','email'=>'o@t.test','password'=>Hash::make('password'),'role'=>'owner','active'=>true]);
        $this->plan = Plan::create(['gym_id'=>$this->gym->id,'name'=>'Mensal','price'=>100,'period'=>'monthly','duration_months'=>1]);
        $this->teacher = Teacher::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'name'=>'Prof','email'=>'p@t.test','specialty'=>'Musculação','status'=>'active']);
        $this->actingAs($this->owner);
        app()->instance('currentGymId', $this->gym->id);
    }

    protected function student(array $over = []): Student {
        $code = 'GM'.strtoupper(Str::random(6));
        return Student::create(array_merge(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'name'=>'Aluno '.Str::random(4),'status'=>'active','member_code'=>$code,'qr_token'=>QrToken::make($code)], $over));
    }

    public function test_login_component(): void {
        Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email','o@t.test')->set('password','password')->call('login')->assertRedirect('/dashboard');
        Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email','o@t.test')->set('password','wrong')->call('login')->assertHasErrors('email');
    }

    public function test_forgot_password_sends_link(): void {
        Livewire::test(\App\Livewire\Auth\ForgotPassword::class)
            ->set('email','o@t.test')->call('send')->assertSet('sent', fn($v) => !empty($v));
    }

    public function test_student_crud(): void {
        Livewire::test(\App\Livewire\Students\StudentForm::class)
            ->set('data.name','Novo Aluno')->set('data.branch_id',$this->branch->id)->set('data.email','n@t.test')
            ->call('save')->assertRedirect('/students');
        $this->assertDatabaseHas('students',['name'=>'Novo Aluno']);
        $s = $this->student(['name'=>'Deletavel']);
        Livewire::test(\App\Livewire\Students\StudentTable::class)
            ->set('search','Deletavel')->assertSee('Deletavel')->call('delete',$s->id);
        $this->assertSoftDeleted('students',['id'=>$s->id]);
    }

    public function test_membership_flow(): void {
        $s = $this->student();
        Livewire::test(\App\Livewire\Memberships\MembershipManager::class)
            ->set('student_id',$s->id)->set('plan_id',$this->plan->id)->set('branch_id',$this->branch->id)
            ->set('starts_at',now()->toDateString())->set('price',100)->set('installments',2)->call('save')
            ->assertHasNoErrors();
        $m = Membership::where('student_id',$s->id)->first();
        $this->assertNotNull($m);
        $this->assertEquals(2, $m->invoices()->count());
        $this->assertEquals(1, Contract::where('membership_id',$m->id)->count());
        Livewire::test(\App\Livewire\Memberships\MembershipManager::class)->call('cancel',$m->id);
        $this->assertEquals('cancelled', $m->fresh()->status);
    }

    public function test_payment_flow(): void {
        $s = $this->student();
        $inv = Invoice::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'student_id'=>$s->id,'description'=>'M','amount'=>100,'due_date'=>now(),'status'=>'pending']);
        Livewire::test(\App\Livewire\Finance\PaymentTable::class)->call('markPaid',$inv->id);
        $this->assertEquals('paid', $inv->fresh()->status);
    }

    public function test_contract_flow(): void {
        $s = $this->student();
        $m = (new MembershipService)->create($s,['branch_id'=>$this->branch->id,'plan_id'=>$this->plan->id,'starts_at'=>now()->toDateString(),'price'=>100,'installments'=>1]);
        $c = $m->contract;
        Livewire::test(\App\Livewire\Contracts\ContractManager::class)->call('sign',$c->id);
        $this->assertEquals('active', $c->fresh()->status);
        Livewire::test(\App\Livewire\Contracts\ContractManager::class)->call('newVersion',$c->id);
        $this->assertEquals(2, Contract::where('membership_id',$m->id)->count());
    }

    public function test_checkin_flow(): void {
        $s = $this->student();
        (new MembershipService)->create($s,['branch_id'=>$this->branch->id,'plan_id'=>$this->plan->id,'starts_at'=>now()->toDateString(),'price'=>100,'installments'=>1]);
        Livewire::test(\App\Livewire\Checkin\CheckinScreen::class)
            ->set('search', substr($s->name,0,5))->assertSee($s->name)->call('checkin',$s->id)->assertSet('last.allowed', true);
        $this->assertDatabaseHas('checkins',['student_id'=>$s->id,'allowed'=>1]);
    }

    public function test_workout_flow(): void {
        $s = $this->student();
        $ex = Exercise::create(['gym_id'=>$this->gym->id,'name'=>'Supino','category'=>'Peito','active'=>true]);
        $t = Livewire::test(\App\Livewire\Workouts\WorkoutBuilder::class)->call('addSession');
        $plan = \App\Models\WorkoutPlan::first();
        $this->assertNotNull($plan);
        $sess = $plan->sessions()->first()->id;
        Livewire::test(\App\Livewire\Workouts\WorkoutBuilder::class, ['planId'=>$plan->id])
            ->set('exerciseId',$ex->id)->set('sets',4)->set('reps','10')->set('load','60kg')->call('addExercise',$sess);
        $this->assertDatabaseHas('workout_exercises',['workout_session_id'=>$sess,'exercise_id'=>$ex->id]);
    }

    public function test_assessment_flow(): void {
        $s = $this->student();
        Livewire::test(\App\Livewire\Assessments\AssessmentManager::class)
            ->set('student_id',$s->id)->set('weight',80)->set('height',180)->call('save')->assertHasNoErrors();
        $this->assertDatabaseHas('physical_assessments',['student_id'=>$s->id]);
    }

    public function test_class_flow(): void {
        $s = $this->student();
        Livewire::test(\App\Livewire\Classes\ClassCalendar::class)
            ->set('name','Spinning')->set('teacher_id',$this->teacher->id)
            ->set('starts_at',now()->addDay()->format('Y-m-d\TH:i'))->call('save')->assertHasNoErrors();
        $c = GymClass::first();
        $this->assertNotNull($c);
        Livewire::test(\App\Livewire\Classes\ClassCalendar::class)->call('reserve',$c->id);
        $this->assertDatabaseHas('class_reservations',['gym_class_id'=>$c->id]);
    }

    public function test_lead_flow(): void {
        Livewire::test(\App\Livewire\Crm\LeadPipeline::class)->set('name','Lead X')->set('phone','11999999999')->call('add')->assertHasNoErrors();
        $lead = Lead::first();
        Livewire::test(\App\Livewire\Crm\LeadPipeline::class)->call('move',$lead->id,'contacted');
        $this->assertEquals('contacted', $lead->fresh()->status);
        Livewire::test(\App\Livewire\Crm\LeadPipeline::class)->call('convert',$lead->id);
        $this->assertEquals('won', $lead->fresh()->status);
        $this->assertNotNull($lead->fresh()->converted_student_id);
    }

    public function test_sale_and_commission_flow(): void {
        $s = $this->student();
        Livewire::test(\App\Livewire\Sales\SaleManager::class)
            ->set('student_id',$s->id)->set('kind','product')->set('description','Whey')->set('amount',200)->call('save')->assertHasNoErrors();
        $sale = Sale::first();
        $this->assertNotNull($sale);
        $this->assertDatabaseHas('commissions',['source'=>'sale','source_id'=>$sale->id,'amount'=>20]);
        $com = Commission::first();
        Livewire::test(\App\Livewire\Commissions\CommissionBoard::class)->call('pay',$com->id);
        $this->assertEquals('paid', $com->fresh()->status);
    }

    public function test_stock_flow(): void {
        Livewire::test(\App\Livewire\Stock\InventoryDashboard::class)->set('name','Whey')->set('price',150)->set('stock',10)->call('save');
        $p = Product::first();
        $this->assertNotNull($p);
        Livewire::test(\App\Livewire\Stock\InventoryDashboard::class)->call('move',$p->id,'out');
        $this->assertEquals(9, $p->fresh()->stock);
    }

    public function test_cash_flow(): void {
        $t = Livewire::test(\App\Livewire\Finance\CashRegisterBoard::class)->call('open');
        $reg = CashRegister::first();
        $this->assertEquals('open', $reg->status);
        Livewire::test(\App\Livewire\Finance\CashRegisterBoard::class)->set('amount',250)->set('description','Venda')->call('add');
        Livewire::test(\App\Livewire\Finance\CashRegisterBoard::class)->call('close');
        $this->assertEquals('closed', $reg->fresh()->status);
        $this->assertEquals(250, (float)$reg->fresh()->closing_balance);
    }

    public function test_financial_plan_teacher_branch_user_flows(): void {
        Livewire::test(\App\Livewire\Finance\FinancialDashboard::class)->set('description','Aluguel')->set('amount',8000)->set('kind','expense')->call('save');
        $this->assertDatabaseHas('financial_transactions',['description'=>'Aluguel']);
        Livewire::test(\App\Livewire\Plans\PlanManager::class)->set('name','Novo Plano')->set('price',149.9)->call('save');
        $p = Plan::where('name','Novo Plano')->first();
        Livewire::test(\App\Livewire\Plans\PlanManager::class)->call('toggle',$p->id);
        $this->assertFalse((bool)$p->fresh()->active);
        Livewire::test(\App\Livewire\Team\TeacherTable::class)->set('name','Novo Prof')->call('save');
        $this->assertDatabaseHas('teachers',['name'=>'Novo Prof']);
        Livewire::test(\App\Livewire\Branches\BranchManager::class)->set('name','Filial 2')->set('code','F-02')->call('save');
        $b = Branch::where('code','F-02')->first();
        $this->assertNotNull($b);
        Livewire::test(\App\Livewire\Users\UserManager::class)->set('name','Recep')->set('email','r@t.test')->set('password','password123')->set('role','receptionist')->call('save');
        $this->assertDatabaseHas('users',['email'=>'r@t.test']);
    }

    public function test_portal_actions(): void {
        $u = User::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'name'=>'Stu','email'=>'stu@t.test','password'=>Hash::make('password'),'role'=>'student','active'=>true]);
        $s = $this->student(['email'=>'stu@t.test']);
        $c = GymClass::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'teacher_id'=>$this->teacher->id,'name'=>'Yoga','capacity'=>10,'starts_at'=>now()->addDay(),'status'=>'open']);
        $inv = Invoice::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'student_id'=>$s->id,'description'=>'M','amount'=>100,'due_date'=>now(),'status'=>'pending']);
        $this->actingAs($u);
        Livewire::test(\App\Livewire\Portal\StudentPortal::class)->call('reserve',$c->id);
        $this->assertDatabaseHas('class_reservations',['gym_class_id'=>$c->id,'student_id'=>$s->id,'status'=>'reserved']);
        Livewire::test(\App\Livewire\Portal\StudentPortal::class)->call('pay',$inv->id);
        $this->assertEquals('paid', $inv->fresh()->status);
    }

    public function test_search_and_notifications(): void {
        $this->student(['name'=>'Buscavel Silva']);
        Livewire::test(\App\Livewire\GlobalSearch::class)->set('q','Buscavel')->assertSee('Buscavel Silva');
        $this->owner->notify(new \App\Notifications\GenericNotification('T','B'));
        Livewire::test(\App\Livewire\NotificationDropdown::class)->assertSee('T')->call('readAll');
        $this->assertEquals(0, $this->owner->unreadNotifications()->count());
    }
}
