<?php
namespace App\Livewire\Finance;
use Livewire\Component;
use App\Models\{FinancialTransaction, Payment};
class FinancialDashboard extends Component {
    public string $description=''; public $amount=0; public string $kind='income'; public string $category='Mensalidades';
    public function save(){ $this->validate(['description'=>'required','amount'=>'required|numeric|min:0.01']); FinancialTransaction::create(['description'=>$this->description,'amount'=>$this->amount,'kind'=>$this->kind,'category'=>$this->category,'due_date'=>now(),'paid_at'=>now(),'status'=>'paid','branch_id'=>auth()->user()->branch_id]); $this->reset(['description','amount']); }
    public function render(){
        $in = (float) FinancialTransaction::where('kind','income')->where('status','paid')->sum('amount') + (float) Payment::where('status','paid')->sum('amount');
        $out = (float) FinancialTransaction::where('kind','expense')->where('status','paid')->sum('amount');
        return view('livewire.finance.financial-dashboard',['in'=>$in,'out'=>$out,'profit'=>$in-$out,'txs'=>FinancialTransaction::latest()->paginate(10)])->layout('layouts.app');
    }
}
