# GymManager — SaaS de Gestão para Academias

Plataforma completa (ERP + CRM + operacional) para academias, studios, boxes e redes. TALL Stack: **Laravel 12 + Livewire 3 + Alpine.js + Tailwind CSS 4**.

## Features

- **Multi-tenant**: `Gym → Branches → Users/Students/...` com global scope (`BelongsToGym`), middleware `gym.context` e Policies.
- **Unidades**: múltiplas filiais, filtro por unidade no dashboard, financeiro e relatórios.
- **Auth**: login/logout, remember me, sessão em banco, `last_login_at`, activity log.
- **11 papéis** (`UserRole` enum) com mapa de permissões + middleware `perm` + Policies + Gates.
- **Dashboard executivo**: alunos ativos/novos/inativos, vencendo, pendências, receita, inadimplência, check-ins, aulas, leads + gráficos (receita 12m, check-ins 14d, alunos por unidade).
- **Alunos**: CRM completo, onboarding em 4 etapas com progresso, perfil em abas (visão geral, pagamentos, treinos, presenças), QR token por aluno.
- **Planos**: mensal/trimestral/semestral/anual, promo, recorrente, corporativo, experimental.
- **Matrículas**: tela dedicada (cria mensalidades parceladas + contrato, cancela com baixa), atalho a partir do perfil do aluno e do onboarding.
- **Contratos**: assinar/ativar, versionamento.
- **Vendas**: planos/produtos/personal/serviços + comissão automática; **Comissões**: pendente/pago.
- **Unidades e usuários**: CRUD de filiais, gestão de acessos por papel.
- **Presenças**: histórico por dia/unidade + radar de baixa frequência (14 dias).
- **Auditoria**: timeline com usuário/ação/entidade/IP.
- **Auth**: recuperação de senha (link por e-mail, driver `log` em dev), "esqueci a senha" no login.
- **QR Code real** no perfil do aluno (qrcodejs, `código:token` assinado via `QrToken`).
- **Exportação CSV**: alunos, faturas, pagamentos, check-ins.
- **Busca global com Ctrl+K**, **painel da Recepção** (operação do dia + ações rápidas), **painel do Professor** (aulas, treinos vencendo, avaliações).
- **Portal do aluno**: reservar/cancelar vaga (com lista de espera) e pagar fatura via PIX.
- **Pagamentos**: parcial, desconto, multa/juros (`Money::fine`), estorno, recibo, comissão de 10% p/ vendedor.
- **Mensalidades**: visões hoje/semana/vencidas/pagas; job marca `overdue` + multa; job gera próxima competência.
- **Inadimplência**: faixas 1–7/8–30/31–60/60+, ações (receber, negociar, notificar).
- **Check-in**: busca com debounce, validação (matrícula, plano, pagamento, unidade, horário) com tela `ACESSO LIBERADO / NEGADO + motivo`.
- **Treinos**: fichas A/B/C, sessões, séries×reps×carga×descanso; biblioteca de 20 exercícios; avaliações físicas com IMC + histórico.
- **Aulas/turmas/reservas**: calendário, capacidade, ocupação, reserva/lista de espera.
- **CRM**: pipeline Kanban em 8 estágios, aula experimental, conversão lead → aluno em 1 clique.
- **Estoque**: produtos, entradas/saídas, alertas de mínimo, valor em estoque.
- **Caixa**: abrir/fechar, sangria, saldo computado.
- **Financeiro**: receitas/despesas, contas a pagar/receber, lucro.
- **Comissões**: por venda/personal, pendente/pago.
- **Notificações**: database notifications + dropdown com badge + centro de notificações (pronto p/ e-mail/WhatsApp/SMS/push).
- **Portal do aluno**: mobile-first (treino do dia, próximas aulas, faturas, check-ins).
- **Busca global** (Ctrl+K style), **auditoria** (`activity_logs` com usuário/ação/IP), **relatórios** (churn, ocupação, inadimplência, receita, exportável).
- **Integrações futuras**: `App\Services` + jobs isolam gateway/pagamento/mensageria (Mercado Pago, Asaas, Stripe, PIX, WhatsApp, catracas).

## Stack

Laravel 12 · Livewire 3 · Alpine.js · Tailwind 4 · Chart.js · SQLite (dev) / MySQL-ready · Queue + Scheduler

## Instalação

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Login demo: **demo@gymmanager.test** / **password**

## Scheduler / Filas

```bash
php artisan queue:work
php artisan schedule:run
```

Jobs: `GenerateInvoicesJob` (mensal), `MarkOverdueInvoicesJob` (diário 01h), `SendDueRemindersJob` (diário 08h).

## Testes

```bash
php artisan test   # 38 testes, 120 assertions — páginas, interações e regras de negócio
```

Cobrem: auth, isolamento de tenant, matrícula→faturas, pagamento→baixa, acesso negado com mensalidade vencida, policy entre academias, cancelamento com baixa de pendências, comissão sobre venda, escopo de faturas por academia, redirect da home e render do login.

## Estrutura

```
app/{Enums,Models,Livewire,Services,Policies,Jobs,Notifications,Support,Http/Middleware}
database/{migrations(5 arquivos temáticos),seeders}
resources/views/{layouts,livewire}
routes/{web,console}.php
tests/Feature/GymManagerTest.php
```

## Roadmap

Assinatura digital · gateway PIX recorrente · app catraca/QR · push · NFS-e · multi-idioma.
