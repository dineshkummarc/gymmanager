<div class="max-w-3xl">
  <div class="flex items-center gap-2 mb-6">@for($i=1;$i<=4;$i++)<div class="flex-1 h-2 rounded-full {{ $step>=$i?'bg-blue-600':'bg-[#27272A]' }}"></div>@endfor</div>
  <div class="card p-6">
    @if($step===1)<h2 class="font-bold text-lg mb-4">1 · Dados pessoais</h2>
      <div class="grid md:grid-cols-2 gap-4"><input wire:model="data.name" class="input" placeholder="Nome completo*"><input wire:model="data.cpf" class="input" placeholder="CPF"><input wire:model="data.birth_date" type="date" class="input"><select wire:model="data.branch_id" class="input"><option value="">Unidade*</option>@foreach($branches as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select></div>
    @elseif($step===2)<h2 class="font-bold text-lg mb-4">2 · Contato e endereço</h2>
      <div class="grid md:grid-cols-2 gap-4"><input wire:model="data.email" class="input" placeholder="E-mail"><input wire:model="data.phone" class="input" placeholder="Telefone"><input wire:model="data.whatsapp" class="input" placeholder="WhatsApp"><input wire:model="data.emergency_contact" class="input" placeholder="Contato de emergência"><input wire:model="data.address" class="input md:col-span-2" placeholder="Endereço"><input wire:model="data.city" class="input" placeholder="Cidade"><input wire:model="data.zip" class="input" placeholder="CEP"></div>
    @elseif($step===3)<h2 class="font-bold text-lg mb-4">3 · Plano e status</h2>
      <div class="grid md:grid-cols-2 gap-4"><select wire:model="data.plan_id" class="input"><option value="">Plano (gera matrícula depois)</option>@foreach($plans as $p)<option value="{{ $p->id }}">{{ $p->name }} — R$ {{ $p->price }}</option>@endforeach</select><select wire:model="data.status" class="input"><option value="active">Ativo</option><option value="pending">Pendente</option><option value="inactive">Inativo</option></select><textarea wire:model="data.notes" class="input md:col-span-2" placeholder="Observações"></textarea></div>
    @else<h2 class="font-bold text-lg mb-4">4 · Revisão e liberação</h2>
      <div class="text-sm text-zinc-400 space-y-1"><p><b class="text-white">{{ $data['name'] }}</b> · {{ $data['email'] }}</p><p>Unidade: {{ $branches->find($data['branch_id'])?->name ?? '—' }}</p><p>Ao salvar, o QR Code de acesso será gerado automaticamente.</p></div>
    @endif
    <div class="flex gap-3 mt-6">@if($step>1)<button wire:click="prev" class="btn-ghost">Voltar</button>@endif @if($step<4)<button wire:click="next" class="btn-primary ml-auto">Continuar</button>@else<button wire:click="save" class="btn-primary ml-auto">Salvar e liberar acesso</button>@endif</div>
  </div>
</div>
