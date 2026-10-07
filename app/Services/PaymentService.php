<?php
namespace App\Services;
use App\Models\{Invoice, Payment, CashRegister, Commission};
use Illuminate\Support\Facades\DB;
class PaymentService {
    public function pay(Invoice $inv, array $d): Payment {
        return DB::transaction(function () use ($inv,$d) {
            $open = CashRegister::where('status','open')->where('branch_id',$inv->branch_id)->first();
            $p = Payment::create(['branch_id'=>$inv->branch_id,'invoice_id'=>$inv->id,'student_id'=>$inv->student_id,
                'user_id'=>auth()->id(),'amount'=>$d['amount'],'method'=>$d['method']??'pix','status'=>'paid',
                'paid_at'=>now(),'receipt'=>'RC'.now()->format('YmdHis').$inv->id,'notes'=>$d['notes']??null,
                'cash_register_id'=>$open?->id]);
            $paid = $inv->paidTotal();
            $inv->update(['status'=> $paid >= $inv->total() - 0.01 ? 'paid' : 'partial','paid_at'=> $paid >= $inv->total()-0.01 ? now() : null]);
            if (($m=$inv->membership) && $m->seller_id) {
                Commission::create(['branch_id'=>$inv->branch_id,'seller_id'=>$m->seller_id,'source'=>'membership','source_id'=>$m->id,
                    'amount'=>round($p->amount*0.10,2),'rate'=>10,'status'=>'pending']);
            }
            return $p;
        });
    }
}
