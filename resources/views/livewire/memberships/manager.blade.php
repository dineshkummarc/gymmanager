<div>
  <h1 class="text-2xl font-extrabold mb-4">Matrículas</h1>
  <div class="card p-5 mb-4"><h3 class="font-bold mb-3">Nova matrícula (gera mensalidades + contrato)</h3>
    <div class="grid md:grid-cols-4 gap-3">
      <select wire:model="student_id" class="input md:col-span-2"><option value="">Aluno...</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->name }} ({{ $s->member_code }})</option>@endforeach</select>
      <select wire:model.live="plan_id" class="input"><option value="">Plano...</option>@foreach($plans as $p)<option value="{{ $p->id }}">{{ $p->name }} — R$ {{ $p->price }}</option>@endforeach</select>
      <select wire:model="branch_id" class="input"><option value="">Unidade...</option>@foreach($branches as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select>
      <input wire:model="starts_at" type="date" class="input"><input wire:model="price" type="number" step="0.01" class="input" placeholder="Valor"><input wire:model="discount" type="number" step="0.01" class="input" placeholder="Desconto">
      <select wire:model="installments" class="input">@for($i=1;$i<=12;$i++)<option value="{{ $i }}">{{ $i }}x</option>@endfor</select>
      <select wire:model="payment_method" class="input"><option value="pix">PIX</option><option value="cash">Dinheiro</option><option value="credit_card">Crédito</option><option value="debit_card">Débito</option><option value="boleto">Boleto</option></select>
      <button wire:click="save" class="btn-primary md:col-span-4">Criar matrícula</button>
    </div></div>
  <div class="flex gap-2 mb-4"><input wire:model.live.debounce.300ms="search" class="input !w-64" placeholder="Buscar aluno..."><select wire:model.live="status" class="input !w-44"><option value="">Todos</option><option value="active">Ativa</option><option value="cancelled">Cancelada</option><option value="expired">Expirada</option><option value="paused">Pausada</option></select></div>
  <div class="card overflow-hidden"><table class="w-full text-sm"><thead><tr class="text-left text-xs text-zinc-500 uppercase border-b border-[#27272A]"><th class="p-4">Aluno</th><th class="p-4">Plano</th><th class="p-4">Período</th><th class="p-4">Valor</th><th class="p-4">Status</th><th class="p-4"></th></tr></thead>
  <tbody>@foreach($items as $m)<tr class="border-b border-[#1c1c1f] table-row"><td class="p-4 font-semibold">{{ $m->student->name }}</td><td class="p-4 text-zinc-400">{{ $m->plan->name }}</td><td class="p-4 text-zinc-400">{{ $m->starts_at->format('d/m/Y') }} → {{ $m->ends_at->format('d/m/Y') }}</td><td class="p-4 font-bold">R$ {{ number_format($m->total(),2,',','.') }}</td><td class="p-4"><span class="badge {{ $m->status==='active'?'bg-green-600/15 text-green-400':'bg-zinc-700/30 text-zinc-400' }}">{{ $m->status }}</span></td><td class="p-4 text-right">@if($m->status==='active')<button wire:click="cancel({{ $m->id }})" wire:confirm="Cancelar matrícula e baixar faturas pendentes?" class="btn-ghost !py-1.5">Cancelar</button>@endif</td></tr>@endforeach</tbody></table></div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
