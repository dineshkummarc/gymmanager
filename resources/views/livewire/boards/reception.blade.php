<div>
  <h1 class="text-2xl font-extrabold mb-1">Recepção</h1><p class="text-sm text-zinc-500 mb-5">Operação do dia · {{ now()->format('d/m/Y') }}</p>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="card p-5"><div class="text-xs text-zinc-500 uppercase">Check-ins hoje</div><div class="text-2xl font-extrabold">{{ $checkinCount }}</div></div>
    <div class="card p-5"><div class="text-xs text-zinc-500 uppercase">A cobrar</div><div class="text-2xl font-extrabold text-amber-400">{{ $pending }} <span class="text-sm font-normal">· R$ {{ number_format($pendingTotal,0,',','.') }}</span></div></div>
    <div class="card p-5"><div class="text-xs text-zinc-500 uppercase">Aulas hoje</div><div class="text-2xl font-extrabold">{{ $classes->count() }}</div></div>
    <div class="card p-5"><div class="text-xs text-zinc-500 uppercase">Novos alunos (7d)</div><div class="text-2xl font-extrabold text-green-400">{{ $newStudents }}</div></div>
  </div>
  <div class="flex flex-wrap gap-2 mb-4"><a href="/students/create" class="btn-primary">+ Novo aluno</a><a href="/memberships" class="btn-ghost">Nova matrícula</a><a href="/payments" class="btn-ghost">Receber pagamento</a><a href="/checkin" class="btn-ghost">Check-in</a><a href="/leads" class="btn-ghost">Novo lead</a></div>
  <div class="grid lg:grid-cols-3 gap-4">
    <div class="card p-5"><h3 class="font-bold mb-3">Últimos check-ins</h3>@forelse($checkins as $c)<p class="text-sm py-1 border-b border-[#1c1c1f]">{{ $c->checked_in_at->format('H:i') }} · {{ $c->student->name }} @if(!$c->allowed)<span class="text-red-400 text-xs">negado</span>@endif</p>@empty<p class="text-sm text-zinc-500">Nenhum ainda hoje.</p>@endforelse</div>
    <div class="card p-5"><h3 class="font-bold mb-3">Aulas de hoje</h3>@forelse($classes as $c)<p class="text-sm py-1 border-b border-[#1c1c1f]">{{ $c->starts_at->format('H:i') }} · {{ $c->name }} <span class="text-xs text-zinc-500">{{ $c->teacher->name ?? '' }}</span></p>@empty<p class="text-sm text-zinc-500">Sem aulas hoje.</p>@endforelse</div>
    <div class="card p-5"><h3 class="font-bold mb-3">Leads p/ contatar</h3>@foreach($leads as $l)<p class="text-sm py-1 border-b border-[#1c1c1f]">{{ $l->name }} <span class="text-xs text-zinc-500">{{ $l->phone }}</span></p>@endforeach<a href="/leads" class="text-blue-400 text-sm mt-2 inline-block">Abrir pipeline →</a></div>
  </div>
</div>
