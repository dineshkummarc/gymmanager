<div>
  <h1 class="text-2xl font-extrabold mb-1">Painel do professor</h1><p class="text-sm text-zinc-500 mb-5">{{ $me->name ?? '' }} · {{ $me->specialty ?? '' }} · {{ $activeStudents }} alunos ativos na academia</p>
  <div class="grid lg:grid-cols-2 gap-4">
    <div class="card p-5"><h3 class="font-bold mb-3">Minhas próximas aulas</h3>@forelse($myClasses as $c)<div class="py-2 border-b border-[#1c1c1f] text-sm"><b>{{ $c->name }}</b> <span class="text-zinc-500">{{ $c->starts_at->format('d/m H:i') }} · {{ $c->confirmed }}/{{ $c->capacity }}</span></div>@empty<p class="text-sm text-zinc-500">Nenhuma aula agendada.</p>@endforelse</div>
    <div class="card p-5"><h3 class="font-bold mb-3">⚠️ Treinos vencendo (7 dias)</h3>@forelse($expiring as $w)<p class="text-sm py-1.5 border-b border-[#1c1c1f]">{{ $w->student->name }} · {{ $w->name }} <span class="text-xs text-amber-400">expira {{ $w->expires_at?->format('d/m') }}</span></p>@empty<p class="text-sm text-green-400">Tudo em dia. 🎉</p>@endforelse</div>
  </div>
  <div class="card p-5 mt-4"><h3 class="font-bold mb-3">Avaliações recentes</h3>@foreach($recentAssessments as $a)<p class="text-sm py-1 border-b border-[#1c1c1f]">{{ $a->student->name }} · {{ $a->weight }}kg · IMC {{ $a->bmi }}</p>@endforeach</div>
</div>
