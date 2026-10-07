<?php
namespace App\Livewire\Commissions;
use Livewire\Component;
use App\Models\Commission;
class CommissionBoard extends Component {
    public string $filter='pending';
    public function pay($id){ Commission::findOrFail($id)->update(['status'=>'paid','paid_at'=>now()]); session()->flash('ok','Comissão paga.'); }
    public function render(){
        $q = Commission::orderBy('created_at','desc');
        if($this->filter) $q->where('status',$this->filter);
        $items = $q->paginate(15);
        $ids = $items->pluck('seller_id')->filter()->unique();
        $names = \App\Models\User::whereIn('id',$ids)->pluck('name','id');
        return view('livewire.commissions.board',[
            'items'=>$items,'names'=>$names,
            'pending'=>(float)Commission::where('status','pending')->sum('amount'),
            'paid'=>(float)Commission::where('status','paid')->whereMonth('paid_at',now()->month)->sum('amount'),
        ])->layout('layouts.app');
    }
}
