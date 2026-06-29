<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table users (schéma production AddressImmo).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('firstname')->nullable();
      $table->string('lastname')->nullable();
      $table->string('surname')->nullable();
      $table->longText('about_me')->nullable();
      $table->string('gender', 45)->nullable();
      $table->date('birthdate')->nullable();
      $table->string('country')->nullable();
      $table->string('city')->nullable();
      $table->text('address_1')->nullable();
      $table->text('address_2')->nullable();
      $table->string('p_o_box', 45)->nullable();
      $table->string('currency', 45)->nullable();
      $table->string('email')->nullable();
      $table->string('phone', 45)->nullable();
      $table->dateTime('email_verified_at')->nullable();
      $table->dateTime('phone_verfied_at')->nullable();
      $table->string('username')->nullable();
      $table->text('password')->nullable();
      $table->string('remember_token', 100)->nullable();
      $table->text('api_token')->nullable();
      $table->text('avatar_url')->nullable();
      $table->enum('status', ['created', 'activated', 'disabled'])->default('created');
      $table->unsignedBigInteger('created_by')->nullable();
      $table->unsignedBigInteger('updated_by')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
