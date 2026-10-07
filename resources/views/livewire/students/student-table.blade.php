<div>
  <div class="flex flex-wrap gap-3 mb-5">
    <input wire:model.live.debounce.300ms="search" class="input !w-64" placeholder="🔍 Buscar aluno, CPF, código... (Ctrl+K)">
    <select wire:model.live="status" class="input !w-44"><option value="">Todos status</option><option value="active">Ativo</option><option value="inactive">Inativo</option><option value="suspended">Suspenso</option><option value="pending">Pendente</option></select>
    <select wire:model.live="branch" class="input !w-52"><option value="">Todas unidades</option>@foreach($branches as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select>
    <a href="/students/create" class="btn-primary ml-auto">+ Novo aluno</a>
  </div>
  <div class="card overflow-hidden"><table class="w-full text-sm">
    <thead><tr class="text-left text-zinc-500 text-xs uppercase tracking-wider border-b border-[#27272A]"><th class="p-4">Aluno</th><th class="p-4">Unidade</th><th class="p-4">Plano</th><th class="p-4">Status</th><th class="p-4"></th></tr></thead>
    <tbody>@forelse($students as $s)<tr class="border-b border-[#1c1c1f] table-row">
      <td class="p-4"><div class="flex items-center gap-3"><div class="w-9 h-9 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center font-bold">{{ substr($s->name,0,1) }}</div><div><div class="font-semibold">{{ $s->name }}</div><div class="text-xs text-zinc-500">{{ $s->member_code }} · {{ $s->phone }}</div></div></div></td>
      <td class="p-4 text-zinc-400">{{ $s->branch->name ?? '—' }}</td>
      <td class="p-4 text-zinc-400">{{ $s->activeMembership->plan->name ?? '—' }}</td>
      <td class="p-4"><span class="badge {{ $s->status==='active'?'bg-green-600/15 text-green-400':'bg-zinc-600/20 text-zinc-400' }}">{{ $s->status }}</span></td>
      <td class="p-4 text-right whitespace-nowrap"><a href="/students/{{ $s->id }}" class="btn-ghost !py-1.5">Abrir</a> <a href="/students/{{ $s->id }}/edit" class="btn-ghost !py-1.5">Editar</a></td>
    </tr>@empty<tr><td colspan="5" class="p-10 text-center text-zinc-500">Nenhum aluno encontrado.</td></tr>@endforelse</tbody>
  </table></div>
  <div class="mt-4">{{ $students->links() }}</div>
</div>
