<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\TComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
  public function index(Request $request)
  {
    $member = Member::where('m_user_id', Auth::id())->firstOrFail();
    $complaints = TComplaint::where('m_member_id', $member->id)
      ->when($request->search, function ($query) use ($request) {
        $query->where('title', 'like', '%' . $request->search . '%');
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10)
      ->withQueryString();

    return view('content.member.complaint.index', compact('complaints'));
  }
  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'complaint' => 'required|string',
    ]);

    $member = Member::where('m_user_id', Auth::id())->firstOrFail();

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

    TComplaint::create([
      'm_member_id' => $member->id,
      'code' => $code,
      'title' => $validated['title'],
      'complaint' => $validated['complaint'],
    ]);

    return redirect()->back()->with('success', 'Aspirasi / Aduan berhasil dikirim.');
  }

  public function detail($id)
  {
    $complaint = TComplaint::with('member', 'user')->findOrFail($id);
    return view('content.member.complaint.detail', compact('complaint'));
  }

  public function generatePdf($id)
  {
    $complaint = TComplaint::with('member', 'user')->findOrFail($id);
    return Pdf::loadView('content.global.pdf-mail-complaint', [
      'complaint' => $complaint,
    ])->setPaper('A4')
      ->stream('mail-complaint-' . $complaint->id . '.pdf');
  }

  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'complaint' => 'required|string',
    ]);

    $member = Member::where('m_user_id', Auth::id())->firstOrFail();
    $complaint = TComplaint::where('id', $id)->where('m_member_id', $member->id)->firstOrFail();
    $complaint->update([
      'title' => $validated['title'],
      'complaint' => $validated['complaint'],
    ]);

    return redirect()->back()->with('success', 'Aspirasi / Aduan berhasil diperbarui.');
  }

  public function updateStatus(Request $request, $id)
  {
    $validated = $request->validate([
      'status' => 'required|in:resolved',
    ]);

    $member = Member::where('m_user_id', Auth::id())->firstOrFail();
    $complaint = TComplaint::where('id', $id)->where('m_member_id', $member->id)->firstOrFail();
    $complaint->update([
      'status' => $validated['status'],
    ]);

    return redirect()->back()->with('success', 'Status aspirasi / aduan berhasil diperbarui.');
  }

  public function destroy($id)
  {
    $member = Member::where('m_user_id', Auth::id())->firstOrFail();
    $complaint = TComplaint::where('id', $id)->where('m_member_id', $member->id)->firstOrFail();
    $complaint->delete();

    return response()->json(['success' => 'Aspirasi / Aduan berhasil dihapus.']);
  }
}
