<?php
namespace App\Livewire\Contracts;
use Livewire\Component; use Livewire\WithPagination;
use App\Models\Contract;
class ContractManager extends Component {
    use WithPagination;
    public string $status='';
    public function sign($id){
        $c = Contract::findOrFail($id);
        $c->update(['status'=>'active','signed_at'=>now()]);
        \App\Models\ActivityLog::create(['gym_id'=>$c->gym_id,'user_id'=>auth()->id(),'action'=>'contract.signed','entity'=>'contract','entity_id'=>$c->id,'ip'=>request()->ip()]);
        session()->flash('ok','Contrato assinado e ativado.');
    }
    public function newVersion($id){
        $c = Contract::findOrFail($id);
        Contract::create(['membership_id'=>$c->membership_id,'student_id'=>$c->student_id,'title'=>$c->title,'content'=>$c->content,'version'=>$c->version+1,'status'=>'draft']);
        session()->flash('ok','Nova versão (v'.($c->version+1).') criada como rascunho.');
    }
    public function render(){
        $q = Contract::with(['student','membership.plan'])->orderBy('created_at','desc');
        if($this->status) $q->where('status',$this->status);
        return view('livewire.contracts.manager',['items'=>$q->paginate(12)])->layout('layouts.app');
    }
}
