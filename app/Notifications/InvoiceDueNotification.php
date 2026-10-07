<?php
namespace App\Notifications;
use Illuminate\Notifications\Notification;
class InvoiceDueNotification extends Notification {
    public function __construct(public $invoice) {}
    public function via($n): array { return ['database']; }
    public function toArray($n): array {
        return ['title'=>'Mensalidade a vencer','body'=>'Vencimento '.$this->invoice->due_date->format('d/m/Y').' — R$ '.number_format($this->invoice->total(),2,',','.'),
            'url'=>'/student-portal/billing','invoice_id'=>$this->invoice->id];
    }
}
