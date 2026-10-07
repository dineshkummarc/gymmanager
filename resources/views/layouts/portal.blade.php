<!DOCTYPE html><html lang="pt-BR" class="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Portal do Aluno — GymManager</title>@vite(['resources/css/app.css','resources/js/app.js'])@livewireStyles</head>
<body class="min-h-screen max-w-md mx-auto border-x border-[#27272A]">
<header class="p-4 flex items-center gap-3 border-b border-[#27272A] bg-[#111113] sticky top-0 z-10">
<div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center font-black">G</div>
<div class="font-extrabold">Portal do Aluno</div>
<form method="POST" action="/logout" class="ml-auto">@csrf<button class="text-zinc-500 text-sm">Sair</button></form>
</header>
<main class="p-4 pb-20">
@if(session('ok'))<div class="mb-3 p-3 rounded-xl bg-green-600/15 border border-green-600/30 text-green-400 text-sm">{{ session('ok') }}</div>@endif
{{ $slot }}</main>
<nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-[#111113] border-t border-[#27272A] flex text-[11px] text-zinc-400">
<a href="/student-portal" class="flex-1 py-3 text-center">🏠<br>Início</a><a href="/student-portal#treino" class="flex-1 py-3 text-center">🏋️<br>Treino</a><a href="/student-portal#faturas" class="flex-1 py-3 text-center">💳<br>Faturas</a><a href="/dashboard" class="flex-1 py-3 text-center">⚙️<br>Admin</a>
</nav>
@livewireScripts</body></html>
