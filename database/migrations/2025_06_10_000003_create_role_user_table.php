<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table pivot role_user.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('role_user', function (Blueprint $table) {
      $table->id();
      $table->foreignId('role_id')->constrained('roles')->cascadeOnUpdate()->cascadeOnDelete();
      $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
      $table->tinyInteger('is_selected')->default(0);
      $table->timestamps();

      $table->index('role_id', 'fk_roleuser_roles_idx');
      $table->index('user_id', 'fk_roleuser_users_idx');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('role_user');
  }
};
