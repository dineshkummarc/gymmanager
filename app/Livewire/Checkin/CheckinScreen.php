<?php
namespace App\Livewire\Checkin;
use Livewire\Component;
use App\Models\{Student, Branch};
use App\Services\CheckinService;
class CheckinScreen extends Component {
    public string $search=''; public $results=[]; public $last=null; public $branches; public $branchId=null;
    public function mount(){ $this->branches=Branch::orderBy('name')->get(); $this->branchId=auth()->user()->branch_id ?? $this->branches->first()?->id; }
    public function updatedSearch(){
        $this->results = strlen($this->search)>=2
            ? Student::search($this->search)->with('activeMembership.plan')->limit(8)->get() : [];
    }
    public function checkin($id){
        $s = Student::findOrFail($id);
        $this->last = (new CheckinService)->checkin($s,'search',$this->branchId);
        $this->last->load('student.activeMembership.plan');
        $this->search=''; $this->results=[];
    }
    public function render(){ return view('livewire.checkin.checkin-screen')->layout('layouts.app'); }
}
