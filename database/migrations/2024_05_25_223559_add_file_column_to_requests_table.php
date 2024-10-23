<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileColumnToRequestsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('requests', function (Blueprint $table) {
      $table->string('file')->nullable(); // Add a file column
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('requests', function (Blueprint $table) {
      $table->dropColumn('file'); // Drop the file column
    });
  }
}
