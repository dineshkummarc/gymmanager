<div class="card p-8 w-full max-w-md">
  <div class="font-extrabold text-lg mb-1">Recuperar senha</div><p class="text-xs text-zinc-500 mb-5">Enviaremos um link de redefinição.</p>
  @if($sent)<div class="p-3 rounded-xl bg-green-600/15 border border-green-600/30 text-green-400 text-sm mb-4">{{ $sent }}</div>@endif
  <form wire:submit="send" class="space-y-4"><input wire:model="email" type="email" class="input" placeholder="Seu e-mail">@error('email')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror<button class="btn-primary w-full">Enviar link</button></form>
  <a href="/login" class="block text-center text-sm text-blue-400 mt-4">← Voltar ao login</a>
</div>
