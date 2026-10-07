<div>
  <h1 class="text-2xl font-extrabold mb-4">Professores & Personal Trainers</h1>
  <div class="card p-4 mb-4 grid md:grid-cols-3 gap-3"><input wire:model="name" class="input" placeholder="Nome"><select wire:model="specialty" class="input"><option>Musculação</option><option>Funcional</option><option>CrossFit</option><option>Pilates</option><option>Yoga</option><option>Cardio</option><option>Personal</option></select><button wire:click="save" class="btn-primary">Cadastrar</button></div>
  <div class="card overflow-hidden"><table class="w-full text-sm"><thead><tr class="text-left text-xs text-zinc-500 uppercase border-b border-[#27272A]"><th class="p-4">Nome</th><th class="p-4">Especialidade</th><th class="p-4">Comissão</th><th class="p-4">Status</th></tr></thead><tbody>@foreach($teachers as $t)<tr class="border-b border-[#1c1c1f]"><td class="p-4 font-semibold">{{ $t->name }}</td><td class="p-4 text-zinc-400">{{ $t->specialty }}</td><td class="p-4">{{ $t->commission_rate }}%</td><td class="p-4"><span class="badge bg-green-600/15 text-green-400">{{ $t->status }}</span></td></tr>@endforeach</tbody></table></div>
  <div class="mt-4">{{ $teachers->links() }}</div>
</div>
