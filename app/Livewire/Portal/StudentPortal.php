<?php
namespace App\Livewire\Portal;
use Livewire\Component;
class StudentPortal extends Component {
    protected function student(){
        $u = auth()->user();
        return \App\Models\Student::where('user_id',$u->id)->orWhere('email',$u->email)
            ->with(['activeMembership.plan','workouts.sessions.items.exercise','invoices','checkins'=>fn($q)=>$q->latest()->limit(10)])->first();
    }
    public function reserve($classId){
        $s = $this->student(); abort_unless($s, 403);
        $c = \App\Models\GymClass::findOrFail($classId);
        $status = $c->confirmedCount() >= $c->capacity ? 'waitlist' : 'reserved';
        \App\Models\ClassReservation::firstOrCreate(['gym_class_id'=>$classId,'student_id'=>$s->id],['gym_id'=>$s->gym_id,'status'=>$status]);
        session()->flash('ok', $status==='reserved' ? 'Vaga reservada!' : 'Turma cheia — você entrou na lista de espera.');
    }
    public function pay($invoiceId){
        $s = $this->student(); abort_unless($s, 403);
        $inv = \App\Models\Invoice::where('student_id',$s->id)->findOrFail($invoiceId);
        (new \App\Services\PaymentService)->pay($inv, ['amount'=>$inv->balance(),'method'=>'pix','notes'=>'Pagamento via portal do aluno']);
        session()->flash('ok','Pagamento registrado. Recibo disponível na recepção.');
    }
    public function render(){
        $student = $this->student();
        $classes = \App\Models\GymClass::with('teacher')->where('starts_at','>=',now())->orderBy('starts_at')->limit(6)->get();
        $mine = $student ? \App\Models\ClassReservation::where('student_id',$student->id)->pluck('status','gym_class_id')->all() : [];
        return view('livewire.portal.student-portal',compact('student','classes','mine'))->layout('layouts.portal');
    }
}
