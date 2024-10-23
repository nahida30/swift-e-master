<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiverIdAndSenderIdToRequestsNotesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('requests_notes', function (Blueprint $table) {
      $table->unsignedBigInteger('receiver_id')->nullable()->after('id');
      $table->unsignedBigInteger('sender_id')->nullable()->after('receiver_id');

      // Optional: Adding foreign key constraints
      $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
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
      $table->dropColumn('receiver_id');
      $table->dropColumn('sender_id');

      // Optional: Dropping foreign key constraints
      $table->dropForeign(['receiver_id']);
      $table->dropForeign(['sender_id']);
    });
  }
}
