<?php
namespace App\Livewire\Users;
use Livewire\Component;
use App\Models\{User, Branch};
use Illuminate\Support\Facades\Hash;
class UserManager extends Component {
    public string $name=''; public string $email=''; public string $password=''; public string $role='receptionist'; public $branch_id=null;
    public function save(){
        $this->validate(['name'=>'required','email'=>'required|email|unique:users,email','password'=>'required|min:8','role'=>'required']);
        User::create(['gym_id'=>auth()->user()->gym_id,'branch_id'=>$this->branch_id,'name'=>$this->name,'email'=>$this->email,
            'password'=>Hash::make($this->password),'role'=>$this->role,'active'=>true,'email_verified_at'=>now()]);
        $this->reset(['name','email','password']); session()->flash('ok','Colaborador criado.');
    }
    public function toggle($id){ $u=User::findOrFail($id); $u->update(['active'=>!$u->active]); }
    public function render(){
        return view('livewire.users.manager',[
            'items'=>User::orderBy('name')->paginate(15),
            'branches'=>Branch::orderBy('name')->get(),
            'roles'=>\App\Enums\UserRole::cases(),
        ])->layout('layouts.app');
    }
}
