<div>
  @if(!$student)<div class="card p-8 text-center text-zinc-500">Nenhum vínculo de aluno encontrado para este usuário.</div>
  @else
  <h1 class="text-xl font-extrabold">Olá, {{ explode(' ', $student->name)[0] }} 👋</h1>
  <p class="text-xs text-zinc-500 mb-4">{{ $student->activeMembership->plan->name ?? 'Sem plano ativo' }}</p>
  <div id="treino" class="card p-5 mb-3"><h3 class="font-bold mb-2">🏋️ Seu treino de hoje</h3>
  @if($student->workouts->isNotEmpty())
    @foreach($student->workouts->take(1) as $w)
      @foreach($w->sessions->take(2) as $s)
        <p class="text-sm font-semibold mt-2">{{ $s->name }}</p>
        @foreach($s->items->take(4) as $it)
          <p class="text-xs text-zinc-400">· {{ $it->exercise->name }} — {{ $it->sets }}×{{ $it->reps }} {{ $it->load }}</p>
        @endforeach
      @endforeach
    @endforeach
  @else
    <p class="text-sm text-zinc-500">Nenhum treino prescrito ainda.</p>
  @endif
  </div>
  <div class="grid grid-cols-2 gap-3 mb-3">
    <div class="card p-4"><div class="text-[11px] text-zinc-500 uppercase">Próx. vencimento</div><div class="font-extrabold">{{ $student->invoices->where('status','!=','paid')->sortBy('due_date')->first()?->due_date?->format('d/m') ?? '—' }}</div></div>
    <div class="card p-4"><div class="text-[11px] text-zinc-500 uppercase">Check-ins</div><div class="font-extrabold">{{ $student->checkins->count() }}</div></div>
  </div>
  <div class="card p-5 mb-3"><h3 class="font-bold mb-2">🗓️ Próximas aulas</h3>@foreach($classes as $c)<div class="flex items-center gap-2 py-1.5 border-b border-[#1c1c1f]"><p class="text-xs text-zinc-400 flex-1">{{ $c->starts_at->format('d/m H:i') }} · {{ $c->name }} · {{ $c->teacher->name ?? '' }}</p>@if(isset($mine[$c->id]))<span class="badge {{ $mine[$c->id]==='reserved'?'bg-green-600/15 text-green-400':'bg-amber-600/15 text-amber-400' }}">{{ $mine[$c->id]==='reserved'?'reservado':'espera' }}</span>@else<button wire:click="reserve({{ $c->id }})" class="text-xs text-blue-400">Reservar</button>@endif</div>@endforeach</div>
  <div id="faturas" class="card p-5"><h3 class="font-bold mb-2">💳 Faturas</h3>@foreach($student->invoices->take(6) as $i)<div class="flex items-center gap-2 text-xs py-1.5 border-b border-[#1c1c1f]"><span class="flex-1">{{ $i->description }} · {{ $i->due_date->format('d/m') }}</span>@if($i->status==='paid')<b class="text-green-400">pago</b>@else<b class="text-amber-400">R$ {{ number_format($i->balance(),2,',','.') }}</b><button wire:click="pay({{ $i->id }})" class="text-blue-400">Pagar via PIX</button>@endif</div>@endforeach</div>
  @endif
</div>
