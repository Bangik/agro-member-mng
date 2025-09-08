<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Imports\MembersImport;
use App\Models\Member;
use App\Models\Part;
use App\Models\TContract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->input('search');
    $members = Member::query()
      ->with('contracts')
      ->when($search, function ($query) use ($search) {
        return $query->where(function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%")
            ->orWhere('reg_number', 'like', "%{$search}%")
            ->orWhere('national_id_number', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
        });
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10)
      ->withQueryString();
    return view('content.admin.member.index', compact('members'));
  }

  public function create()
  {
    $parts = Part::all();
    return view('content.admin.member.create', compact('parts'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'reg_number'         => ['required', 'string', 'max:255'],
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

      'm_part_id'          => ['nullable', 'exists:m_part,id'],
      'contract_number'    => ['required', 'string', 'max:255'],
      'active_date'        => ['required', 'date'],
      'off_date'           => ['required', 'date', 'after_or_equal:active_date'],
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
      $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['national_id_number']),
      ]);

      $validated['m_user_id'] = $user->id;

      if ($request->hasFile('photo')) {
        $validated['pp_file'] = FileHelper::storeFile($request->file('photo'), '/member');
        $validated['pp_path'] = '/member';
      }

      $member = Member::create($validated);
      TContract::create([
        'm_member_id'   => $member->id,
        'm_part_id'     => $validated['m_part_id'],
        'contract_number' => $validated['contract_number'],
        'start_date'   => $validated['active_date'],
        'end_date'      => $validated['off_date'],
      ]);
      DB::commit();

      return redirect()->route('admin.members.index')->with('success', 'Member created successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      report($e);
      return redirect()->route('admin.members.create')->with('error', 'Failed to create member.');
    }
  }

  public function edit($id)
  {
    $member = Member::findOrFail($id);
    return view('content.admin.member.edit', compact('member'));
  }

  public function update(Request $request, $id)
  {
    $member = Member::findOrFail($id);

    $validated = $request->validate([
      'reg_number'         => ['required', 'string', 'max:255'],
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
      return redirect()->route('admin.members.index')->with('success', 'Member updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      report($e);
      return redirect()->route('admin.members.edit', $id)->with('error', 'Failed to update member.');
    }
  }

  public function detail($id)
  {
    $member = Member::findOrFail($id);
    $contracts = TContract::query()
      ->with('part')
      ->where('m_member_id', $id)
      ->latest()
      ->paginate(10)
      ->withQueryString();
    $parts = Part::all();
    return view('content.admin.member.detail', compact('member', 'contracts', 'parts'));
  }

  public function import(Request $request)
  {
    $validated = $request->validate([
      'file' => ['required', 'file', 'mimes:xlsx', 'max:2048'],
    ]);

    Excel::import(new MembersImport, $validated['file']);

    return redirect()->route('admin.members.index')->with('success', 'Members imported successfully.');
  }

  public function destroy($id)
  {
    $member = Member::findOrFail($id);
    $member->delete();
    return response()->json(['message' => 'Member deleted successfully.']);
  }

  public function getDeletedMembers(Request $request)
  {
    $deletedMembers = Member::onlyTrashed()->paginate(10);
    return view('content.admin.member.deleted', compact('deletedMembers'));
  }

  public function restore($id)
  {
    $member = Member::onlyTrashed()->findOrFail($id);
    $member->restore();
    return redirect()->route('admin.members.deleted')->with('success', 'Member restored successfully.');
  }

  public function forceDelete($id)
  {
    $member = Member::onlyTrashed()->findOrFail($id);

    if ($member->pp_file) {
      FileHelper::deleteFile('/member', $member->pp_file);
    }

    $member->forceDelete();
    return redirect()->route('admin.members.deleted')->with('success', 'Member permanently deleted.');
  }
}
