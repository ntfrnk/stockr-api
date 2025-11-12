<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained()->onDelete('restrict');
            $table->foreignId('payment_entity_id')->constrained()->onDelete('restrict');

            $table->decimal('amount', 12, 2)->default(0);
            $table->string('reference')->nullable();
            $table->date('payment_date')->default(now());
            $table->text('notes')->nullable();
            $table->boolean('completed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_payments');
    }
};
