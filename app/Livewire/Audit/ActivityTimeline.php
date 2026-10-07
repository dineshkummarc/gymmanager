<?php
namespace App\Livewire\Audit;
use Livewire\Component; use Livewire\WithPagination;
use App\Models\ActivityLog;
class ActivityTimeline extends Component {
    use WithPagination;
    public string $action='';
    public function render(){
        $q = ActivityLog::with('user')->orderBy('created_at','desc');
        if($this->action) $q->where('action','like',"%{$this->action}%");
        return view('livewire.audit.timeline',['items'=>$q->paginate(20)])->layout('layouts.app');
    }
}
