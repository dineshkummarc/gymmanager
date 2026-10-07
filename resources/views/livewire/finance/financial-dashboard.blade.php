<div>
  <div class="grid grid-cols-3 gap-4 mb-5">
    <div class="card p-5"><div class="text-xs text-zinc-500 uppercase">Receitas</div><div class="text-2xl font-extrabold text-green-400">R$ {{ number_format($in,2,',','.') }}</div></div>
    <div class="card p-5"><div class="text-xs text-zinc-500 uppercase">Despesas</div><div class="text-2xl font-extrabold text-red-400">R$ {{ number_format($out,2,',','.') }}</div></div>
    <div class="card p-5"><div class="text-xs text-zinc-500 uppercase">Lucro</div><div class="text-2xl font-extrabold text-blue-400">R$ {{ number_format($profit,2,',','.') }}</div></div>
  </div>
  <div class="card p-5 mb-4"><h3 class="font-bold mb-3">Novo lançamento</h3><div class="grid md:grid-cols-4 gap-3"><input wire:model="description" class="input" placeholder="Descrição"><input type="number" step="0.01" wire:model="amount" class="input" placeholder="Valor"><select wire:model="kind" class="input"><option value="income">Receita</option><option value="expense">Despesa</option></select><button wire:click="save" class="btn-primary">Lançar</button></div></div>
  <div class="card overflow-hidden"><table class="w-full text-sm"><tbody>@foreach($txs as $t)<tr class="border-b border-[#1c1c1f]"><td class="p-3">{{ $t->description }} <span class="text-xs text-zinc-500">{{ $t->category }}</span></td><td class="p-3 text-right font-bold {{ $t->kind==='income'?'text-green-400':'text-red-400' }}">R$ {{ number_format($t->amount,2,',','.') }}</td></tr>@endforeach</tbody></table></div>
  <div class="mt-4">{{ $txs->links() }}</div>
</div>
