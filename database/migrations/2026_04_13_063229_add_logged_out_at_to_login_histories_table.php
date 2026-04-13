<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_logged_out_at_to_login_histories_table.php

    public function up(): void
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->timestamp('logged_out_at')->nullable()->after('user_id');
            $table->string('session_id')->nullable()->after('logged_out_at');
        });
    }

    public function down(): void
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->dropColumn(['logged_out_at', 'session_id']);
        });
    }
};
