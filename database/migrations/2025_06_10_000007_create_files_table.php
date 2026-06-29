<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table files (médias des annonces).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('files', function (Blueprint $table) {
      $table->id();
      $table->string('file_name')->nullable();
      $table->text('file_url');
      $table->enum('file_type', ['video', 'photo', 'audio', 'document'])->default('photo');
      $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
      $table->timestamps();

      $table->index('product_id', 'fk_files_products_idx');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('files');
  }
};
