<?php
namespace App\Livewire\Memberships;
use Livewire\Component; use Livewire\WithPagination;
use App\Models\{Student, Plan, Membership, Branch};
use App\Services\MembershipService;
class MembershipManager extends Component {
    use WithPagination;
    public $student_id=null; public $plan_id=null; public $branch_id=null;
    public string $starts_at=''; public $price=0; public $discount=0; public int $installments=1;
    public string $payment_method='pix'; public string $search=''; public string $status='';
    public function mount(){ $this->starts_at = now()->toDateString(); $this->branch_id = auth()->user()->branch_id; if(request('student')) $this->student_id = request('student'); }
    public function updatedPlanId($v){ if($p = Plan::find($v)) $this->price = $p->price; }
    public function save(){
        $this->validate(['student_id'=>'required','plan_id'=>'required','starts_at'=>'required|date','price'=>'required|numeric|min:0']);
        $s = Student::findOrFail($this->student_id);
        $this->authorize('update', $s);
        $m = (new MembershipService)->create($s, [
            'branch_id'=>$this->branch_id ?? $s->branch_id, 'plan_id'=>$this->plan_id,
            'starts_at'=>$this->starts_at, 'price'=>$this->price, 'discount'=>$this->discount ?? 0,
            'installments'=>$this->installments, 'payment_method'=>$this->payment_method,
        ]);
        \App\Models\ActivityLog::create(['gym_id'=>$m->gym_id,'user_id'=>auth()->id(),'action'=>'membership.created','entity'=>'membership','entity_id'=>$m->id,'ip'=>request()->ip()]);
        $this->reset(['plan_id','price','discount','installments']); session()->flash('ok','Matrícula criada com '.$m->invoices()->count().' mensalidade(s) + contrato.');
    }
    public function cancel($id){
        $m = Membership::findOrFail($id); $this->authorize('update', $m->student);
        (new MembershipService)->cancel($m); session()->flash('ok','Matrícula cancelada e faturas pendentes baixadas.');
    }
    public function render(){
        $q = Membership::with(['student','plan','branch'])->orderBy('created_at','desc');
        if($this->search) $q->whereHas('student',fn($w)=>$w->where('name','like',"%{$this->search}%"));
        if($this->status) $q->where('status',$this->status);
        return view('livewire.memberships.manager',[
            'items'=>$q->paginate(10),
            'students'=>Student::orderBy('name')->limit(300)->get(),
            'plans'=>Plan::active()->orderBy('price')->get(),
            'branches'=>Branch::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
