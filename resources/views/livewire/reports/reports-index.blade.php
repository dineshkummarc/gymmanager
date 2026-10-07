<div>
  <h1 class="text-2xl font-extrabold mb-4">Relatórios</h1>
  <div class="flex flex-wrap gap-2 mb-4"><a href="/reports/export/students" class="btn-ghost">⬇ Alunos CSV</a><a href="/reports/export/invoices" class="btn-ghost">⬇ Faturas CSV</a><a href="/reports/export/payments" class="btn-ghost">⬇ Pagamentos CSV</a><a href="/reports/export/checkins" class="btn-ghost">⬇ Check-ins CSV</a></div>
  <div class="grid md:grid-cols-3 gap-4 mb-4">
    <div class="card p-5"><h3 class="font-bold mb-2">Retenção / Churn (30d)</h3><div class="text-3xl font-black text-red-400">{{ $churn['rate'] }}%</div><p class="text-xs text-zinc-500">{{ $churn['cancelled'] }} cancelamentos · {{ $churn['active'] }} ativas</p></div>
    <div class="card p-5"><h3 class="font-bold mb-2">Ocupação das aulas</h3>@foreach(array_slice($occ,0,5) as $o)<div class="flex justify-between text-xs py-1"><span>{{ $o['name'] }} {{ $o['date'] }}</span><b>{{ $o['occ'] }}%</b></div>@endforeach</div>
    <div class="card p-5"><h3 class="font-bold mb-2">Inadimplência por faixa</h3>@foreach($delinq as $k=>$v)<div class="flex justify-between text-xs py-1"><span>{{ $k }} dias</span><b>{{ $v['count'] }} · R$ {{ number_format($v['total'],0,',','.') }}</b></div>@endforeach</div>
  </div>
  <div class="card p-5"><h3 class="font-bold mb-3">Receita 12 meses</h3><canvas id="chR" height="100"></canvas></div>
</div>
<script>document.addEventListener('DOMContentLoaded',()=>{new Chart(document.getElementById('chR'),{type:'bar',data:{labels:@js($revenue['labels']),datasets:[{data:@js($revenue['data']),backgroundColor:'#2563EB',borderRadius:6}]},options:{plugins:{legend:{display:false}}}})});</script>
