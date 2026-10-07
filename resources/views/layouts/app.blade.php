<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title ?? 'GymManager' }} — GymManager</title>
@vite(['resources/css/app.css','resources/js/app.js'])
@livewireStyles
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="min-h-screen">
<div x-data="{ sidebar: window.innerWidth > 1024 }" class="flex min-h-screen">
  <aside :class="sidebar ? 'w-64' : 'w-0 overflow-hidden'" class="transition-all duration-200 bg-[#111113] border-r border-[#27272A] min-h-screen flex flex-col fixed lg:static z-40 h-screen">
    <div class="p-5 flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-black text-lg">G</div>
      <div><div class="font-extrabold tracking-tight">GymManager</div><div class="text-[11px] text-zinc-500">{{ auth()->user()->gym->name ?? 'SaaS Fitness' }}</div></div>
    </div>
    <div class="px-4 pb-2">@livewire('global-search')</div>
    <nav class="flex-1 overflow-y-auto px-3 pb-6 text-sm">
      <a href="/dashboard" class="sidebar-link {{ request()->is('dashboard')?'active':'' }}">📊 Dashboard</a>
      <a href="/reception" class="sidebar-link {{ request()->is('reception*')?'active':'' }}">🏠 Recepção</a>
      <a href="/teacher" class="sidebar-link {{ request()->is('teacher*')?'active':'' }}">🧑‍🏫 Meu painel</a>
      <div class="sidebar-group">Alunos</div>
      <a href="/students" class="sidebar-link {{ request()->is('students*')?'active':'' }}">👥 Alunos</a>
      <a href="/memberships" class="sidebar-link {{ request()->is('memberships*')?'active':'' }}">📝 Matrículas</a>
      <a href="/plans" class="sidebar-link {{ request()->is('plans*')?'active':'' }}">💳 Planos</a>
      <a href="/contracts" class="sidebar-link {{ request()->is('contracts*')?'active':'' }}">📄 Contratos</a>
      <a href="/checkin" class="sidebar-link {{ request()->is('checkin')?'active':'' }}">🎫 Check-in</a>
      <a href="/checkins" class="sidebar-link {{ request()->is('checkins*')?'active':'' }}">📋 Presenças</a>
      <div class="sidebar-group">Treinos</div>
      <a href="/workouts" class="sidebar-link {{ request()->is('workouts*')?'active':'' }}">🏋️ Fichas</a>
      <a href="/assessments" class="sidebar-link {{ request()->is('assessments*')?'active':'' }}">📏 Avaliações</a>
      <div class="sidebar-group">Aulas</div>
      <a href="/classes" class="sidebar-link {{ request()->is('classes*')?'active':'' }}">🗓️ Aulas</a>
      <div class="sidebar-group">CRM</div>
      <a href="/leads" class="sidebar-link {{ request()->is('leads*')?'active':'' }}">🧲 Leads / Pipeline</a>
      <a href="/sales" class="sidebar-link {{ request()->is('sales*')?'active':'' }}">🛒 Vendas</a>
      <div class="sidebar-group">Equipe</div>
      <a href="/teachers" class="sidebar-link {{ request()->is('teachers*')?'active':'' }}">🧑‍🏫 Professores</a>
      <a href="/users" class="sidebar-link {{ request()->is('users*')?'active':'' }}">🧑‍💼 Usuários</a>
      <a href="/branches" class="sidebar-link {{ request()->is('branches*')?'active':'' }}">🏢 Unidades</a>
      <div class="sidebar-group">Estoque</div>
      <a href="/inventory" class="sidebar-link {{ request()->is('inventory*')?'active':'' }}">📦 Produtos</a>
      <div class="sidebar-group">Financeiro</div>
      <a href="/payments" class="sidebar-link {{ request()->is('payments*')?'active':'' }}">💰 Mensalidades</a>
      <a href="/cash" class="sidebar-link {{ request()->is('cash*')?'active':'' }}">🧾 Caixa</a>
      <a href="/financial" class="sidebar-link {{ request()->is('financial*')?'active':'' }}">📈 Financeiro</a>
      <a href="/commissions" class="sidebar-link {{ request()->is('commissions*')?'active':'' }}">🤝 Comissões</a>
      <div class="sidebar-group">Sistema</div>
      <a href="/reports" class="sidebar-link {{ request()->is('reports*')?'active':'' }}">📑 Relatórios</a>
      <a href="/audit" class="sidebar-link {{ request()->is('audit*')?'active':'' }}">🕵️ Auditoria</a>
      <a href="/student-portal" class="sidebar-link">📱 Portal do aluno</a>
    </nav>
    <div class="p-4 border-t border-[#27272A]">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center font-bold">{{ substr(auth()->user()->name,0,1) }}</div>
        <div class="flex-1 min-w-0"><div class="text-sm font-semibold truncate">{{ auth()->user()->name }}</div><div class="text-[11px] text-zinc-500">{{ \App\Enums\UserRole::tryFrom(auth()->user()->role)?->label() }}</div></div>
        <form method="POST" action="/logout">@csrf<button class="text-zinc-500 hover:text-white text-lg" title="Sair">⏻</button></form>
      </div>
    </div>
  </aside>
  <div class="flex-1 min-w-0">
    <header class="sticky top-0 z-30 bg-[#09090B]/90 backdrop-blur border-b border-[#27272A] px-4 lg:px-8 py-3 flex items-center gap-3">
      <button @click="sidebar=!sidebar" class="lg:hidden btn-ghost !px-3">☰</button>
      <div class="font-bold text-lg tracking-tight">{{ $title ?? 'Dashboard' }}</div>
      <div class="ml-auto flex items-center gap-3">
        @livewire('notification-dropdown')
        <span class="badge bg-blue-600/15 text-blue-400">{{ auth()->user()->branch->name ?? 'Todas unidades' }}</span>
      </div>
    </header>
    <main class="p-4 lg:p-8 max-w-[1400px] mx-auto">
      @if(session('ok'))<div class="mb-4 p-3 rounded-xl bg-green-600/15 border border-green-600/30 text-green-400 text-sm">{{ session('ok') }}</div>@endif
      @if(session('err'))<div class="mb-4 p-3 rounded-xl bg-red-600/15 border border-red-600/30 text-red-400 text-sm">{{ session('err') }}</div>@endif
      {{ $slot }}
    </main>
  </div>
</div>
@livewireScripts
<script>document.addEventListener('keydown',e=>{ if((e.ctrlKey||e.metaKey)&&e.key.toLowerCase()==='k'){ e.preventDefault(); document.getElementById('global-search-input')?.focus(); } });</script>
</body>
</html>
