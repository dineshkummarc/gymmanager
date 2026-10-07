<?php
namespace App\Jobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Invoice;
use App\Notifications\InvoiceDueNotification;
class SendDueRemindersJob implements ShouldQueue {
    use Queueable;
    public function handle(): void {
        Invoice::with('student')->whereIn('status',['pending','overdue'])
            ->whereBetween('due_date',[now()->toDateString(), now()->addDays(3)->toDateString()])
            ->chunk(200, fn($invs)=>$invs->each(fn($i)=>$i->student?->user_id && \App\Models\User::find($i->student->user_id)?->notify(new InvoiceDueNotification($i))));
    }
}
