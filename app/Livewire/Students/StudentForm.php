<?php
namespace App\Livewire\Students;
use Livewire\Component;
use App\Models\{Student, Branch};
use App\Support\QrToken;
use Illuminate\Support\Str;
class StudentForm extends Component {
    public ?Student $student = null; public int $step = 1;
    public array $data = ['name'=>'','cpf'=>'','birth_date'=>'','gender'=>'masculino','email'=>'','phone'=>'','whatsapp'=>'','branch_id'=>'','address'=>'','city'=>'','state'=>'','zip'=>'','emergency_contact'=>'','notes'=>'','plan_id'=>'','status'=>'active'];
    public function mount($student=null){ if($student){ $this->student=$student instanceof Student?$student:Student::findOrFail($student); $this->data=array_merge($this->data,$this->student->toArray()); } }
    public function next(){ $this->validateStep(); $this->step=min(4,$this->step+1); }
    public function prev(){ $this->step=max(1,$this->step-1); }
    protected function validateStep(){
        if($this->step===1) $this->validate(['data.name'=>'required|min:3','data.cpf'=>'nullable']);
        if($this->step===2) $this->validate(['data.email'=>'nullable|email','data.phone'=>'nullable']);
    }
    public function save(){
        $this->validate(['data.name'=>'required|min:3','data.branch_id'=>'required']);
        $code = $this->student?->member_code ?? 'GM'.strtoupper(Str::random(6));
        $payload = collect($this->data)->except('plan_id')->merge(['member_code'=>$code,'qr_token'=>QrToken::make($code)])->all();
        if($this->student){ $this->student->update($payload); session()->flash('ok','Aluno atualizado.'); }
        else {
            $created = Student::create($payload);
            \App\Models\ActivityLog::create(['gym_id'=>$created->gym_id,'user_id'=>auth()->id(),'action'=>'student.created','entity'=>'student','entity_id'=>$created->id,'ip'=>request()->ip()]);
            if(!empty($this->data['plan_id'])) return redirect()->route('memberships.index',['student'=>$created->id]);
            session()->flash('ok','Aluno cadastrado e acesso liberado.');
            return redirect()->route('students.index');
        }
    }
    public function render(){ return view('livewire.students.student-form',['branches'=>Branch::orderBy('name')->get(),'plans'=>\App\Models\Plan::active()->get()])->layout('layouts.app'); }
}
