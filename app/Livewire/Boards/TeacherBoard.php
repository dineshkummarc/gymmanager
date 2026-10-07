<?php
namespace App\Livewire\Boards;
use Livewire\Component;
use App\Models\{GymClass, Teacher, WorkoutPlan, Student, PhysicalAssessment};
class TeacherBoard extends Component {
    public function render(){
        $me = Teacher::where('email',auth()->user()->email)->first() ?? Teacher::orderBy('id')->first();
        $myClasses = $me ? GymClass::withCount(['reservations as confirmed'=>fn($q)=>$q->where('status','reserved')])->where('teacher_id',$me->id)->where('starts_at','>=',now()->subDay())->orderBy('starts_at')->limit(8)->get() : collect();
        return view('livewire.boards.teacher',[
            'me'=>$me, 'myClasses'=>$myClasses,
            'expiring'=>WorkoutPlan::with('student')->where('status','active')->where('expires_at','<=',now()->addDays(7))->limit(8)->get(),
            'recentAssessments'=>PhysicalAssessment::with('student')->latest()->limit(5)->get(),
            'activeStudents'=>(int)Student::active()->count(),
        ])->layout('layouts.app');
    }
}
