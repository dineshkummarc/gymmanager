<?php
namespace App\Livewire\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\{Password, Hash};
use Illuminate\Auth\Events\PasswordReset;
class ResetPassword extends Component {
    public string $token=''; public string $email=''; public string $password=''; public string $password_confirmation='';
    public function mount($token){ $this->token=$token; $this->email=request('email',''); }
    public function doReset(){
        $this->validate(['email'=>'required|email','password'=>'required|min:8|confirmed']);
        $status = Password::reset(
            ['email'=>$this->email,'password'=>$this->password,'password_confirmation'=>$this->password_confirmation,'token'=>$this->token],
            function($u,$p){ $u->forceFill(['password'=>Hash::make($p),'remember_token'=>\Illuminate\Support\Str::random(60)])->save(); event(new PasswordReset($u)); }
        );
        if($status===Password::PASSWORD_RESET) return redirect('/login')->with('ok','Senha redefinida. Entre com a nova senha.');
        $this->addError('email','Token inválido ou expirado.');
    }
    public function render(){ return view('livewire.auth.reset')->layout('layouts.guest'); }
}
