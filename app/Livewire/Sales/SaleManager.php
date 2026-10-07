<?php
namespace App\Livewire\Sales;
use Livewire\Component; use Livewire\WithPagination;
use App\Models\{Sale, Student, Commission};
class SaleManager extends Component {
    use WithPagination;
    public $student_id=null; public string $kind='plan'; public string $description=''; public $amount=0; public string $method='pix';
    public function save(){
        $this->validate(['description'=>'required','amount'=>'required|numeric|min:0.01']);
        $s = Sale::create(['branch_id'=>auth()->user()->branch_id,'student_id'=>$this->student_id,'seller_id'=>auth()->id(),
            'kind'=>$this->kind,'description'=>$this->description,'amount'=>$this->amount,'method'=>$this->method,'status'=>'paid','sold_at'=>now()]);
        Commission::create(['branch_id'=>$s->branch_id,'seller_id'=>auth()->id(),'source'=>'sale','source_id'=>$s->id,
            'amount'=>round($this->amount*0.10,2),'rate'=>10,'status'=>'pending']);
        \App\Models\ActivityLog::create(['gym_id'=>$s->gym_id,'user_id'=>auth()->id(),'action'=>'sale.created','entity'=>'sale','entity_id'=>$s->id,'ip'=>request()->ip()]);
        $this->reset(['description','amount']); session()->flash('ok','Venda registrada (+ comissão de 10%).');
    }
    public function render(){
        return view('livewire.sales.manager',[
            'items'=>Sale::with('student')->orderBy('sold_at','desc')->paginate(12),
            'students'=>Student::orderBy('name')->limit(300)->get(),
            'total'=>(float) Sale::where('status','paid')->whereMonth('sold_at',now()->month)->sum('amount'),
        ])->layout('layouts.app');
    }
}
