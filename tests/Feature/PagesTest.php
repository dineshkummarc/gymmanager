<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{Gym, Branch, User, Student, Plan, Teacher};
use App\Support\QrToken;

class PagesTest extends TestCase {
    use RefreshDatabase;
    protected User $owner; protected Student $student;

    protected function setUp(): void {
        parent::setUp();
        $gym = Gym::create(['name'=>'G','slug'=>'g','active'=>true]);
        $branch = Branch::create(['gym_id'=>$gym->id,'name'=>'M','code'=>'M-01','active'=>true]);
        $this->owner = User::create(['gym_id'=>$gym->id,'branch_id'=>$branch->id,'name'=>'O','email'=>'o@t.test','password'=>Hash::make('password'),'role'=>'owner','active'=>true]);
        $code = 'GM'.strtoupper(Str::random(6));
        $this->student = Student::create(['gym_id'=>$gym->id,'branch_id'=>$branch->id,'name'=>'Aluno Teste','status'=>'active','member_code'=>$code,'qr_token'=>QrToken::make($code)]);
        Plan::create(['gym_id'=>$gym->id,'name'=>'Mensal','price'=>99.9,'period'=>'monthly','duration_months'=>1]);
        Teacher::create(['gym_id'=>$gym->id,'branch_id'=>$branch->id,'name'=>'Prof','specialty'=>'Musculação','status'=>'active']);
    }

    public function test_guest_pages_render(): void {
        $this->get('/')->assertRedirect('/login');
        foreach (['/login','/forgot-password','/reset-password/abc123'] as $url) {
            $this->get($url)->assertOk($url);
        }
    }

    public function test_all_authenticated_pages_render(): void {
        $this->actingAs($this->owner);
        $s = $this->student->id;
        $urls = ['/dashboard','/reception','/teacher','/students','/students/create',"/students/{$s}","/students/{$s}/edit",
            '/plans','/memberships','/contracts','/checkin','/checkins','/payments','/cash','/financial',
            '/workouts','/assessments','/classes','/leads','/teachers','/sales','/commissions','/inventory',
            '/branches','/users','/audit','/reports','/student-portal'];
        foreach ($urls as $url) {
            $this->get($url)->assertOk($url);
        }
    }

    public function test_exports_download_csv(): void {
        $this->actingAs($this->owner);
        foreach (['students','invoices','payments','checkins'] as $t) {
            $r = $this->get("/reports/export/{$t}");
            $r->assertOk($t);
            $this->assertStringContainsString('text/csv', $r->headers->get('Content-Type'));
        }
        $this->get('/reports/export/nope')->assertNotFound();
    }

    public function test_logout_works(): void {
        $this->actingAs($this->owner)->post('/logout')->assertRedirect('/login');
    }
}
