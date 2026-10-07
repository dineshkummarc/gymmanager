<div>
  <div class="flex items-center mb-4"><h1 class="text-2xl font-extrabold">Presenças</h1><div class="ml-auto card !rounded-xl px-4 py-2">Hoje: <b class="text-blue-400">{{ $todayCount }}</b></div></div>
  <div class="flex gap-2 mb-4"><input wire:model.live="date" type="date" class="input !w-48"><select wire:model.live="branch" class="input !w-52"><option value="">Todas unidades</option>@foreach($branches as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select></div>
  <div class="grid lg:grid-cols-3 gap-4">
    <div class="card overflow-hidden lg:col-span-2"><table class="w-full text-sm"><thead><tr class="text-left text-xs text-zinc-500 uppercase border-b border-[#27272A]"><th class="p-3">Hora</th><th class="p-3">Aluno</th><th class="p-3">Unidade</th><th class="p-3">Status</th></tr></thead>
    <tbody>@foreach($items as $c)<tr class="border-b border-[#1c1c1f]"><td class="p-3 text-zinc-500">{{ $c->checked_in_at->format('H:i') }}</td><td class="p-3 font-semibold">{{ $c->student->name }}</td><td class="p-3 text-zinc-400">{{ $c->branch->name ?? '' }}</td><td class="p-3">@if($c->allowed)<span class="badge bg-green-600/15 text-green-400">ok</span>@else<span class="badge bg-red-600/15 text-red-400" title="{{ $c->deny_reason }}">negado</span>@endif</td></tr>@endforeach</tbody></table><div class="p-3">{{ $items->links() }}</div></div>
    <div class="card p-5 h-fit"><h3 class="font-bold mb-1">⚠️ Baixa frequência</h3><p class="text-xs text-zinc-500 mb-3">Ativos sem check-in há 14+ dias</p>@forelse($low as $s)<p class="text-sm py-1.5 border-b border-[#1c1c1f]">{{ $s->name }} <span class="text-xs text-zinc-500">{{ $s->phone }}</span></p>@empty<p class="text-sm text-green-400">Ninguém em risco. 🎉</p>@endforelse</div>
  </div>
</div>
