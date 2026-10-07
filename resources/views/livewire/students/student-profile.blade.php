<div>
  <div class="card p-6 flex flex-wrap items-center gap-4 mb-4">
    <div class="w-16 h-16 rounded-2xl bg-blue-600/20 text-blue-400 flex items-center justify-center text-2xl font-black">{{ substr($student->name,0,1) }}</div>
    <div><h1 class="text-xl font-extrabold">{{ $student->name }}</h1><p class="text-sm text-zinc-500">{{ $student->member_code }} · {{ $student->branch->name ?? '' }} · {{ $student->email }}</p></div>
    <span class="badge bg-green-600/15 text-green-400 ml-auto">{{ $student->status }}</span>
    <a href="/memberships?student={{ $student->id }}" class="btn-primary !py-2">+ Nova matrícula</a>
  </div>
  <div class="flex gap-2 mb-4 flex-wrap">@foreach(['overview'=>'Visão geral','billing'=>'Pagamentos','training'=>'Treinos','presence'=>'Presenças'] as $k=>$l)<button wire:click="$set('tab','{{ $k }}')" class="btn-ghost {{ $tab===$k?'!border-blue-600 !text-white':'' }}">{{ $l }}</button>@endforeach</div>
  @if($tab==='overview')
  <div class="grid md:grid-cols-3 gap-4">
    <div class="card p-5"><h3 class="font-bold mb-2">Matrícula</h3>@if($student->activeMembership)<p class="text-sm">{{ $student->activeMembership->plan->name }}</p><p class="text-xs text-zinc-500">até {{ $student->activeMembership->ends_at->format('d/m/Y') }}</p>@else<p class="text-sm text-zinc-500">Sem matrícula ativa</p>@endif</div>
    <div class="card p-5"><h3 class="font-bold mb-2">QR de acesso</h3><div id="qrbox" class="bg-white p-3 rounded-xl w-fit"></div><div class="font-mono text-[11px] text-zinc-500 mt-2 break-all">{{ $student->member_code }}</div></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>document.addEventListener('DOMContentLoaded',()=>{ if(window.QRCode) new QRCode(document.getElementById('qrbox'),{text:@js($student->member_code.':'.$student->qr_token),width:140,height:140}); });</script>
    <div class="card p-5"><h3 class="font-bold mb-2">Últimos check-ins</h3>@foreach($student->checkins->take(5) as $c)<p class="text-xs text-zinc-400">{{ $c->checked_in_at->format('d/m H:i') }} — {{ $c->allowed?'✅':'⛔ '.$c->deny_reason }}</p>@endforeach</div>
  </div>
  @elseif($tab==='billing')
  <div class="card overflow-hidden"><table class="w-full text-sm"><thead><tr class="text-left text-xs text-zinc-500 uppercase border-b border-[#27272A]"><th class="p-3">Descrição</th><th class="p-3">Vencimento</th><th class="p-3">Valor</th><th class="p-3">Status</th></tr></thead><tbody>@foreach($student->invoices as $i)<tr class="border-b border-[#1c1c1f]"><td class="p-3">{{ $i->description }}</td><td class="p-3">{{ $i->due_date->format('d/m/Y') }}</td><td class="p-3">R$ {{ number_format($i->total(),2,',','.') }}</td><td class="p-3"><span class="badge bg-zinc-700/30 text-zinc-300">{{ $i->status }}</span></td></tr>@endforeach</tbody></table></div>
  @elseif($tab==='training')
  <div class="grid gap-3">@foreach($student->workouts as $w)<div class="card p-4"><b>{{ $w->name }}</b> <span class="text-xs text-zinc-500">{{ $w->goal }} · expira {{ $w->expires_at?->format('d/m/Y') }}</span></div>@endforeach</div>
  @else
  <div class="card p-5"><h3 class="font-bold mb-2">Frequência ({{ $student->checkins->count() }} presenças)</h3>@foreach($student->checkins->take(15) as $c)<p class="text-xs text-zinc-400">{{ $c->checked_in_at->format('d/m/Y H:i') }}</p>@endforeach</div>
  @endif
</div>
