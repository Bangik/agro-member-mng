<?php

namespace App\Http\Controllers\Global;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:m_user,email,' . $id,
      'gender' => 'nullable|string|in:male,female',
      'phone' => 'nullable|string|max:20',
      'address' => 'nullable|string|max:255',
      'city' => 'nullable|string|max:255',
      'state' => 'nullable|string|max:255',
      'country' => 'nullable|string|max:255',
      'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
      toastr()->error(implode(', ', $validator->errors()->all()));
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }

    $photo = FileHelper::storeFile($request->file('photo'), '/user');


    $user = User::findOrFail($id);
    if ($request->hasFile('photo')) {
      FileHelper::deleteFile('/user', $user->pp_file);
    }
    $user->update([
      'name' => $request->name,
      'email' => $request->email,
      'gender' => $request->gender,
      'phone' => $request->phone,
      'address' => $request->address,
      'city' => $request->city,
      'state' => $request->state,
      'country' => $request->country,
      'pp_path' => '/user',
      'pp_file' => $photo,
    ]);
    return redirect()->back()->with('success', 'Profile updated successfully');
  }
}
