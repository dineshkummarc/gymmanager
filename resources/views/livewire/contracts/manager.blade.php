<div>
  <h1 class="text-2xl font-extrabold mb-4">Contratos</h1>
  <div class="flex gap-2 mb-4"><select wire:model.live="status" class="input !w-52"><option value="">Todos status</option><option value="draft">Rascunho</option><option value="pending_signature">Aguard. assinatura</option><option value="active">Ativo</option><option value="expired">Expirado</option><option value="cancelled">Cancelado</option></select></div>
  <div class="grid md:grid-cols-2 gap-4">@foreach($items as $c)<div class="card p-5">
    <div class="flex items-center gap-2"><h3 class="font-bold">{{ $c->title }} <span class="text-xs text-zinc-500">v{{ $c->version }}</span></h3><span class="badge bg-zinc-700/30 text-zinc-300 ml-auto">{{ $c->status }}</span></div>
    <p class="text-xs text-zinc-500 mt-1">{{ $c->student->name }} · {{ $c->membership->plan->name ?? '' }} @if($c->signed_at)· assinado {{ $c->signed_at->format('d/m/Y') }}@endif</p>
    <div class="flex gap-2 mt-3">@if($c->status!=='active')<button wire:click="sign({{ $c->id }})" class="btn-primary !py-1.5">Assinar / Ativar</button>@endif<button wire:click="newVersion({{ $c->id }})" class="btn-ghost !py-1.5">Nova versão</button></div>
  </div>@endforeach</div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
