<?php
namespace App\Livewire\Classes;
use Livewire\Component;
use App\Models\{GymClass, Teacher};
class ClassCalendar extends Component {
    public $classes; public string $name=''; public $teacher_id=null; public string $starts_at=''; public int $capacity=20;
    public function mount(){ $this->load(); }
    public function load(){ $this->classes = GymClass::with('teacher')->where('starts_at','>=',now()->subDay())->orderBy('starts_at')->limit(50)->get(); }
    public function save(){
        $this->validate(['name'=>'required','starts_at'=>'required']);
        GymClass::create(['name'=>$this->name,'teacher_id'=>$this->teacher_id,'starts_at'=>$this->starts_at,'capacity'=>$this->capacity,'status'=>'open','branch_id'=>auth()->user()->branch_id]);
        $this->reset(['name','starts_at']); $this->load(); session()->flash('ok','Aula criada.');
    }
    public function reserve($id){
        $c = GymClass::findOrFail($id);
        if($c->confirmedCount() >= $c->capacity){ session()->flash('err','Turma cheia — lista de espera.'); $status='waitlist'; }
        else $status='reserved';
        $student = \App\Models\Student::first();
        \App\Models\ClassReservation::firstOrCreate(['gym_class_id'=>$id,'student_id'=>$student->id],['status'=>$status]);
        $this->load();
    }
    public function render(){ return view('livewire.classes.class-calendar',['teachers'=>Teacher::orderBy('name')->get()])->layout('layouts.app'); }
}
