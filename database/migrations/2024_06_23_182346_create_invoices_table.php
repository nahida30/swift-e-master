<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('invoices', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->text('request_ids');
      $table->decimal('amount', 10, 2);
      $table->date('invoice_date');
      $table->date('due_date');
      $table->text('description')->nullable();
      $table->timestamps();

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
    Schema::dropIfExists('invoices');
  }
}
