<?php
namespace App\Jobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Membership;
class GenerateInvoicesJob implements ShouldQueue {
    use Queueable;
    public function handle(): void {
        Membership::where('status','active')->where('ends_at','>',now())->chunk(200, function($ms){
            foreach($ms as $m){
                $exists = $m->invoices()->where('reference_month', now()->addMonth()->format('Y-m'))->exists();
                if(!$exists){
                    $m->invoices()->create(['gym_id'=>$m->gym_id,'branch_id'=>$m->branch_id,'student_id'=>$m->student_id,
                        'description'=>'Mensalidade '.$m->plan->name.' — '.now()->addMonth()->format('m/Y'),
                        'amount'=>$m->price,'due_date'=>now()->addMonth()->day(10)->toDateString(),
                        'status'=>'pending','reference_month'=>now()->addMonth()->format('Y-m')]);
                }
            }
        });
    }
}
