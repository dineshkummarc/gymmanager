<div class="card p-8 w-full max-w-md">
  <div class="font-extrabold text-lg mb-5">Definir nova senha</div>
  <form wire:submit="doReset" class="space-y-4"><input wire:model="email" type="email" class="input" placeholder="E-mail">@error('email')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror<input wire:model="password" type="password" class="input" placeholder="Nova senha"><input wire:model="password_confirmation" type="password" class="input" placeholder="Confirmar senha"><button class="btn-primary w-full">Redefinir senha</button></form>
</div>
