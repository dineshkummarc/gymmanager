<?php
namespace App\Livewire\Crm;
use Livewire\Component;
use App\Models\Lead;
class LeadPipeline extends Component {
    public string $name=''; public string $phone=''; public $dragOver=null;
    public array $columns = ['new'=>'Novo','contacted'=>'Contatado','interested'=>'Interessado','trial'=>'Experimental','proposal'=>'Proposta','negotiation'=>'Negociação','won'=>'Ganho','lost'=>'Perdido'];
    public function add(){
        $this->validate(['name'=>'required|min:2','phone'=>'required']);
        Lead::create(['name'=>$this->name,'phone'=>$this->phone,'status'=>'new','branch_id'=>auth()->user()->branch_id]);
        $this->reset(['name','phone']); session()->flash('ok','Lead criado.');
    }
    public function move($id,$status){ Lead::findOrFail($id)->update(['status'=>$status]); }
    public function convert($id){
        $lead = Lead::findOrFail($id);
        $code = 'GM'.strtoupper(\Illuminate\Support\Str::random(6));
        $s = \App\Models\Student::create(['name'=>$lead->name,'phone'=>$lead->phone,'email'=>$lead->email,'branch_id'=>$lead->branch_id,'status'=>'active','member_code'=>$code,'qr_token'=>\App\Support\QrToken::make($code),'source'=>'lead']);
        $lead->update(['status'=>'won','converted_student_id'=>$s->id]);
        session()->flash('ok','Lead convertido em aluno.');
    }
    public function render(){ return view('livewire.crm.lead-pipeline',['grouped'=>Lead::orderBy('updated_at','desc')->get()->groupBy('status')])->layout('layouts.app'); }
}
