<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\{Gym, Branch, User, Student, Plan, Membership, Invoice};
use App\Services\{AccessControlService, MembershipService, PaymentService};
use App\Support\QrToken;
class GymManagerTest extends TestCase {
    use RefreshDatabase;
    protected Gym $gym; protected Gym $other; protected Branch $branch; protected User $owner;
    protected function setUp(): void {
        parent::setUp();
        $this->gym = Gym::create(['name'=>'Test Gym','slug'=>'test','active'=>true]);
        $this->other = Gym::create(['name'=>'Other Gym','slug'=>'other','active'=>true]);
        $this->branch = Branch::create(['gym_id'=>$this->gym->id,'name'=>'Matriz','code'=>'T-01','active'=>true]);
        $this->owner = User::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'name'=>'Owner','email'=>'o@t.test','password'=>Hash::make('password'),'role'=>'owner','active'=>true]);
    }
    public function test_login_works(): void {
        $this->post('/logout');
        $r = $this->post('/login', []);
        $this->assertTrue(true);
        $this->assertTrue(\Illuminate\Support\Facades\Auth::attempt(['email'=>'o@t.test','password'=>'password','active'=>1]));
    }
    public function test_tenant_isolation(): void {
        $code='GM'.strtoupper(\Illuminate\Support\Str::random(6));
        $s = Student::create(['gym_id'=>$this->other->id,'name'=>'Estranho','status'=>'active','member_code'=>$code,'qr_token'=>QrToken::make($code)]);
        $this->actingAs($this->owner);
        app()->instance('currentGymId', $this->gym->id);
        $this->assertFalse(Student::all()->contains($s));
    }
    public function test_membership_creates_invoices(): void {
        $this->actingAs($this->owner);
        app()->instance('currentGymId', $this->gym->id);
        $code='GM'.strtoupper(\Illuminate\Support\Str::random(6));
        $s = Student::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'name'=>'Aluno','status'=>'active','member_code'=>$code,'qr_token'=>QrToken::make($code)]);
        $plan = Plan::create(['gym_id'=>$this->gym->id,'name'=>'Mensal','price'=>100,'period'=>'monthly','duration_months'=>1]);
        $m = (new MembershipService)->create($s, ['branch_id'=>$this->branch->id,'plan_id'=>$plan->id,'starts_at'=>now()->toDateString(),'price'=>100,'installments'=>2]);
        $this->assertEquals(2, $m->invoices()->count());
    }
    public function test_payment_settles_invoice(): void {
        $this->actingAs($this->owner);
        app()->instance('currentGymId', $this->gym->id);
        $code='GM'.strtoupper(\Illuminate\Support\Str::random(6));
        $s = Student::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'name'=>'Pagador','status'=>'active','member_code'=>$code,'qr_token'=>QrToken::make($code)]);
        $inv = Invoice::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'student_id'=>$s->id,'description'=>'Mensalidade','amount'=>100,'due_date'=>now(),'status'=>'pending']);
        (new PaymentService)->pay($inv, ['amount'=>100,'method'=>'pix']);
        $this->assertEquals('paid', $inv->fresh()->status);
    }
    public function test_access_denied_when_overdue(): void {
        $this->actingAs($this->owner);
        app()->instance('currentGymId', $this->gym->id);
        $code='GM'.strtoupper(\Illuminate\Support\Str::random(6));
        $s = Student::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'name'=>'Devedor','status'=>'active','member_code'=>$code,'qr_token'=>QrToken::make($code)]);
        $plan = Plan::create(['gym_id'=>$this->gym->id,'name'=>'Mensal','price'=>100,'period'=>'monthly','duration_months'=>12,'all_branches'=>true]);
        Membership::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'student_id'=>$s->id,'plan_id'=>$plan->id,'starts_at'=>now()->subMonth(),'ends_at'=>now()->addYear(),'price'=>100,'status'=>'active']);
        Invoice::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'student_id'=>$s->id,'description'=>'Vencida','amount'=>100,'due_date'=>now()->subDays(10),'status'=>'pending']);
        $r = (new AccessControlService)->validate($s->fresh());
        $this->assertFalse($r['allowed']);
        $this->assertStringContainsString('Mensalidade', $r['reason']);
    }
    public function test_student_policy_blocks_other_gym(): void {
        $code='GM'.strtoupper(\Illuminate\Support\Str::random(6));
        $s = Student::withoutGlobalScopes()->create(['gym_id'=>$this->other->id,'name'=>'X','status'=>'active','member_code'=>$code,'qr_token'=>QrToken::make($code)]);
        $this->assertFalse((new \App\Policies\StudentPolicy)->view($this->owner, $s));
    }
}

