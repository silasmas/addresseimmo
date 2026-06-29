<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute la colonne last_connection utilisée par l'authentification API v1.
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    if (Schema::hasColumn('users', 'last_connection')) {
      return;
    }

    Schema::table('users', function (Blueprint $table) {
      $table->timestamp('last_connection')->nullable()->after('api_token');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    if (!Schema::hasColumn('users', 'last_connection')) {
      return;
    }

    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('last_connection');
    });
  }
};
