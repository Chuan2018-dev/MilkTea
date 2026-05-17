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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('order_number');
            $table->string('contact_number', 20)->nullable()->after('customer_name');
            $table->text('delivery_address')->nullable()->after('contact_number');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('sugar_level', 10)->default('50%')->after('size_id');
            $table->string('ice_level', 20)->default('normal_ice')->after('sugar_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['sugar_level', 'ice_level']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'contact_number', 'delivery_address']);
        });
    }
};
