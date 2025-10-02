<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use a raw statement to ensure compatibility without requiring doctrine/dbal
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if ($driver === 'mysql' || $driver === 'mysqli') {
            DB::statement("ALTER TABLE `orders` MODIFY `order_number` VARCHAR(255) NOT NULL;");
        } else {
            // Fallback: try schema change (may require doctrine/dbal)
            Schema::table('orders', function ($table) {
                $table->string('order_number', 255)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if ($driver === 'mysql' || $driver === 'mysqli') {
            DB::statement("ALTER TABLE `orders` MODIFY `order_number` CHAR(36) NOT NULL;");
        } else {
            Schema::table('orders', function ($table) {
                $table->uuid('order_number')->change();
            });
        }
    }
};
