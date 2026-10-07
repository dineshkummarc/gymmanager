<?php
namespace App\Livewire\Branches;
use Livewire\Component;
use App\Models\Branch;
class BranchManager extends Component {
    public string $name=''; public string $code=''; public string $address=''; public string $phone=''; public string $opening_hours='Seg–Sáb 06:00–22:00';
    public function save(){
        $this->validate(['name'=>'required','code'=>'required']);
        Branch::create(['gym_id'=>auth()->user()->gym_id,'name'=>$this->name,'code'=>strtoupper($this->code),'address'=>$this->address,'phone'=>$this->phone,'opening_hours'=>$this->opening_hours,'active'=>true]);
        $this->reset(['name','code','address','phone']); session()->flash('ok','Unidade criada.');
    }
    public function toggle($id){ $b=Branch::findOrFail($id); $b->update(['active'=>!$b->active]); }
    public function render(){ return view('livewire.branches.manager',['items'=>Branch::withCount(['students'])->orderBy('name')->get()])->layout('layouts.app'); }
}
