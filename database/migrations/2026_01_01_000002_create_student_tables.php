<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('cpf',14)->nullable(); $t->string('rg')->nullable();
            $t->date('birth_date')->nullable(); $t->string('gender',12)->nullable();
            $t->string('email')->nullable(); $t->string('phone')->nullable(); $t->string('whatsapp')->nullable();
            $t->string('photo')->nullable(); $t->string('address')->nullable(); $t->string('city')->nullable();
            $t->string('state',2)->nullable(); $t->string('zip',9)->nullable();
            $t->string('emergency_contact')->nullable(); $t->text('notes')->nullable();
            $t->string('status')->default('active'); $t->string('member_code')->unique();
            $t->string('qr_token'); $t->string('goal')->nullable(); $t->string('source')->nullable();
            $t->timestamps(); $t->softDeletes();
            $t->index(['gym_id','status']); $t->index(['gym_id','branch_id']); $t->index(['gym_id','name']);
        });
        Schema::create('plans', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->string('name'); $t->text('description')->nullable(); $t->decimal('price',10,2);
            $t->string('period')->default('monthly'); $t->integer('duration_months')->default(1);
            $t->decimal('signup_fee',10,2)->default(0); $t->json('allowed_days')->nullable();
            $t->string('allowed_hours')->nullable(); $t->integer('class_limit')->nullable();
            $t->boolean('all_branches')->default(true); $t->boolean('promo')->default(false);
            $t->boolean('recurring')->default(true); $t->boolean('corporate')->default(false);
            $t->boolean('trial')->default(false); $t->boolean('active')->default(true); $t->timestamps();
            $t->index(['gym_id','active']);
        });
        Schema::create('memberships', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->foreignId('plan_id')->constrained(); $t->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('coach_id')->nullable()->constrained('teachers')->nullOnDelete();
            $t->date('starts_at'); $t->date('ends_at'); $t->decimal('price',10,2); $t->decimal('discount',10,2)->default(0);
            $t->string('status')->default('pending'); $t->string('payment_method')->nullable();
            $t->text('notes')->nullable(); $t->timestamp('paused_at')->nullable(); $t->timestamps();
            $t->index(['gym_id','status']); $t->index(['student_id','status']); $t->index(['gym_id','ends_at']);
        });
        Schema::create('contracts', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('membership_id')->constrained()->cascadeOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->string('title'); $t->longText('content')->nullable(); $t->integer('version')->default(1);
            $t->string('status')->default('draft'); $t->string('file_path')->nullable();
            $t->timestamp('signed_at')->nullable(); $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('contracts'); Schema::dropIfExists('memberships');
        Schema::dropIfExists('plans'); Schema::dropIfExists('students');
    }
};
