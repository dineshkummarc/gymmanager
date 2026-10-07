<?php
namespace App\Jobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Invoice;
use App\Support\Money;
class MarkOverdueInvoicesJob implements ShouldQueue {
    use Queueable;
    public function handle(): void {
        Invoice::where('status','pending')->where('due_date','<',now()->toDateString())->chunk(200, function($invs){
            foreach($invs as $i){
                $days = now()->diffInDays($i->due_date);
                $i->update(['status'=>'overdue','fine'=>Money::fine((float)$i->amount,$days)]);
            }
        });
    }
}
