<div>
  <div class="flex flex-wrap items-center gap-3 mb-6">
    <h1 class="text-2xl font-extrabold tracking-tight">Visão executiva</h1>
    <select wire:model.live="branchId" class="input !w-56 ml-auto"><option value="">Todas as unidades</option>@foreach($branchList as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    @php $cards=[['Alunos ativos',$stats['active_students'],'👥'],['Receita do mês','R$ '.number_format($stats['revenue_month'],2,',','.'),'💰'],['Inadimplência','R$ '.number_format($stats['overdue_total'],2,',','.'),'⚠️'],['Check-ins hoje',$stats['checkins_today'],'🎫'],['Matrículas vencendo',$stats['expiring'],'📝'],['Faturas pendentes',$stats['pending_invoices'],'🧾'],['Aulas hoje',$stats['classes_today'],'🗓️'],['Leads abertos',$stats['leads_open'],'🧲']]; @endphp
    @foreach($cards as [$l,$v,$i])
    <div class="card p-5"><div class="flex items-center justify-between"><span class="text-xs text-zinc-500 uppercase tracking-wider">{{ $l }}</span><span class="text-xl">{{ $i }}</span></div><div class="text-2xl font-extrabold mt-2">{{ $v }}</div></div>
    @endforeach
  </div>
  <div class="grid lg:grid-cols-2 gap-4 mt-4">
    <div class="card p-5"><h3 class="font-bold mb-3">Receita mensal (12m)</h3><canvas id="chRev" height="120"></canvas></div>
    <div class="card p-5"><h3 class="font-bold mb-3">Check-ins (14 dias)</h3><canvas id="chChk" height="120"></canvas></div>
  </div>
  <div class="grid lg:grid-cols-2 gap-4 mt-4">
    <div class="card p-5"><h3 class="font-bold mb-3">Alunos por unidade</h3><canvas id="chBr" height="120"></canvas></div>
    <div class="card p-5"><h3 class="font-bold mb-3">Receita prevista vs recebida</h3>
      <div class="text-3xl font-extrabold text-blue-400">R$ {{ number_format($stats['projected'],2,',','.') }}</div>
      <p class="text-sm text-zinc-500 mt-1">previsto para este mês · {{ $stats['overdue_count'] }} faturas vencidas</p>
      <div class="mt-4 flex gap-2"><a href="/payments" class="btn-primary">Cobrar mensalidades</a><a href="/checkin" class="btn-ghost">Abrir check-in</a></div>
    </div>
  </div>
</div>
<script>
document.addEventListener('livewire:navigated', draw); document.addEventListener('DOMContentLoaded', draw);
function draw(){
  const o={color:'#A1A1AA',grid:{color:'#27272A'},ticks:{color:'#A1A1AA'}};
  if(window._c1)window._c1.destroy(); if(window._c2)window._c2.destroy(); if(window._c3)window._c3.destroy();
  window._c1=new Chart(document.getElementById('chRev'),{type:'bar',data:{labels:@js($revenue['labels']),datasets:[{data:@js($revenue['data']),backgroundColor:'#2563EB',borderRadius:6}]},options:{plugins:{legend:{display:false}},scales:{x:{...o},y:{...o}}}});
  window._c2=new Chart(document.getElementById('chChk'),{type:'line',data:{labels:@js($checkins['labels']),datasets:[{data:@js($checkins['data']),borderColor:'#3B82F6',tension:.4,fill:true,backgroundColor:'rgba(59,130,246,.15)'}]},options:{plugins:{legend:{display:false}},scales:{x:{...o},y:{...o}}}});
  window._c3=new Chart(document.getElementById('chBr'),{type:'doughnut',data:{labels:@js($branches['labels']),datasets:[{data:@js($branches['data']),backgroundColor:['#2563EB','#3B82F6','#1D4ED8','#60A5FA','#93C5FD']}]},options:{plugins:{legend:{position:'bottom',labels:{color:'#A1A1AA'}}}}});
}
</script>
