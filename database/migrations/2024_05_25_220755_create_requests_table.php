<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('requests', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->string('doc_type');
      $table->string('title');
      $table->text('description');
      $table->string('county');
      $table->string('state');
      $table->string('payment_status')->default('unpaid');
      $table->timestamp('payment_at')->nullable();
      $table->string('status')->default('Awaiting Completion');
      $table->timestamp('completed_at')->nullable();
      $table->timestamps(); // created_at and updated_at columns

      // Foreign key constraint
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('requests');
  }
}
