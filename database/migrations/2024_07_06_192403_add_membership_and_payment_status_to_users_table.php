<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->string('payment_status')->default('pending');
      $table->string('membership_status')->default('inactive');
      $table->date('membership_start')->nullable();
      $table->date('membership_end')->nullable();
      $table->text('details')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('payment_status');
      $table->dropColumn('membership_status');
      $table->dropColumn('membership_start');
      $table->dropColumn('membership_end');
      $table->dropColumn('details');
    });
  }
};
