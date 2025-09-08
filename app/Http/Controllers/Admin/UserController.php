<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\TEvent;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->input('search');
    $users = User::whereNot('role', 'admin')
      ->when($search, function ($query) use ($search) {
        return $query->where('name', 'like', "%{$search}%");
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10);
    return view('content.admin.user.index', compact('users'));
  }

  public function detail($id)
  {
    $user = User::findOrFail($id);
    $historyTransactions = TEvent::query()
      ->where('m_user_id', $user->id)
      ->with(['eventItem.event'])
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('content.admin.user.detail', compact('user', 'historyTransactions'));
  }

  public function edit($id)
  {
    $user = User::findOrFail($id);
    return view('content.global.profile', compact('user'));
  }

  public function delete($id)
  {
    $user = User::findOrFail($id);

    FileHelper::deleteFile('/user', $user->pp_file);

    $user->delete();
    return response()->json([
      'status' => 'success',
      'message' => 'User deleted successfully'
    ]);
  }
}
