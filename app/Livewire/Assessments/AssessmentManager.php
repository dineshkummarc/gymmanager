<?php
namespace App\Livewire\Assessments;
use Livewire\Component;
use App\Models\{PhysicalAssessment, Student, Teacher};
class AssessmentManager extends Component {
    public $student_id=null; public $weight=0; public $height=0; public $body_fat=null; public $waist=null; public $notes='';
    public function save(){
        $this->validate(['student_id'=>'required','weight'=>'required|numeric','height'=>'required|numeric']);
        PhysicalAssessment::create(['student_id'=>$this->student_id,'teacher_id'=>Teacher::first()?->id,'weight'=>$this->weight,'height'=>$this->height,'body_fat'=>$this->body_fat,'waist'=>$this->waist,'notes'=>$this->notes,'measured_at'=>now()]);
        $this->reset(['weight','height','body_fat','waist','notes']); session()->flash('ok','Avaliação registrada.');
    }
    public function render(){ return view('livewire.assessments.manager',['students'=>Student::orderBy('name')->limit(200)->get(),'items'=>PhysicalAssessment::with('student')->latest()->paginate(10)])->layout('layouts.app'); }
}
