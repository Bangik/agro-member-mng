<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\Part;
use App\Models\TContract;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MembersImport implements ToCollection
{
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function collection(Collection $rows)
  {
    $rows->shift();

    $users = [];
    $members = [];
    $contracts = [];

    $parts = Part::all();

    foreach ($rows as $row) {
      $userId = Str::uuid()->toString();
      $memberId = Str::uuid()->toString();
      $users[] = [
        'id' => $userId,
        'name' => $row[0],
        'email' => $row[15],
        'password' => bcrypt($row[1]),
        'created_at' => now(),
        'updated_at' => now()
      ];

      $members[] = [
        'id'                  => $memberId,
        'm_user_id'           => $userId,

        'name'                => $row[0],
        'reg_number'          => $row[1],
        'national_id_number'  => $row[2],
        'birth_place'         => $row[3],
        'birth_date'          => !empty($row[4]) ? \Carbon\Carbon::parse($row[4])->format('Y-m-d') : null,
        'gender'              => strtolower($row[5]), // male or female

        'address'             => $row[6],
        'rt'                  => $row[7],
        'rw'                  => $row[8],
        'village'             => $row[9],
        'district'            => $row[10],
        'city'                => $row[11], // kabupaten/kota
        'state'               => $row[12], // provinsi
        'post_code'           => $row[13],
        'phone'               => $row[14],
        'email'               => $row[15],
        'religion'            => $row[16],
        'blood_type'          => $row[17],
        'is_married'          => $row[18], // 1 or 0
        'hobbies'             => $row[19],
        'created_at'          => now(),
        'updated_at'          => now()
      ];

      $contracts[] = [
        'id'                  => Str::uuid()->toString(),
        'm_member_id'           => $memberId,
        'm_part_id'           => $parts->where('name', $row[23])->first()->id ?? null,

        'contract_number'     => $row[20],
        'start_date'         => !empty($row[21]) ? \Carbon\Carbon::parse($row[21])->format('Y-m-d') : null,
        'end_date'           => !empty($row[22]) ? \Carbon\Carbon::parse($row[22])->format('Y-m-d') : null,
        'created_at'          => now(),
        'updated_at'          => now()
      ];
    }

    DB::transaction(function () use ($users, $members, $contracts) {
      User::insert($users);
      Member::insert($members);
      TContract::insert($contracts);
    });
  }
}
