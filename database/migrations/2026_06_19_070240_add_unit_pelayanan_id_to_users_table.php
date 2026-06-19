<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_pelayanan_id')
                ->nullable()
                ->constrained('unit_pelayanan')
                ->onDelete('set null')
                ->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_pelayanan_id']);
            $table->dropColumn('unit_pelayanan_id');
        });
    }
};