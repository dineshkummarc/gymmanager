<?php
namespace App\Livewire\Plans;
use Livewire\Component;
use App\Models\Plan;
class PlanManager extends Component {
    public string $name=''; public $price=99.9; public string $period='monthly';
    public function save(){ $this->validate(['name'=>'required']); Plan::create(['name'=>$this->name,'price'=>$this->price,'period'=>$this->period,'duration_months'=>\App\Enums\PlanPeriod::from($this->period)->months()]); $this->reset(['name']); }
    public function toggle($id){ $p=Plan::findOrFail($id); $p->update(['active'=>!$p->active]); }
    public function render(){ return view('livewire.plans.plan-manager',['plans'=>Plan::orderBy('price')->get()])->layout('layouts.app'); }
}
