<div class="max-w-3xl">
  @if(!$register)<div class="card p-10 text-center"><p class="text-zinc-500 mb-4">Caixa fechado.</p><button wire:click="open" class="btn-primary">Abrir caixa</button></div>
  @else<div class="card p-6 mb-4"><div class="flex items-center"><h2 class="font-bold">Caixa aberto</h2><span class="ml-auto text-3xl font-extrabold text-green-400">R$ {{ number_format($register->balance(),2,',','.') }}</span></div>
    <div class="grid md:grid-cols-3 gap-3 mt-4"><input type="number" step="0.01" wire:model="amount" class="input" placeholder="Valor"><select wire:model="kind" class="input"><option value="in">Entrada</option><option value="out">Saída (sangria)</option></select><input wire:model="description" class="input" placeholder="Descrição"></div>
    <div class="flex gap-2 mt-4"><button wire:click="add" class="btn-primary">Lançar</button><button wire:click="close" class="btn-ghost ml-auto">Fechar caixa</button></div></div>
  <div class="card overflow-hidden"><table class="w-full text-sm"><tbody>@foreach($register->movements()->latest()->get() as $m)<tr class="border-b border-[#1c1c1f]"><td class="p-3">{{ $m->description }}</td><td class="p-3 text-right font-bold {{ $m->kind==='in'?'text-green-400':'text-red-400' }}">{{ $m->kind==='in'?'+':'-' }} R$ {{ number_format($m->amount,2,',','.') }}</td></tr>@endforeach</tbody></table></div>@endif
</div>
