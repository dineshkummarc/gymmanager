<div>
  <div class="card p-4 mb-4 flex flex-wrap gap-3"><input wire:model="name" class="input !w-56" placeholder="Nome do lead"><input wire:model="phone" class="input !w-44" placeholder="WhatsApp"><button wire:click="add" class="btn-primary">+ Novo lead</button></div>
  <div class="grid md:grid-cols-4 xl:grid-cols-8 gap-3">
    @foreach($columns as $key=>$label)<div class="card p-3 min-h-[200px]">
      <div class="font-bold text-xs uppercase tracking-wider text-zinc-400 mb-3">{{ $label }} ({{ $grouped->get($key,collect())->count() }})</div>
      <div class="space-y-2">@foreach($grouped->get($key,[]) as $lead)<div class="bg-black/40 border border-[#27272A] rounded-lg p-2.5">
        <div class="font-semibold text-sm">{{ $lead->name }}</div><div class="text-[11px] text-zinc-500">{{ $lead->phone }}</div>
        <div class="flex gap-1 mt-2 flex-wrap">
          @if($key!=='won'&&$key!=='lost')<button wire:click="move({{ $lead->id }},'won')" class="text-[11px] text-green-400">✓</button><button wire:click="convert({{ $lead->id }})" class="text-[11px] text-blue-400">Converter</button>@endif
          @foreach(['contacted'=>'📞','trial'=>'🎯','proposal'=>'📄','negotiation'=>'🤝','lost'=>'✕'] as $s=>$e)<button wire:click="move({{ $lead->id }},'{{ $s }}')" class="text-[11px]" title="{{ $s }}">{{ $e }}</button>@endforeach
        </div></div>@endforeach</div>
    </div>@endforeach
  </div>
</div>
