<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\{Gym, Branch, User, Student, Plan, Membership, Invoice};
use App\Services\{AccessControlService, MembershipService, PaymentService};
use App\Support\QrToken;
class GymManagerExtendedTest extends GymManagerTest {
    public function test_membership_cancel_voids_pending_invoices(): void {
        $this->actingAs($this->owner);
        app()->instance('currentGymId', $this->gym->id);
        $code='GM'.strtoupper(\Illuminate\Support\Str::random(6));
        $s = Student::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'name'=>'Cancel','status'=>'active','member_code'=>$code,'qr_token'=>QrToken::make($code)]);
        $plan = Plan::create(['gym_id'=>$this->gym->id,'name'=>'M','price'=>100,'period'=>'monthly','duration_months'=>1]);
        $m = (new MembershipService)->create($s, ['branch_id'=>$this->branch->id,'plan_id'=>$plan->id,'starts_at'=>now()->toDateString(),'price'=>100,'installments'=>1]);
        (new MembershipService)->cancel($m);
        $this->assertEquals('cancelled', $m->fresh()->status);
        $this->assertEquals(0, $m->invoices()->where('status','pending')->count());
    }
    public function test_sale_creates_commission(): void {
        $this->actingAs($this->owner);
        app()->instance('currentGymId', $this->gym->id);
        $sale = \App\Models\Sale::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'seller_id'=>$this->owner->id,'kind'=>'product','description'=>'Whey','amount'=>200,'method'=>'pix','status'=>'paid','sold_at'=>now()]);
        \App\Models\Commission::create(['gym_id'=>$this->gym->id,'branch_id'=>$this->branch->id,'seller_id'=>$this->owner->id,'source'=>'sale','source_id'=>$sale->id,'amount'=>20,'rate'=>10,'status'=>'pending']);
        $this->assertEquals(20, (float)\App\Models\Commission::where('source_id',$sale->id)->first()->amount);
    }
    public function test_tenant_scope_hides_other_gym_invoices(): void {
        $this->actingAs($this->owner);
        app()->instance('currentGymId', $this->gym->id);
        $code='GM'.strtoupper(\Illuminate\Support\Str::random(6));
        $s = Student::withoutGlobalScopes()->create(['gym_id'=>$this->other->id,'name'=>'Z','status'=>'active','member_code'=>$code,'qr_token'=>QrToken::make($code)]);
        \App\Models\Invoice::withoutGlobalScopes()->create(['gym_id'=>$this->other->id,'student_id'=>$s->id,'description'=>'X','amount'=>50,'due_date'=>now(),'status'=>'pending']);
        $this->assertEquals(0, Invoice::count());
    }
}
