<div>
  <h1 class="text-2xl font-extrabold mb-4">Aulas e turmas</h1>
  <div class="card p-4 mb-4 grid md:grid-cols-5 gap-3"><input wire:model="name" class="input" placeholder="Nome da aula (ex: Spinning)"><select wire:model="teacher_id" class="input"><option value="">Professor...</option>@foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select><input wire:model="starts_at" type="datetime-local" class="input"><input wire:model="capacity" type="number" class="input" placeholder="Vagas"><button wire:click="save" class="btn-primary">Criar aula</button></div>
  <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">@foreach($classes as $c)<div class="card p-5">
    <div class="flex items-center gap-2"><h3 class="font-bold">{{ $c->name }}</h3><span class="badge bg-blue-600/15 text-blue-400 ml-auto">{{ $c->confirmedCount() }}/{{ $c->capacity }}</span></div>
    <p class="text-xs text-zinc-500 mt-1">{{ $c->starts_at->format('d/m H:i') }} · {{ $c->teacher->name ?? '—' }} · {{ $c->room ?? 'Sala 1' }}</p>
    <div class="w-full h-2 bg-[#27272A] rounded-full mt-3"><div class="h-2 bg-blue-600 rounded-full" style="width:{{ $c->capacity?min(100,$c->confirmedCount()/$c->capacity*100):0 }}%"></div></div>
    <button wire:click="reserve({{ $c->id }})" class="btn-ghost w-full mt-3">Reservar vaga</button>
  </div>@endforeach</div>
</div>
