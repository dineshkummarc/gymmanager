<?php
namespace App\Livewire\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class Login extends Component {
    public string $email=''; public string $password=''; public bool $remember=false;
    public function login(){
        $this->validate(['email'=>'required|email','password'=>'required']);
        if(Auth::attempt(['email'=>$this->email,'password'=>$this->password,'active'=>1],$this->remember)){
            session()->regenerate();
            auth()->user()->update(['last_login_at'=>now()]);
            \App\Models\ActivityLog::create(['gym_id'=>auth()->user()->gym_id,'user_id'=>auth()->id(),'action'=>'login','ip'=>request()->ip()]);
            return redirect()->intended(auth()->user()->role==='student'?'/student-portal':'/dashboard');
        }
        $this->addError('email','Credenciais inválidas.');
    }
    public function render(){ return view('livewire.auth.login')->layout('layouts.guest'); }
}
