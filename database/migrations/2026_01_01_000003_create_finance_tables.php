<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->foreignId('membership_id')->nullable()->constrained()->nullOnDelete();
            $t->string('description'); $t->decimal('amount',10,2); $t->decimal('discount',10,2)->default(0);
            $t->decimal('fine',10,2)->default(0); $t->date('due_date'); $t->timestamp('paid_at')->nullable();
            $t->string('status')->default('pending'); $t->string('reference_month',7)->nullable();
            $t->text('notes')->nullable(); $t->timestamps();
            $t->index(['gym_id','status']); $t->index(['gym_id','due_date']); $t->index(['student_id','status']);
        });
        Schema::create('cash_registers', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('user_id')->constrained(); $t->decimal('opening_balance',10,2)->default(0);
            $t->decimal('closing_balance',10,2)->nullable(); $t->string('status')->default('open');
            $t->timestamp('opened_at')->useCurrent(); $t->timestamp('closed_at')->nullable();
            $t->text('notes')->nullable(); $t->timestamps();
        });
        Schema::create('payments', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('cash_register_id')->nullable()->constrained()->nullOnDelete();
            $t->decimal('amount',10,2); $t->string('method')->default('pix'); $t->string('status')->default('paid');
            $t->timestamp('paid_at')->useCurrent(); $t->string('receipt')->nullable(); $t->text('notes')->nullable(); $t->timestamps();
            $t->index(['gym_id','status']); $t->index(['invoice_id']);
        });
        Schema::create('cash_movements', function (Blueprint $t) {
            $t->id(); $t->foreignId('cash_register_id')->constrained()->cascadeOnDelete();
            $t->string('kind'); $t->string('category')->nullable(); $t->decimal('amount',10,2);
            $t->string('method')->nullable(); $t->string('description')->nullable();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $t->timestamps();
        });
        Schema::create('financial_transactions', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->string('kind'); $t->string('category')->nullable(); $t->string('description');
            $t->decimal('amount',10,2); $t->date('due_date'); $t->timestamp('paid_at')->nullable();
            $t->string('status')->default('pending'); $t->string('cost_center')->nullable(); $t->timestamps();
            $t->index(['gym_id','kind','status']);
        });
        Schema::create('commissions', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->string('beneficiary_type')->nullable(); $t->unsignedBigInteger('beneficiary_id')->nullable();
            $t->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('source')->nullable(); $t->unsignedBigInteger('source_id')->nullable();
            $t->decimal('amount',10,2); $t->decimal('rate',5,2)->default(0);
            $t->string('status')->default('pending'); $t->timestamp('paid_at')->nullable(); $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('commissions'); Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('cash_movements'); Schema::dropIfExists('payments');
        Schema::dropIfExists('cash_registers'); Schema::dropIfExists('invoices');
    }
};
