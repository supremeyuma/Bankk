<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First drop the enum constraint by changing the column to string (varchar)
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('type')->change(); // Now it's just a string, no enum
        });
    }

    public function down()
    {
        // If you need to rollback, you can define the original enum values again
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('deposit', 'withdrawal', 'internal_transfer', 'domestic_wire', 'international_wire') NOT NULL");
    }
};
