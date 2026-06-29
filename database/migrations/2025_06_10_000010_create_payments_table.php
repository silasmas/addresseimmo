<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table payments (FlexPay).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->string('reference', 45)->nullable();
      $table->string('provider_reference', 45)->nullable();
      $table->text('order_number')->nullable();
      $table->decimal('amount', 12, 2)->nullable();
      $table->decimal('amount_customer', 12, 2)->nullable();
      $table->string('phone', 45)->nullable();
      $table->string('currency', 45)->nullable();
      $table->string('channel', 45)->nullable();
      $table->integer('type')->nullable();
      $table->integer('status')->nullable();
      $table->foreignId('cart_id')->constrained('carts')->cascadeOnUpdate()->cascadeOnDelete();
      $table->timestamps();

      $table->index('cart_id', 'fk_payments_carts_idx');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('payments');
  }
};
