<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUserIdFromRequestsNotesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('requests_notes', function (Blueprint $table) {
      // Dropping foreign key constraint if it exists
      // Uncomment the line below if there is a foreign key constraint on user_id
      $table->dropForeign(['user_id']);

      // Dropping the user_id column
      $table->dropColumn('user_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('requests_notes', function (Blueprint $table) {
      // Adding the user_id column back
      $table->unsignedBigInteger('user_id')->nullable();

      // Uncomment the lines below if you want to add the foreign key constraint back
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }
}
