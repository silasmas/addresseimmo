<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table customer_orders (lignes de panier).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('customer_orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained('products');
      $table->decimal('price_at_that_time', 12, 2)->nullable();
      $table->string('currency', 45)->nullable();
      $table->integer('quantity')->nullable();
      $table->foreignId('cart_id')->constrained('carts');
      $table->timestamps();

      $table->index('product_id', 'fk_customer_orders_products1_idx');
      $table->index('cart_id', 'fk_customer_orders_carts1_idx');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('customer_orders');
  }
};
