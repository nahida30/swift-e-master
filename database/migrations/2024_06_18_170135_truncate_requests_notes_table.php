<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateRequestsNotesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::disableForeignKeyConstraints(); // Disable foreign key checks
    DB::table('requests_notes')->truncate(); // Truncate the table
    Schema::enableForeignKeyConstraints(); // Enable foreign key checks
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    // If you want to reverse this operation, you can leave this empty or handle rollback as needed.
  }
}
