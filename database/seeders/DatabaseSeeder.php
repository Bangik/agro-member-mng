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
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    Part::factory(10)->create();

    User::factory(100)->create()->each(function ($user) {
      Member::factory()->create([
        'email' => $user->email,
        'reg_number' => $user->reg_number,
        'm_user_id' => $user->id
      ]);
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

    // TComplaint::factory(100)->create();

    for ($i = 0; $i < 100; $i++) {
      $year  = (int) now()->format('Y');   // reset per tahun
      $month = now()->format('m');

      // Ambil nomor urut secara ATOMIK & aman dari race condition
      // Pola: INSERT ... ON DUPLICATE KEY UPDATE last_number = LAST_INSERT_ID(last_number + 1)
      // lalu ambil DB::getPdo()->lastInsertId()
      $nextNumber = DB::transaction(function () use ($year) {
        DB::statement("
        INSERT INTO complaint_counter (`year`, `last_number`, `created_at`, `updated_at`)
        VALUES (?, LAST_INSERT_ID(1), NOW(), NOW())
        ON DUPLICATE KEY UPDATE
            `last_number` = LAST_INSERT_ID(`last_number` + 1),
            `updated_at` = NOW()
    ", [$year]);

        return (int) DB::getPdo()->lastInsertId(); // 1 saat baris tahun baru diinsert, atau n+1 saat update
      });


      $seq  = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
      $code = "{$seq}/AD-SPA/{$month}/{$year}";

      TComplaint::factory()->create([
        'code' => $code,
      ]);
    }

    TContract::factory(100)->create();

    Setting::factory()->create([
      'kta_path_now' => '/kta',
      'kta_file_now' => 'default.png',
      'kta_back_path_now' => '/kta',
      'kta_back_file_now' => 'default.png',
      'union_chairman' => 'John Doe',
      'union_reg_number' => '1234567890',
    ]);
  }
}
