<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table pivot category_product (produits multi-catégories).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    if (Schema::hasTable('category_product')) {
      return;
    }

    Schema::create('category_product', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('category_id');
      $table->bigInteger('product_id');
      $table->timestamps();

      $table->unique(['category_id', 'product_id']);
      $table->foreign('category_id')->references('id')->on('categories')->cascadeOnUpdate()->cascadeOnDelete();
      $table->foreign('product_id')->references('id')->on('products')->cascadeOnUpdate()->cascadeOnDelete();
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('category_product');
  }
};
