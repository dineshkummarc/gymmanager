<div>
  <h1 class="text-2xl font-extrabold mb-4">Unidades</h1>
  <div class="card p-4 mb-4 grid md:grid-cols-5 gap-3"><input wire:model="name" class="input" placeholder="Nome*"><input wire:model="code" class="input" placeholder="Código* (ex: MOEMA-01)"><input wire:model="address" class="input" placeholder="Endereço"><input wire:model="phone" class="input" placeholder="Telefone"><button wire:click="save" class="btn-primary">Criar unidade</button></div>
  <div class="grid md:grid-cols-3 gap-4">@foreach($items as $b)<div class="card p-5 {{ !$b->active?'opacity-50':'' }}">
    <div class="flex items-center gap-2"><h3 class="font-bold">{{ $b->name }}</h3><span class="badge bg-zinc-700/30 text-zinc-300 ml-auto">{{ $b->code }}</span></div>
    <p class="text-xs text-zinc-500 mt-1">{{ $b->address }} · {{ $b->students_count }} alunos</p>
    <p class="text-xs text-zinc-500">{{ $b->opening_hours }}</p>
    <button wire:click="toggle({{ $b->id }})" class="btn-ghost w-full mt-3">{{ $b->active?'Desativar':'Ativar' }}</button>
  </div>@endforeach</div>
</div>
