<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->input('search');
    $admins = User::whereNot('role', 'member')
      ->when($search, function ($query) use ($search) {
        $query->where('name', 'like', "%{$search}%");
      })
      ->latest()
      ->paginate(10)
      ->withQueryString();

    return view('content.admin.admin.index', compact('admins'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:m_user',
      'role' => 'required|string|in:admin,superadmin'
    ]);

    if ($validator->fails()) {
      toastr()->error(implode(', ', $validator->errors()->all()));
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }

    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->email),
      'role' => $request->role,
    ]);

    return redirect()->route('admin.admin.index')->with('success', 'Admin created successfully');
  }

  public function update(Request $request, $id)
  {
    $admin = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:m_user,email,' . $admin->id . ',id',
      'role' => 'required|string|in:admin,superadmin'
    ]);

    if ($validator->fails()) {
      toastr()->error(implode(', ', $validator->errors()->all()));
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }

    $admin->update([
      'name' => $request->name,
      'email' => $request->email,
      'role' => $request->role,
    ]);

    return redirect()->route('admin.admin.index')->with('success', 'Admin updated successfully');
  }

  public function delete($id)
  {
    $admin = User::findOrFail($id);

    $admin->delete();
    return response()->json([
      'status' => 'success',
      'message' => 'Admin deleted successfully'
    ]);
  }
}
