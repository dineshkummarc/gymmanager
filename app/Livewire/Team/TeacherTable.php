<?php
namespace App\Livewire\Team;
use Livewire\Component;
use App\Models\Teacher;
class TeacherTable extends Component {
    public string $name=''; public string $specialty='Musculação';
    public function save(){ $this->validate(['name'=>'required']); Teacher::create(['name'=>$this->name,'specialty'=>$this->specialty,'branch_id'=>auth()->user()->branch_id]); $this->reset('name'); }
    public function render(){ return view('livewire.team.teacher-table',['teachers'=>Teacher::orderBy('name')->paginate(12)])->layout('layouts.app'); }
}
