<div class="max-w-3xl mx-auto">
  <h1 class="text-2xl font-extrabold mb-1">Check-in</h1><p class="text-sm text-zinc-500 mb-5">Busque por nome, CPF ou código · QR / cartão / código</p>
  <select wire:model.live="branchId" class="input mb-3">@foreach($branches as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select>
  <input wire:model.live.debounce.250ms="search" autofocus class="input !text-lg !py-4" placeholder="Digite o nome ou código do aluno...">
  @if(count($results))<div class="card mt-2 overflow-hidden">@foreach($results as $s)
    <button wire:click="checkin({{ $s->id }})" class="w-full text-left p-4 hover:bg-white/5 flex items-center gap-3 border-b border-[#1c1c1f]">
      <div class="w-10 h-10 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center font-bold">{{ substr($s->name,0,1) }}</div>
      <div><div class="font-semibold">{{ $s->name }}</div><div class="text-xs text-zinc-500">{{ $s->member_code }} · {{ $s->activeMembership->plan->name ?? 'sem plano' }}</div></div>
    </button>@endforeach</div>@endif
  @if($last)<div class="card mt-4 p-6 text-center {{ $last->allowed?'!border-green-600':'!border-red-600' }}">
    @if($last->allowed)<div class="text-5xl">✅</div><h2 class="text-xl font-extrabold mt-2 text-green-400">ACESSO LIBERADO</h2><p class="font-semibold mt-1">{{ $last->student->name }}</p><p class="text-xs text-zinc-500">{{ $last->student->activeMembership->plan->name ?? '' }} · {{ $last->checked_in_at->format('H:i') }}</p>
    @else<div class="text-5xl">⛔</div><h2 class="text-xl font-extrabold mt-2 text-red-400">ACESSO NEGADO</h2><p class="text-sm mt-1">Motivo: {{ $last->deny_reason }}</p><p class="font-semibold">{{ $last->student->name }}</p>@endif
  </div>@endif
</div>
