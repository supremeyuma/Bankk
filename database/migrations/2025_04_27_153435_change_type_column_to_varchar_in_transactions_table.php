<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Use raw SQL to ALTER column manually
        DB::statement('ALTER TABLE transactions MODIFY COLUMN type VARCHAR(255) NOT NULL');
    }

    public function down()
    {
        // (Optional) Restore it back to enum if you ever rollback
        //DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('deposit', 'withdrawal', 'internal_transfer', 'domestic_wire', 'international_wire') NOT NULL");
    }
};
