<?php
namespace App\Livewire\Finance;
use Livewire\Component;
use App\Models\{CashRegister, CashMovement};
class CashRegisterBoard extends Component {
    public $register=null; public $amount=0; public string $kind='in'; public string $description='';
    public function mount(){ $this->register = CashRegister::where('status','open')->latest()->first(); }
    public function open(){ $this->register = CashRegister::create(['user_id'=>auth()->id(),'branch_id'=>auth()->user()->branch_id,'opening_balance'=>0,'status'=>'open']); }
    public function add(){
        $this->register->movements()->create(['kind'=>$this->kind,'amount'=>$this->amount,'description'=>$this->description,'user_id'=>auth()->id()]);
        $this->reset(['amount','description']);
    }
    public function close(){ $this->register->update(['status'=>'closed','closing_balance'=>$this->register->balance(),'closed_at'=>now()]); $this->register=null; }
    public function render(){ return view('livewire.finance.cash-register-board')->layout('layouts.app'); }
}
