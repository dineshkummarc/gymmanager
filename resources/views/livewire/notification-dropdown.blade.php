<div x-data="{open:false}" class="relative">
  <button @click="open=!open" class="btn-ghost !px-3 relative">🔔@if($unread)<span class="absolute -top-1 -right-1 w-5 h-5 bg-red-600 rounded-full text-[10px] flex items-center justify-center">{{ $unread }}</span>@endif</button>
  <div x-show="open" @click.outside="open=false" class="absolute right-0 top-11 w-80 card p-2 z-50">
    <div class="flex items-center p-2"><b class="text-sm">Notificações</b><button wire:click="readAll" class="ml-auto text-xs text-blue-400">Marcar lidas</button></div>
    @forelse($items as $n)<div class="p-2.5 rounded-lg hover:bg-white/5 text-sm"><div class="font-semibold text-[13px]">{{ $n->data['title'] ?? 'Aviso' }}</div><div class="text-xs text-zinc-500">{{ $n->data['body'] ?? '' }}</div></div>@empty<p class="p-4 text-xs text-zinc-500 text-center">Nenhuma notificação.</p>@endforelse
  </div>
</div>
