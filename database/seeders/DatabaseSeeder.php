<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{Gym, Branch, User, Student, Plan, Membership, Invoice, Payment, Checkin, Exercise, WorkoutPlan, WorkoutSession, WorkoutExercise, PhysicalAssessment, Teacher, GymClass, ClassReservation, Lead, Sale, Product, StockMovement, FinancialTransaction, Commission};
use App\Support\QrToken;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        $faker = \Faker\Factory::create('pt_BR');
        $this->command->info('Seeding GymManager...');
        $exercises = ['Supino reto','Supino inclinado','Agachamento livre','Leg press','Cadeira extensora','Mesa flexora','Puxada alta','Remada curvada','Desenvolvimento','Elevação lateral','Rosca direta','Tríceps pulley','Prancha','Corrida esteira','Bike','Remo','Levantamento terra','Stiff','Hip thrust','Abdominal'];
        $cats = ['Peito','Peito','Pernas','Pernas','Pernas','Pernas','Costas','Costas','Ombros','Ombros','Bíceps','Tríceps','Abdômen','Cardio','Cardio','Cardio','Costas','Pernas','Glúteos','Abdômen'];
        $muscles = $cats;
        // Demo gym + 2 extra
        $gyms = [];
        foreach ([['Iron House','demo'],['Corpo Ativo','corpo'],['Titan Box','titan']] as [$name,$slug]) {
            $gyms[] = Gym::create(['name'=>$name.' Fitness','slug'=>$slug,'email'=>'contato@'.$slug.'.test','phone'=>'(11) 3000-0000','city'=>'São Paulo','state'=>'SP','active'=>true]);
        }
        $demoGym = $gyms[0];
        $branches = [];
        foreach ($gyms as $gi=>$g) {
            $n = $gi===0?3:2;
            for($i=1;$i<=$n;$i++) $branches[] = Branch::create(['gym_id'=>$g->id,'name'=>$g->slug.' Unidade '.$i,'code'=>strtoupper($g->slug).'-0'.$i,'address'=>$faker->address,'phone'=>$faker->phoneNumber,'email'=>'unidade'.$i.'@'.$g->slug.'.test','opening_hours'=>'Seg–Sáb 06:00–22:00','active'=>true]);
        }
        $demoBranches = array_values(array_filter($branches, fn($b)=>$b->gym_id===$demoGym->id));
        // Users
        $demo = User::create(['gym_id'=>$demoGym->id,'branch_id'=>$demoBranches[0]->id,'name'=>'Demo Owner','email'=>'demo@gymmanager.test','password'=>Hash::make('password'),'role'=>'owner','active'=>true,'email_verified_at'=>now()]);
        $roles = ['manager','receptionist','instructor','personal','financial','sales'];
        foreach ($branches as $b) foreach ($roles as $r) {
            User::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'name'=>$faker->name,'email'=>$faker->unique()->safeEmail,'password'=>Hash::make('password'),'role'=>$r,'phone'=>$faker->phoneNumber,'active'=>true,'email_verified_at'=>now()]);
        }
        // Teachers
        $specs = ['Musculação','Funcional','CrossFit','Pilates','Yoga','Cardio','Personal'];
        $teachers = [];
        foreach ($branches as $b) for($i=0;$i<4;$i++) $teachers[] = Teacher::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'name'=>$faker->name,'cpf'=>$faker->cpf(false),'cref'=>'CREF '.rand(10000,99999).'-G/SP','email'=>$faker->safeEmail,'phone'=>$faker->phoneNumber,'specialty'=>$specs[array_rand($specs)],'commission_rate'=>rand(5,30),'status'=>'active','hire_date'=>$faker->date()]);
        // Plans
        $planDefs = [['Mensal Flex',99.9,'monthly',1],['Trimestral Plus',259,'quarterly',3],['Semestral Pro',489,'semiannual',6],['Anual Black',899,'annual',12],['Experimental 7 dias',29.9,'monthly',1],['Corporativo',79.9,'monthly',1]];
        $plansByGym = [];
        foreach ($gyms as $g) foreach ($planDefs as [$n,$p,$per,$d]) $plansByGym[$g->id][] = Plan::create(['gym_id'=>$g->id,'name'=>$n,'description'=>'Acesso completo + avaliação','price'=>$p,'period'=>$per,'duration_months'=>$d,'signup_fee'=>49.9,'all_branches'=>true,'recurring'=>true,'active'=>true]);
        // Exercises (global, gym_id null for catalog + per gym)
        foreach ($exercises as $i=>$e) Exercise::create(['gym_id'=>null,'name'=>$e,'category'=>$cats[$i],'muscle_group'=>$muscles[$i],'equipment'=>'Livre','active'=>true]);
        $allEx = Exercise::all();
        // Students + memberships + invoices + payments + checkins + workouts + assessments
        $statuses = ['active','active','active','active','inactive','suspended','pending'];
        $total = 0;
        foreach ($branches as $b) {
            $count = $b->gym_id===$demoGym->id ? 150 : 60;
            for($i=0;$i<$count;$i++){
                $code = 'GM'.strtoupper(Str::random(6));
                $s = Student::withoutGlobalScopes()->create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'name'=>$faker->name,'cpf'=>$faker->cpf(false),'birth_date'=>$faker->date(),'gender'=>$faker->randomElement(['masculino','feminino']),'email'=>$faker->unique()->safeEmail,'phone'=>$faker->phoneNumber,'whatsapp'=>$faker->phoneNumber,'address'=>$faker->streetAddress,'city'=>'São Paulo','state'=>'SP','status'=>$statuses[array_rand($statuses)],'member_code'=>$code,'qr_token'=>QrToken::make($code),'goal'=>$faker->randomElement(['Hipertrofia','Emagrecimento','Condicionamento'])]);
                $total++;
                if ($s->status==='active' && rand(0,100)<85) {
                    $plan = $plansByGym[$b->gym_id][array_rand($plansByGym[$b->gym_id])];
                    $start = $faker->dateTimeBetween('-11 months','now');
                    $m = Membership::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'student_id'=>$s->id,'plan_id'=>$plan->id,'starts_at'=>$start,'ends_at'=>(clone $start)->modify('+'.$plan->duration_months.' months'),'price'=>$plan->price,'status'=>'active','payment_method'=>'pix']);
                    for($k=0;$k<rand(1,4);$k++){
                        $due = (clone $start)->modify("+$k months");
                        $paid = $due < new \DateTime('-1 month') ? 'paid' : $faker->randomElement(['paid','paid','pending','overdue']);
                        $inv = Invoice::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'student_id'=>$s->id,'membership_id'=>$m->id,'description'=>'Mensalidade '.$plan->name,'amount'=>$plan->price,'due_date'=>$due,'status'=>$paid,'paid_at'=>$paid==='paid'?$due:null,'reference_month'=>$due->format('Y-m')]);
                        if($paid==='paid') Payment::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'invoice_id'=>$inv->id,'student_id'=>$s->id,'amount'=>$plan->price,'method'=>$faker->randomElement(['pix','cash','credit_card','debit_card']),'status'=>'paid','paid_at'=>$due,'receipt'=>'RC'.$inv->id.rand(100,999)]);
                    }
                    if(rand(0,100)<60) for($c=0;$c<rand(1,8);$c++) Checkin::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'student_id'=>$s->id,'method'=>'search','checked_in_at'=>$faker->dateTimeBetween('-14 days','now'),'allowed'=>true]);
                    if(rand(0,100)<50){
                        $wp = WorkoutPlan::create(['gym_id'=>$b->gym_id,'student_id'=>$s->id,'coach_id'=>$faker->randomElement($teachers)->id,'name'=>'Ficha '.$faker->randomElement(['A','B','C']).' — '.$s->goal,'goal'=>$s->goal,'starts_at'=>now()->subDays(30),'expires_at'=>now()->addDays(30),'status'=>'active']);
                        foreach (['Treino A','Treino B'] as $sessName) {
                            $sess = WorkoutSession::create(['workout_plan_id'=>$wp->id,'name'=>$sessName,'order'=>0]);
                            foreach ($allEx->random(4) as $ex) WorkoutExercise::create(['workout_session_id'=>$sess->id,'exercise_id'=>$ex->id,'sets'=>rand(3,4),'reps'=>(string)rand(8,15),'load'=>rand(10,80).'kg','rest_seconds'=>60]);
                        }
                    }
                    if(rand(0,100)<40) PhysicalAssessment::create(['gym_id'=>$b->gym_id,'student_id'=>$s->id,'teacher_id'=>$faker->randomElement($teachers)->id,'weight'=>$faker->randomFloat(1,55,110),'height'=>$faker->randomFloat(1,155,195),'body_fat'=>$faker->randomFloat(1,8,35),'waist'=>$faker->randomFloat(1,65,110),'measured_at'=>$faker->dateTimeBetween('-6 months','now')]);
                }
            }
        }
        // Classes + reservations
        $classNames = ['Spinning','Yoga','Pilates','CrossFit','Funcional','Zumba','Boxe','Alongamento'];
        foreach ($branches as $b) for($i=0;$i<10;$i++){
            $c = GymClass::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'teacher_id'=>$faker->randomElement($teachers)->id,'name'=>$faker->randomElement($classNames),'room'=>'Sala '.rand(1,4),'capacity'=>rand(10,30),'starts_at'=>$faker->dateTimeBetween('now','+14 days'),'duration_min'=>60,'status'=>'open']);
            foreach (Student::withoutGlobalScopes()->where('branch_id',$b->id)->inRandomOrder()->limit(rand(0,12))->get() as $s) {
                ClassReservation::firstOrCreate(['gym_class_id'=>$c->id,'student_id'=>$s->id],['gym_id'=>$b->gym_id,'status'=>'reserved']);
            }
        }
        // Leads + sales + products + financial
        foreach ($branches as $b) {
            for($i=0;$i<25;$i++) \App\Models\Lead::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'name'=>$faker->name,'phone'=>$faker->phoneNumber,'email'=>$faker->safeEmail,'source'=>$faker->randomElement(['Instagram','Indicação','Google','Passante']),'interest'=>$faker->randomElement(['Musculação','CrossFit','Pilates']),'status'=>$faker->randomElement(['new','contacted','interested','trial','proposal','negotiation','won','lost']),'estimated_value'=>99.9]);
            for($i=0;$i<20;$i++){ $st = Student::withoutGlobalScopes()->where('branch_id',$b->id)->inRandomOrder()->first(); Sale::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'student_id'=>$st?->id,'seller_id'=>User::where('gym_id',$b->gym_id)->first()?->id,'kind'=>$faker->randomElement(['plan','product','personal']),'description'=>'Venda '.$faker->word,'amount'=>$faker->randomFloat(2,29,500),'method'=>'pix','status'=>'paid','sold_at'=>$faker->dateTimeBetween('-6 months','now')]); }
            foreach (['Whey 900g','Creatina 300g','Camiseta Dry','Garrafa 750ml','Barra proteica','Luva treino'] as $pn) {
                $p = Product::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'name'=>$pn,'price'=>$faker->randomFloat(2,15,250),'cost'=>10,'stock'=>rand(0,40),'min_stock'=>5,'active'=>true]);
                StockMovement::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'product_id'=>$p->id,'type'=>'in','quantity'=>$p->stock,'reason'=>'Estoque inicial']);
            }
            FinancialTransaction::create(['gym_id'=>$b->gym_id,'branch_id'=>$b->id,'kind'=>'expense','category'=>'Aluguel','description'=>'Aluguel unidade','amount'=>8000,'due_date'=>now(),'paid_at'=>now(),'status'=>'paid']);
            Commission::create(['gym_id'=>$b->gym_id,'seller_id'=>User::where('gym_id',$b->gym_id)->first()?->id,'source'=>'membership','amount'=>150,'rate'=>10,'status'=>'pending']);
        }
        $this->command->info("Seeded {$total} students. Demo: demo@gymmanager.test / password");
    }
}
