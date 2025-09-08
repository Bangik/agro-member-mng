<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
  public function index()
  {
    $setting = Setting::first();
    return view('content.admin.settings.index', compact('setting'));
  }

  public function updateKta(Request $request, $id)
  {
    $request->validate([
      'kta_file_now' => 'required|file|mimes:jpg,jpeg,png|max:2048',
    ]);

    $setting = Setting::findOrFail($id);

    if ($request->hasFile('kta_file_now')) {
      // Move current to before
      $setting->kta_file_before = $setting->kta_file_now;
      $setting->kta_path_before = $setting->kta_path_now;

      // Update to new file
      $setting->kta_file_now = FileHelper::storeFile($request->file('kta_file_now'), '/kta');
      $setting->kta_path_now = '/kta';
    }

    $setting->save();

    return redirect()->route('admin.settings.index')->with('success', 'Kartu Anggota updated successfully.');
  }

  public function printKta()
  {
    $setting = Setting::first();
    return view('content.admin.settings.kta', compact('setting'));
  }
}
