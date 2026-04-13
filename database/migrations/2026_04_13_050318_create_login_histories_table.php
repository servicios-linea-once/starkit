<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('location')->nullable();   // país/ciudad vía IP
            $table->boolean('successful')->default(true);
            $table->timestamp('logged_in_at');
            $table->timestamps();

            $table->index('user_id');
            $table->index('logged_in_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
