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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('additional_player')->nullable()->after('discount');
            $table->string('additional_per_player')->nullable()->after('additional_player');
            $table->string('additional_sub_total')->nullable()->after('additional_per_player');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('additional_player');
            $table->dropColumn('additional_per_player');
            $table->dropColumn('additional_sub_total');
        });
    }
};
