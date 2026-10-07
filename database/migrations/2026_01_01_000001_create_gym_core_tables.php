<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('gyms', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('slug')->unique(); $t->string('document')->nullable();
            $t->string('email')->nullable(); $t->string('phone')->nullable(); $t->string('city')->nullable();
            $t->string('state',2)->nullable(); $t->string('plan')->default('pro'); $t->boolean('active')->default(true); $t->timestamps();
        });
        Schema::create('branches', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete(); $t->string('name'); $t->string('code');
            $t->string('address')->nullable(); $t->string('phone')->nullable(); $t->string('email')->nullable();
            $t->string('opening_hours')->nullable(); $t->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $t->boolean('active')->default(true); $t->timestamps(); $t->unique(['gym_id','code']);
            $t->index(['gym_id','active']);
        });
        Schema::table('users', function (Blueprint $t) {
            $t->foreignId('gym_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $t->foreignId('branch_id')->nullable()->after('gym_id')->constrained()->nullOnDelete();
            $t->string('role')->default('viewer')->after('email');
            $t->string('phone')->nullable(); $t->string('avatar')->nullable();
            $t->boolean('active')->default(true); $t->timestamp('last_login_at')->nullable();
            $t->index(['gym_id','role']);
        });
        Schema::create('gym_user', function (Blueprint $t) {
            $t->id(); $t->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('role')->default('viewer'); $t->timestamps(); $t->unique(['gym_id','user_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('gym_user');
        Schema::table('users', fn(Blueprint $t)=>$t->dropConstrainedForeignId('gym_id'));
        Schema::dropIfExists('branches'); Schema::dropIfExists('gyms');
    }
};
