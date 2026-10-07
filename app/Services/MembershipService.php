<?php
namespace App\Services;
use App\Models\{Membership, Invoice, Contract, Student};
use Illuminate\Support\Facades\DB;
use App\Enums\{MembershipStatus, InvoiceStatus};
class MembershipService {
    public function create(Student $s, array $d): Membership {
        return DB::transaction(function () use ($s,$d) {
            $ends = now()->parse($d['starts_at'])->addMonths((int)($d['duration_months'] ?? 1));
            $m = Membership::create([
                'branch_id'=>$d['branch_id']??$s->branch_id,'student_id'=>$s->id,'plan_id'=>$d['plan_id'],
                'seller_id'=>$d['seller_id']??auth()->id(),'coach_id'=>$d['coach_id']??null,
                'starts_at'=>$d['starts_at'],'ends_at'=>$ends->toDateString(),
                'price'=>$d['price'],'discount'=>$d['discount']??0,'status'=>MembershipStatus::Active->value,
                'payment_method'=>$d['payment_method']??null,'notes'=>$d['notes']??null,
            ]);
            $months = max(1, (int)($d['installments'] ?? 1));
            $each = round($m->total()/$months,2);
            for($i=0;$i<$months;$i++){
                Invoice::create(['branch_id'=>$m->branch_id,'student_id'=>$s->id,'membership_id'=>$m->id,
                    'description'=>'Mensalidade '.($i+1).'/'.$months.' — '.$m->plan->name,
                    'amount'=>$each,'due_date'=>now()->parse($d['starts_at'])->addMonths($i)->toDateString(),
                    'status'=>InvoiceStatus::Pending->value,'reference_month'=>now()->parse($d['starts_at'])->addMonths($i)->format('Y-m')]);
            }
            Contract::create(['membership_id'=>$m->id,'student_id'=>$s->id,'title'=>'Contrato de prestação de serviços',
                'content'=>'Contrato gerado automaticamente.','status'=>'active','signed_at'=>now()]);
            return $m;
        });
    }
    public function cancel(Membership $m): void {
        $m->update(['status'=>MembershipStatus::Cancelled->value]);
        $m->invoices()->whereIn('status',['pending'])->update(['status'=>InvoiceStatus::Cancelled->value]);
    }
}
