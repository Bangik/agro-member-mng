<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use App\Models\Part;
use App\Models\Setting;
use App\Models\TComplaint;
use App\Models\TContract;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    Part::factory(10)->create();

    User::factory(100)->create()->each(function ($user) {
      Member::factory()->create(['m_user_id' => $user->id]);
    });

    User::factory()->create([
      'name' => 'admin',
      'email' => 'admin@example.com',
      'role' => 'admin',
    ]);

    User::factory()->create([
      'name' => 'superadmin',
      'email' => 'superadmin@example.com',
      'role' => 'superadmin',
    ]);

    $member = User::factory()->create([
      'name' => 'member',
      'email' => 'member@example.com',
      'role' => 'member',
    ]);
    Member::factory()->create(['m_user_id' => $member->id]);

    TComplaint::factory(100)->create();

    TContract::factory(100)->create();

    Setting::factory()->create([
      'kta_path_now' => '/kta',
      'kta_file_now' => 'default.png',
    ]);
  }
}
