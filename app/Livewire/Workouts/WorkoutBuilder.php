<?php
namespace App\Livewire\Workouts;
use Livewire\Component;
use App\Models\{WorkoutPlan, WorkoutSession, WorkoutExercise, Exercise, Student, Teacher};
class WorkoutBuilder extends Component {
    public $planId=null; public $plan=null; public string $sessionName='Treino A'; public $exerciseId=null; public int $sets=3; public string $reps='12'; public string $load='';
    public function mount($planId=null){ if($planId){ $this->planId=$planId; $this->plan=WorkoutPlan::with('sessions.items.exercise')->findOrFail($planId); } }
    public function addSession(){
        $p = $this->plan ?? WorkoutPlan::create(['student_id'=>request('student_id', Student::first()?->id),'name'=>request('name','Nova ficha'),'goal'=>'Hipertrofia','starts_at'=>now(),'expires_at'=>now()->addDays(60),'status'=>'active','coach_id'=>Teacher::first()?->id]);
        $this->plan=$p; $this->planId=$p->id;
        $p->sessions()->create(['name'=>$this->sessionName,'order'=>$p->sessions()->count()]);
        $this->plan->refresh(); session()->flash('ok','Sessão adicionada.');
    }
    public function addExercise($sessionId){
        WorkoutExercise::create(['workout_session_id'=>$sessionId,'exercise_id'=>$this->exerciseId,'sets'=>$this->sets,'reps'=>$this->reps,'load'=>$this->load,'order'=>0]);
        $this->plan->refresh();
    }
    public function render(){ return view('livewire.workouts.workout-builder',['exercises'=>Exercise::active()->orderBy('name')->get(),'students'=>Student::orderBy('name')->limit(100)->get()])->layout('layouts.app'); }
}
