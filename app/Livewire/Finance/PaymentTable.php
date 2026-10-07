<?php
namespace App\Livewire\Finance;
use Livewire\Component; use Livewire\WithPagination;
use App\Models\Invoice;
class PaymentTable extends Component {
    use WithPagination;
    public string $filter='all'; public string $search='';
    public function markPaid($id){ (new \App\Services\PaymentService)->pay(Invoice::findOrFail($id),['amount'=>Invoice::findOrFail($id)->balance(),'method'=>'pix']); session()->flash('ok','Pagamento registrado.'); }
    public function render(){
        $q = Invoice::with(['student','membership.plan'])->orderBy('due_date');
        if($this->filter==='today') $q->whereDate('due_date',today());
        elseif($this->filter==='week') $q->whereBetween('due_date',[now(),now()->addWeek()]);
        elseif($this->filter==='overdue') $q->whereIn('status',['pending','overdue'])->where('due_date','<',now()->toDateString());
        elseif($this->filter==='paid') $q->where('status','paid');
        elseif($this->filter==='pending') $q->whereIn('status',['pending','partial']);
        if($this->search) $q->whereHas('student',fn($w)=>$w->where('name','like',"%{$this->search}%"));
        return view('livewire.finance.payment-table',['invoices'=>$q->paginate(12)])->layout('layouts.app');
    }
}
