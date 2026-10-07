<?php
namespace App\Livewire\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Password;
class ForgotPassword extends Component {
    public string $email=''; public string $sent='';
    public function send(){
        $this->validate(['email'=>'required|email']);
        $status = Password::sendResetLink(['email'=>$this->email]);
        if($status === Password::RESET_LINK_SENT){ $this->sent = 'Link de recuperação enviado. Verifique seu e-mail.'; }
        else $this->addError('email','E-mail não encontrado.');
    }
    public function render(){ return view('livewire.auth.forgot')->layout('layouts.guest'); }
}
