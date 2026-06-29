<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table pivot product_user (co-propriété / partage).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('product_user', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
      $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
      $table->timestamps();

      $table->index('product_id', 'fk_productuser_products_idx');
      $table->index('user_id', 'fk_productuser_users_idx');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_user');
  }
};
