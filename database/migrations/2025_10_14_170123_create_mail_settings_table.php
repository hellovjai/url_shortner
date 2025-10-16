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
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->id();
            $table->string('driver', 191);
            $table->string('host', 191);
            $table->string('port', 191);
            $table->string('from_address', 191);
            $table->string('from_name', 191);
            $table->string('encryption', 191);
            $table->string('username', 191);
            $table->string('password', 191);
            $table->timestamps();

            // Optional: if you want to specify engine & collation like in your SQL
            $table->engine = 'MyISAM';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_settings');
    }
};
