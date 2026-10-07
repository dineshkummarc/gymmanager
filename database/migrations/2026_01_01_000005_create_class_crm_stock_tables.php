<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('gym_classes', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('room')->nullable(); $t->integer('capacity')->default(20);
            $t->dateTime('starts_at'); $t->integer('duration_min')->default(60);
            $t->json('weekdays')->nullable(); $t->string('status')->default('open'); $t->timestamps();
            $t->index(['gym_id','starts_at']);
        });
        Schema::create('class_reservations', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('gym_class_id')->constrained()->cascadeOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->string('status')->default('reserved'); $t->text('notes')->nullable(); $t->timestamps();
            $t->unique(['gym_class_id','student_id']); $t->index(['gym_id','status']);
        });
        Schema::create('leads', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('name'); $t->string('phone'); $t->string('email')->nullable();
            $t->string('source')->nullable(); $t->string('interest')->nullable();
            $t->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $t->string('status')->default('new'); $t->decimal('estimated_value',10,2)->default(0);
            $t->timestamp('next_contact_at')->nullable(); $t->text('notes')->nullable();
            $t->timestamp('trial_at')->nullable(); $t->foreignId('converted_student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->timestamps(); $t->index(['gym_id','status']);
        });
        Schema::create('lead_activities', function (Blueprint $t) {
            $t->id(); $t->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('type')->default('note'); $t->text('description'); $t->timestamps();
        });
        Schema::create('sales', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('kind')->default('plan'); $t->string('description');
            $t->decimal('amount',10,2); $t->string('method')->default('pix'); $t->string('status')->default('paid');
            $t->timestamp('sold_at')->useCurrent(); $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('sku')->nullable(); $t->string('category')->nullable();
            $t->decimal('price',10,2); $t->decimal('cost',10,2)->default(0);
            $t->integer('stock')->default(0); $t->integer('min_stock')->default(5);
            $t->string('barcode')->nullable(); $t->string('image')->nullable(); $t->boolean('active')->default(true); $t->timestamps();
            $t->index(['gym_id','category']);
        });
        Schema::create('stock_movements', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('type'); $t->integer('quantity'); $t->string('reason')->nullable(); $t->timestamps();
        });
        Schema::create('activity_logs', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action'); $t->string('entity')->nullable(); $t->unsignedBigInteger('entity_id')->nullable();
            $t->string('ip')->nullable(); $t->json('meta')->nullable(); $t->timestamps();
            $t->index(['gym_id','created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('activity_logs'); Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('products'); Schema::dropIfExists('sales');
        Schema::dropIfExists('lead_activities'); Schema::dropIfExists('leads');
        Schema::dropIfExists('class_reservations'); Schema::dropIfExists('gym_classes');
    }
};
