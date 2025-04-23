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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('account_id')->constrained()->onDelete('cascade');
        
        $table->enum('type', ['deposit', 'withdrawal', 'internal_transfer', 'international_transfer']);
        $table->decimal('amount', 15, 2);
        $table->string('currency', 3)->default('USD');
        $table->string('description')->nullable();

        // Transfer-specific
        $table->foreignId('recipient_account_id')->nullable()->constrained('accounts')->onDelete('set null'); // for internal
        $table->json('recipient_details')->nullable(); // for international

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
