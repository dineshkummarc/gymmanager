<div x-data="{open:false}" class="relative" @click.outside="open=false">
  <input id="global-search-input" x-on:focus="open=true" wire:model.live.debounce.300ms="q" class="input !py-2" placeholder="Buscar... (Ctrl+K)">
  @if(strlen($q)>=2)<div x-show="open" class="absolute top-11 left-0 right-0 card p-2 z-50 space-y-1">
    @foreach($res['students']??[] as $s)<a href="/students/{{ $s->id }}" class="block p-2 rounded-lg hover:bg-white/5 text-sm">👤 {{ $s->name }} <span class="text-zinc-500 text-xs">{{ $s->member_code }}</span></a>@endforeach
    @foreach($res['leads']??[] as $l)<a href="/leads" class="block p-2 rounded-lg hover:bg-white/5 text-sm">🧲 {{ $l->name }}</a>@endforeach
    @foreach($res['products']??[] as $p)<a href="/inventory" class="block p-2 rounded-lg hover:bg-white/5 text-sm">📦 {{ $p->name }}</a>@endforeach
  </div>@endif
</div>
