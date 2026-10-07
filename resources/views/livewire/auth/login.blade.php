<div class="card p-8 w-full max-w-md">
  <div class="flex items-center gap-3 mb-6"><div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center font-black text-xl">G</div><div><div class="font-extrabold text-lg">GymManager</div><div class="text-xs text-zinc-500">Gestão profissional para academias</div></div></div>
  <form wire:submit="login" class="space-y-4">
    <div><label class="text-xs text-zinc-500 uppercase">E-mail</label><input wire:model="email" type="email" class="input mt-1" placeholder="demo@gymmanager.test">@error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div><label class="text-xs text-zinc-500 uppercase">Senha</label><input wire:model="password" type="password" class="input mt-1" placeholder="••••••••"></div>
    <label class="flex items-center gap-2 text-sm text-zinc-400"><input type="checkbox" wire:model="remember" class="accent-blue-600"> Lembrar de mim</label>
    <button class="btn-primary w-full !py-3">Entrar →</button>
    <button type="button" @click="$wire.set('email','demo@gymmanager.test'); $wire.set('password','password'); $wire.set('remember', true); $wire.call('login')" class="btn-ghost w-full !py-3 !text-white !border-blue-600/50 hover:!border-blue-500">⚡ Entrar com conta demo</button>
  </form>
  <a href="/forgot-password" class="block text-center text-sm text-zinc-500 hover:text-blue-400 mt-3">Esqueci minha senha</a>
  <div class="mt-5 p-3 rounded-xl bg-blue-600/10 border border-blue-600/20 text-xs text-zinc-400">Demo: <b class="text-white">demo@gymmanager.test</b> / <b class="text-white">password</b></div>
</div>
