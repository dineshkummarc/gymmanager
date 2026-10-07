<div>
  <h1 class="text-2xl font-extrabold mb-4">Auditoria</h1>
  <input wire:model.live.debounce.300ms="action" class="input !w-64 mb-4" placeholder="Filtrar ação (ex: login, sale...)">
  <div class="card overflow-hidden"><table class="w-full text-sm"><thead><tr class="text-left text-xs text-zinc-500 uppercase border-b border-[#27272A]"><th class="p-4">Data</th><th class="p-4">Usuário</th><th class="p-4">Ação</th><th class="p-4">Entidade</th><th class="p-4">IP</th></tr></thead>
  <tbody>@foreach($items as $l)<tr class="border-b border-[#1c1c1f]"><td class="p-4 text-zinc-500">{{ $l->created_at->format('d/m H:i') }}</td><td class="p-4">{{ $l->user->name ?? 'sistema' }}</td><td class="p-4"><span class="badge bg-zinc-700/30 text-zinc-300">{{ $l->action }}</span></td><td class="p-4 text-zinc-400">{{ $l->entity }} #{{ $l->entity_id }}</td><td class="p-4 text-zinc-500">{{ $l->ip }}</td></tr>@endforeach</tbody></table></div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
