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
        Schema::create('account_sub_classifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_classification_id')->nullable();
            $table->foreignId('cash_flow_classification_id')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_sub_classifications');
    }
};
