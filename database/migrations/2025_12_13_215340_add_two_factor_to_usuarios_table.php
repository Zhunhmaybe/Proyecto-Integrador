<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->boolean('two_factor_enabled')->default(false)->after('estado');
                $table->string('two_factor_code', 6)->nullable()->after('two_factor_enabled');
                $table->timestamp('two_factor_expires_at')->nullable()->after('two_factor_code');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['two_factor_enabled', 'two_factor_code', 'two_factor_expires_at']);
        });
    }
};
