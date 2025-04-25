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
            $table->enum('type', ['internal_transfer', 'deposit', 'withdrawal, international wire transfer','domestic wire transfer' ]);
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('USD');
            $table->string('sender_account_id')->nullable(); // for transfers
            $table->string('description')->nullable();
            
            // Transfer-specific
        $table->unsignedBigInteger('recipient_account_id')->nullable()->constrained('accounts')->onDelete('set null'); // for internal
        $table->json('recipient_details')->nullable(); // for wire
            
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
