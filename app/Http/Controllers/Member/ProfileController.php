<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileHelper;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{
  public function index()
  {
    $member = Member::where('m_user_id', Auth::user()->id)->firstOrFail();

    return view('content.member.profile.index', compact('member'));
  }

  public function update(Request $request, $id)
  {
    $member = Member::findOrFail($id);

    $validated = $request->validate([
      // 'reg_number'         => ['required', 'string', 'max:255'],
      'national_id_number' => ['required', 'digits:16'], // NIK 16 digit (opsional: ubah sesuai kebutuhan)
      'name'               => ['required', 'string', 'max:255'],
      'birth_place'        => ['nullable', 'string', 'max:255'],
      'birth_date'         => ['nullable', 'date', 'before:today'],
      'gender'             => ['nullable', 'in:male,female'],

      'address'            => ['nullable', 'string'],
      'rt'                 => ['nullable', 'digits_between:1,3'],
      'rw'                 => ['nullable', 'digits_between:1,3'],
      'village'            => ['nullable', 'string', 'max:255'],
      'district'           => ['nullable', 'string', 'max:255'],
      'city'               => ['nullable', 'string', 'max:255'],
      'state'              => ['nullable', 'string', 'max:255'],
      'post_code'          => ['nullable', 'digits:5'], // Kode pos Indonesia

      'phone'              => ['nullable', 'string', 'max:30'],
      'email'              => ['required', 'email', 'max:255'],

      'religion'           => ['nullable', 'string', 'max:50'],
      'blood_type'         => ['nullable', 'in:A,B,AB,O'],
      'is_married'         => ['nullable', 'boolean'],
      'hobbies'            => ['nullable', 'string', 'max:255'],
      'photo'              => ['nullable', 'image', 'max:2048'],
    ], [
      'user_id.required'   => 'User wajib diisi.',
      'user_id.exists'     => 'User tidak ditemukan.',
      'birth_date.before'  => 'Tanggal lahir harus sebelum hari ini.',
      'off_date.after_or_equal' => 'Tanggal nonaktif harus setelah/serta dengan tanggal aktif.',
      'email.email'        => 'Format email tidak valid.',
      'national_id_number.digits' => 'NIK harus 16 digit.',
      'post_code.digits'   => 'Kode pos harus 5 digit.',
    ]);

    DB::beginTransaction();
    try {
      $user = User::findOrFail($member->m_user_id);
      $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
      ]);

      if ($request->hasFile('photo')) {

        if ($member->pp_file) {
          FileHelper::deleteFile('/member', $member->pp_file);
        }

        $validated['pp_file'] = FileHelper::storeFile($request->file('photo'), '/member');
        $validated['pp_path'] = '/member';
      }

      $member->update($validated);

      DB::commit();
      return redirect()->route('home')->with('success', 'Member updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      report($e);
      return redirect()->route('member.profile.index', $id)->with('error', 'Failed to update member.');
    }
  }

  public function generatePdf()
  {
    $member = Member::with([
      'contracts' => function ($query) {
        $query->with('part')->orderBy('created_at', 'desc');
      }
    ])->where('m_user_id', Auth::user()->id)->firstOrFail();

    return Pdf::loadView('content.global.pdf-profile', [
      'member' => $member,
      'employment' => $member->contracts->first(),
      'contracts' => $member->contracts,
      'chairman_name' => 'Sutrisno',
    ])->setPaper('A4')
      ->stream('profile.pdf');
  }
}
