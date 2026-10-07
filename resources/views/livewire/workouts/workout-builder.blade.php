<div>
  <h1 class="text-2xl font-extrabold mb-4">Fichas de treino</h1>
  @if(!$plan)<div class="card p-8 text-center"><p class="text-zinc-500 mb-4">Monte uma ficha profissional: sessões (A/B/C) + exercícios com séries, reps, carga e descanso.</p><button wire:click="addSession" class="btn-primary">+ Nova ficha</button></div>
  @else
  <div class="card p-5 mb-4 flex flex-wrap items-center gap-3"><div><h2 class="font-extrabold text-lg">{{ $plan->name }}</h2><p class="text-xs text-zinc-500">{{ $plan->student->name ?? '' }} · {{ $plan->goal }}</p></div>
    <div class="ml-auto flex gap-2"><input wire:model="sessionName" class="input !w-40" placeholder="Nome sessão"><button wire:click="addSession" class="btn-primary">+ Sessão</button></div></div>
  <div class="grid lg:grid-cols-2 gap-4">@foreach($plan->sessions as $sess)<div class="card p-5">
    <h3 class="font-bold mb-3">{{ $sess->name }}</h3>
    @foreach($sess->items as $it)<div class="py-2 border-b border-[#1c1c1f] text-sm"><b>{{ $it->exercise->name }}</b><div class="text-xs text-zinc-500">{{ $it->sets }}×{{ $it->reps }} · {{ $it->load }} · descanso {{ $it->rest_seconds }}s</div></div>@endforeach
    <div class="grid grid-cols-4 gap-2 mt-3"><select wire:model="exerciseId" class="input col-span-2"><option value="">Exercício...</option>@foreach($exercises as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach</select><input wire:model="sets" type="number" class="input" placeholder="Séries"><input wire:model="load" class="input" placeholder="Carga"><input wire:model="reps" class="input" placeholder="Reps"><button wire:click="addExercise({{ $sess->id }})" class="btn-primary col-span-3">Adicionar exercício</button></div>
  </div>@endforeach</div>@endif
</div>
