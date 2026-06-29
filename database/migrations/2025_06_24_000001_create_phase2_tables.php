<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée les tables des modules Phase 2 (enchères, loto, Ya Ofele, ventes vérifiées).
 */
return new class extends Migration
{
  /**
   * Exécute la migration.
   */
  public function up(): void
  {
    if (!Schema::hasTable('agencies')) {
      Schema::create('agencies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('city')->nullable();
        $table->string('country')->nullable();
        $table->boolean('is_verified')->default(false);
        $table->string('logo_url')->nullable();
        $table->timestamps();
      });
    }

    if (!Schema::hasTable('auctions')) {
      Schema::create('auctions', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('product_id')->nullable();
        $table->string('title');
        $table->string('location')->nullable();
        $table->decimal('start_price', 15, 2)->default(0);
        $table->decimal('current_bid', 15, 2)->nullable();
        $table->string('currency', 10)->default('USD');
        $table->string('status')->default('draft');
        $table->timestamp('starts_at')->nullable();
        $table->timestamp('ends_at')->nullable();
        $table->timestamps();
      });
    }

    if (!Schema::hasTable('auction_bids')) {
      Schema::create('auction_bids', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('auction_id');
        $table->bigInteger('user_id');
        $table->decimal('amount', 15, 2);
        $table->string('currency', 10)->default('USD');
        $table->timestamps();
      });
    }

    if (!Schema::hasTable('lottery_draws')) {
      Schema::create('lottery_draws', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('product_id')->nullable();
        $table->decimal('ticket_price', 15, 2)->default(5000);
        $table->string('currency', 10)->default('CDF');
        $table->unsignedInteger('tickets_sold')->default(0);
        $table->unsignedInteger('tickets_available')->default(5000);
        $table->string('status')->default('open');
        $table->timestamp('draw_at')->nullable();
        $table->timestamps();
      });
    }

    if (!Schema::hasTable('lottery_tickets')) {
      Schema::create('lottery_tickets', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('lottery_draw_id');
        $table->bigInteger('user_id')->nullable();
        $table->string('ticket_number')->unique();
        $table->bigInteger('payment_id')->nullable();
        $table->timestamps();
      });
    }

    if (!Schema::hasTable('ya_ofele_draws')) {
      Schema::create('ya_ofele_draws', function (Blueprint $table) {
        $table->id();
        $table->string('prize_description');
        $table->string('status')->default('open');
        $table->timestamp('draw_at')->nullable();
        $table->timestamps();
      });
    }

    if (!Schema::hasTable('ya_ofele_entries')) {
      Schema::create('ya_ofele_entries', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('ya_ofele_draw_id');
        $table->bigInteger('user_id')->nullable();
        $table->string('full_name');
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->string('city')->nullable();
        $table->timestamps();
      });
    }

    if (!Schema::hasTable('verified_sales')) {
      Schema::create('verified_sales', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('product_id');
        $table->bigInteger('user_id');
        $table->string('status')->default('pending');
        $table->timestamp('verified_at')->nullable();
        $table->timestamps();
      });
    }

    if (!Schema::hasTable('verified_documents')) {
      Schema::create('verified_documents', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('verified_sale_id');
        $table->string('document_type');
        $table->string('file_url');
        $table->string('status')->default('pending');
        $table->timestamps();
      });
    }
  }

  /**
   * Annule la migration.
   */
  public function down(): void
  {
    Schema::dropIfExists('verified_documents');
    Schema::dropIfExists('verified_sales');
    Schema::dropIfExists('ya_ofele_entries');
    Schema::dropIfExists('ya_ofele_draws');
    Schema::dropIfExists('lottery_tickets');
    Schema::dropIfExists('lottery_draws');
    Schema::dropIfExists('auction_bids');
    Schema::dropIfExists('auctions');
    Schema::dropIfExists('agencies');
  }
};
