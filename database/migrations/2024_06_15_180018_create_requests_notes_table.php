<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsNotesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('requests_notes', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('request_id');
      $table->text('note');
      $table->unsignedBigInteger('user_id')->nullable(); // If you want to track who made the note
      $table->timestamps();

      // Adding foreign keys and indexing for relationships
      $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

      // Optional: Indexing for performance optimization
      $table->index(['request_id', 'user_id']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('requests_notes');
  }
}
