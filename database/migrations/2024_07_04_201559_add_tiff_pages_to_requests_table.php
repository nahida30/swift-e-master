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
      $table->integer('tiff_pages')->nullable();
      $table->string('completed_file_name')->nullable();
      $table->string('completed_original_name')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('requests', function (Blueprint $table) {
      $table->dropColumn('tiff_pages');
      $table->dropColumn('completed_file_name');
      $table->dropColumn('completed_original_name');
    });
  }
};
