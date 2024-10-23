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
      $table->string('file2')->nullable();
      $table->string('file_name2')->nullable();
      $table->string('original_name2')->nullable();
      $table->timestamp('uploaded_at2')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('requests', function (Blueprint $table) {
      $table->dropColumn(['file2', 'file_name2', 'original_name2', 'uploaded_at2']);
    });
  }
};
