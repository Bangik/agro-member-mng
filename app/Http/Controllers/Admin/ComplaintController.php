<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ChangeStatusToSolvedJob;
use App\Models\TComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ComplaintController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->input('search');
    $status = $request->input('status');

    $complaints = TComplaint::query()
      ->with('member', 'user')
      ->when($search, function ($query, $search) {
        return $query->where(function ($q) use ($search) {
          $q->where('title', 'like', "%{$search}%")
            ->orWhere('complaint', 'like', "%{$search}%")
            ->orWhere('status', 'like', "%{$search}%");
        });
      })
      ->when($status, function ($query, $status) {
        return $query->where('status', $status);
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('content.admin.complaint.index', compact('complaints', 'search', 'status'));
  }

  public function detail($id)
  {
    $complaint = TComplaint::with('member', 'user')->findOrFail($id);
    return view('content.admin.complaint.detail', compact('complaint'));
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'response' => 'required|string',
      'response_at' => 'required|date',
    ]);

    $complaint = TComplaint::findOrFail($id);
    $complaint->response = $request->response;
    $complaint->response_at = $request->response_at;
    $complaint->m_user_id = Auth::user()->id;
    $complaint->save();

    dispatch(new ChangeStatusToSolvedJob($complaint))->delay(now()->addDays(7));

    return redirect()->route('admin.complaints.index')->with('success', 'Aspirasi / Aduan berhasil diperbarui.');
  }

  public function generatePdf($id)
  {
    $complaint = TComplaint::with('member', 'user')->findOrFail($id);
    return Pdf::loadView('content.global.pdf-mail-complaint', [
      'complaint' => $complaint,
    ])->setPaper('A4')
      ->stream('mail-complaint-' . $complaint->id . '.pdf');
  }

  public function delete($id)
  {
    $complaint = TComplaint::findOrFail($id);
    $complaint->delete();

    return response()->json(['success' => true, 'message' => 'Bagian berhasil dihapus.']);
  }
}
