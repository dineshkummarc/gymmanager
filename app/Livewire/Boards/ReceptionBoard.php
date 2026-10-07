<?php
namespace App\Livewire\Boards;
use Livewire\Component;
use App\Models\{Checkin, Invoice, GymClass, Lead, Student};
class ReceptionBoard extends Component {
    public function render(){
        $b = auth()->user()->branch_id;
        $scope = fn($q) => $b ? $q->where('branch_id',$b) : $q;
        return view('livewire.boards.reception',[
            'checkins'=>$scope(Checkin::with('student')->whereDate('checked_in_at',today())->orderBy('checked_in_at','desc'))->limit(8)->get(),
            'checkinCount'=>(int)$scope(Checkin::whereDate('checked_in_at',today()))->count(),
            'pending'=>Invoice::whereIn('status',['pending','partial','overdue'])->when($b,fn($q)=>$q->where('branch_id',$b))->count(),
            'pendingTotal'=>(float)Invoice::whereIn('status',['pending','partial','overdue'])->when($b,fn($q)=>$q->where('branch_id',$b))->sum('amount'),
            'classes'=>GymClass::with('teacher')->whereDate('starts_at',today())->when($b,fn($q)=>$q->where('branch_id',$b))->orderBy('starts_at')->get(),
            'leads'=>Lead::whereNotIn('status',['won','lost'])->when($b,fn($q)=>$q->where('branch_id',$b))->latest()->limit(5)->get(),
            'newStudents'=>(int)$scope(Student::where('created_at','>=',now()->subDays(7)))->count(),
        ])->layout('layouts.app');
    }
}
