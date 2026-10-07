<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('teachers', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('cpf',14)->nullable(); $t->string('cref')->nullable();
            $t->string('email')->nullable(); $t->string('phone')->nullable(); $t->string('specialty')->nullable();
            $t->string('photo')->nullable(); $t->decimal('commission_rate',5,2)->default(0);
            $t->string('status')->default('active'); $t->date('hire_date')->nullable(); $t->timestamps();
            $t->index(['gym_id','status']);
        });
        Schema::create('checkins', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->string('method')->default('search'); $t->timestamp('checked_in_at')->useCurrent();
            $t->boolean('allowed')->default(true); $t->string('deny_reason')->nullable();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $t->timestamps();
            $t->index(['gym_id','checked_in_at']); $t->index(['student_id']);
        });
        Schema::create('exercises', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('category')->nullable(); $t->string('muscle_group')->nullable();
            $t->string('equipment')->nullable(); $t->text('description')->nullable(); $t->text('instructions')->nullable();
            $t->string('image')->nullable(); $t->string('video_url')->nullable(); $t->boolean('active')->default(true); $t->timestamps();
        });
        Schema::create('workout_plans', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->foreignId('coach_id')->nullable()->constrained('teachers')->nullOnDelete();
            $t->string('name'); $t->string('goal')->nullable(); $t->date('starts_at'); $t->date('expires_at')->nullable();
            $t->text('notes')->nullable(); $t->string('status')->default('active'); $t->timestamps();
            $t->index(['gym_id','status']); $t->index(['student_id']);
        });
        Schema::create('workout_sessions', function (Blueprint $t) {
            $t->id(); $t->foreignId('workout_plan_id')->constrained()->cascadeOnDelete();
            $t->string('name'); $t->string('day_of_week')->nullable(); $t->integer('order')->default(0);
            $t->text('notes')->nullable(); $t->timestamps();
        });
        Schema::create('workout_exercises', function (Blueprint $t) {
            $t->id(); $t->foreignId('workout_session_id')->constrained()->cascadeOnDelete();
            $t->foreignId('exercise_id')->constrained(); $t->integer('sets')->default(3);
            $t->string('reps')->default('12'); $t->string('load')->nullable(); $t->integer('rest_seconds')->default(60);
            $t->string('tempo')->nullable(); $t->string('distance')->nullable(); $t->integer('order')->default(0);
            $t->text('notes')->nullable(); $t->timestamps();
        });
        Schema::create('physical_assessments', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $t->decimal('weight',5,2); $t->decimal('height',5,2); $t->decimal('body_fat',5,2)->nullable();
            $t->decimal('muscle_mass',5,2)->nullable(); $t->decimal('arm',5,2)->nullable(); $t->decimal('chest',5,2)->nullable();
            $t->decimal('waist',5,2)->nullable(); $t->decimal('hip',5,2)->nullable(); $t->decimal('thigh',5,2)->nullable();
            $t->decimal('calf',5,2)->nullable(); $t->text('notes')->nullable(); $t->date('measured_at'); $t->timestamps();
            $t->index(['student_id','measured_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('physical_assessments'); Schema::dropIfExists('workout_exercises');
        Schema::dropIfExists('workout_sessions'); Schema::dropIfExists('workout_plans');
        Schema::dropIfExists('exercises'); Schema::dropIfExists('checkins'); Schema::dropIfExists('teachers');
    }
};
