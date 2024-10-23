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
    Schema::table('requests', function (Blueprint $table) {
      $table->string('file_name')->nullable();
      $table->string('original_name')->nullable();
      $table->dateTime('uploaded_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('requests', function (Blueprint $table) {
      $table->dropColumn('file_name');
      $table->dropColumn('original_name');
      $table->dropColumn('uploaded_at');
    });
  }
};
