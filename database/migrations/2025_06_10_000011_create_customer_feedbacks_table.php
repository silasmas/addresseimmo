<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table customer_feedbacks (avis clients).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('customer_feedbacks', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('for_user_id')->nullable();
      $table->unsignedBigInteger('for_product_id')->nullable();
      $table->tinyInteger('rating')->nullable();
      $table->longText('comment')->nullable();
      $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
      $table->timestamps();

      $table->index('user_id', 'fk_customerfeedbacks_users_idx');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('customer_feedbacks');
  }
};
