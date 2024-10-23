<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::create([
      'first_name' => 'Swift',
      'last_name' => 'Admin',
      'name' => 'Swift Admin',
      'email' => 'admin@gmail.com',
      'password' => Hash::make('12345678'),
      'role' => 'admin',
    ]);
  }
}
