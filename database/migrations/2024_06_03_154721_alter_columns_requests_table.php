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
      $table->string('doc_type')->nullable()->change();
      $table->string('tag_it')->nullable()->change();
      $table->string('state')->nullable()->change();
      $table->string('county')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('requests', function (Blueprint $table) {
      $table->string('doc_type');
      $table->string('tag_it');
      $table->string('state');
      $table->string('county');
    });
  }
};
