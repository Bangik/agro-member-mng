<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
  public function index(Request $request)
  {
    $onlyTrashed = $request->input('only_trashed', 'all');

    $parts = Part::query()
      ->when($onlyTrashed === 'yes', function ($query) {
        $query->onlyTrashed();
      })
      ->when($onlyTrashed === 'no', function ($query) {
        $query->whereNull('deleted_at');
      })
      ->when($onlyTrashed === 'all', function ($query) {
        $query->withTrashed();
      })
      ->when($request->search, function ($query, $search) {
        $query->where('name', 'like', "%{$search}%");
      })
      ->latest()
      ->paginate(10)
      ->withQueryString();
    return view('content.admin.part.index', compact('parts'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
    ]);

    Part::create([
      'name' => $request->name,
    ]);

    return redirect()->route('admin.parts.index')->with('success', 'Bagian berhasil ditambahkan.');
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|string|max:255',
    ]);

    $part = Part::withTrashed()->findOrFail($id);
    $part->update([
      'name' => $request->name,
    ]);

    return redirect()->route('admin.parts.index')->with('success', 'Bagian berhasil diperbarui.');
  }

  public function destroy($id)
  {
    $part = Part::findOrFail($id);
    $part->delete();

    return response()->json(['success' => true, 'message' => 'Bagian berhasil dihapus.']);
  }

  public function restore($id)
  {
    $part = Part::withTrashed()->findOrFail($id);
    if ($part->trashed()) {
      $part->restore();
      return redirect()->route('admin.parts.index')->with('success', 'Bagian berhasil dipulihkan.');
    }
    return redirect()->route('admin.parts.index')->with('info', 'Bagian tidak dalam keadaan terhapus.');
  }
}
