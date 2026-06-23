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
        Schema::table('transactions', function (Blueprint $table) {
            // Tambahkan kolom 'currency' setelah 'amount'
            // Default 'USD' akan diterapkan pada baris baru.
            // Untuk baris yang sudah ada, nilai default mungkin perlu diisi secara manual atau dengan seeder.
            $table->string('currency', 3)->default('USD')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
