<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table products (annonces immobilières).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('product_name');
      $table->longText('product_description')->nullable();
      $table->integer('quantity')->nullable()->default(1);
      $table->decimal('price', 12, 2)->nullable();
      $table->string('currency', 45)->default('USD');
      $table->tinyInteger('is_service')->default(0);
      $table->enum('action', ['sell', 'rent', 'build', 'design', 'moving'])->default('sell');
      $table->string('country')->nullable();
      $table->string('city')->nullable();
      $table->text('address')->nullable();
      $table->string('municipality')->nullable();
      $table->string('neighborhood')->nullable();
      $table->string('street')->nullable();
      $table->tinyInteger('is_shared')->default(0);
      $table->enum('type', [
        'equipped_house',
        'empty_house',
        'unfinished_house',
        'equipped_apartment',
        'empty_apartment',
        'empty_plot',
        'house_plot',
      ])->nullable();
      $table->unsignedBigInteger('created_by')->nullable();
      $table->unsignedBigInteger('updated_by')->nullable();
      $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnUpdate()->nullOnDelete();
      $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
      $table->timestamps();

      $table->index('category_id', 'fk_products_categories_idx');
      $table->index('user_id', 'fk_products_users_idx');
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
