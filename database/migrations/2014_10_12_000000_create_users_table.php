<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('new user');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password');
            $table->boolean('verified')->default(false);
            $table->string('avatar')->default('default.jpg');
            $table->enum('provider',['email','google'])->default('email');
            $table->enum('rules',['Chef', 'Assistant Chef', 'Trainee', 'Owner', 'Guest'])->default('Guest');
            $table->text('provider_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropForeign(['section_id']);

            $table->dropColumn(['workspace_id', 'section_id']);
        });
        Schema::dropIfExists('users');
    }
};
