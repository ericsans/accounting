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
        Schema::create('journal_entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->nullable();
            $table->foreignId('account_id')->nullable();
            $table->integer('rownumber')->nullable();
            $table->string('voucher')->nullable();
            $table->text('description')->nullable();
            $table->decimal('debit')->default(0)->nullable();
            $table->decimal('credit')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entry_details');
    }
};
