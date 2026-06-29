<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table carts (paniers utilisateurs).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('carts', function (Blueprint $table) {
      $table->id();
      $table->string('payment_code', 45)->nullable();
      $table->tinyInteger('is_paid')->default(0);
      $table->tinyInteger('is_delivered')->nullable();
      $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
      $table->timestamps();

      $table->index('user_id', 'fk_carts_users_idx');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('carts');
  }
};
