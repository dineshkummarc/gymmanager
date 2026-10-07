<?php
namespace App\Livewire\History;
use Livewire\Component;
use App\Models\{Checkin, Student, Branch};
class CheckinHistory extends Component {
    public string $date=''; public $branch='';
    public function mount(){ $this->date = now()->toDateString(); }
    public function render(){
        $q = Checkin::with(['student','branch'])->whereDate('checked_in_at',$this->date)->orderBy('checked_in_at','desc');
        if($this->branch) $q->where('branch_id',$this->branch);
        $low = Student::active()->whereDoesntHave('checkins',fn($w)=>$w->where('checked_in_at','>=',now()->subDays(14)))->limit(10)->get();
        return view('livewire.history.checkins',['items'=>$q->paginate(20),'low'=>$low,'branches'=>Branch::orderBy('name')->get(),
            'todayCount'=>(int)Checkin::whereDate('checked_in_at',today())->count()])->layout('layouts.app');
    }
}
