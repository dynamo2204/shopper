<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_city')->nullable();

            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_cost', 8, 2)->default(0);

            $table->text('customer_notes')->nullable();

            $table->boolean('invoice_requested')->default(false);
            $table->string('invoice_company')->nullable();
            $table->string('invoice_nip')->nullable();
            $table->string('invoice_address')->nullable();
            $table->string('invoice_postal_code')->nullable();
            $table->string('invoice_city')->nullable();

            $table->string('payment_method')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_first_name',
                'shipping_last_name',
                'shipping_phone',
                'shipping_email',
                'shipping_address',
                'shipping_postal_code',
                'shipping_city',
                'shipping_method',
                'shipping_cost',
                'customer_notes',
                'invoice_requested',
                'invoice_company',
                'invoice_nip',
                'invoice_address',
                'invoice_postal_code',
                'invoice_city',
                'payment_method',
            ]);
        });
    }
};