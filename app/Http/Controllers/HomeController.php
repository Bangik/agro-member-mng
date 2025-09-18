<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Part;
use App\Models\TComplaint;
use App\Models\TContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware(['auth', 'verified']);
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index(Request $request)
  {

    if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
      $currentMonth = now()->month;
      $end  = $request->filled('end_date')
        ? Carbon::parse($request->input('end_date'))->endOfDay()
        : now()->endOfDay();

      $start = $request->filled('start_date')
        ? Carbon::parse($request->input('start_date'))->startOfDay()
        : $end->copy()->subYear()->addDay()->startOfDay();

      $viewBy = in_array($request->input('view_by'), ['month', 'week']) ? $request->input('view_by') : 'month';

      $complaints = TComplaint::with('member')->latest()->take(10)->get();
      $totalComplaintsThisMonth = TComplaint::whereMonth('created_at', $currentMonth)->count();
      $maleCount = Member::where('gender', 'male')->count();
      $femaleCount = Member::where('gender', 'female')->count();
      $complaintPending = TComplaint::where('status', 'pending')->count();
      $complaintInProgress = TComplaint::where('status', 'in_progress')->count();
      $complaintResolved = TComplaint::where('status', 'resolved')->count();

      $keys = [];
      $categories = [];

      if ($viewBy === 'month') {
        $period = CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth());
        foreach ($period as $dt) {
          $keys[]   = $dt->format('Y-m');               // e.g. 2025-09
          $categories[] = $dt->isoFormat('MMM YY');         // e.g. Sep 25
        }
        $bucketSql    = "DATE_FORMAT(created_at, '%Y-%m')";
      } else { // week (ISO week)
        $period = CarbonPeriod::create(
          $start->copy()->startOfWeek(Carbon::MONDAY),
          '1 week',
          $end->copy()->startOfWeek(Carbon::MONDAY)
        );
        foreach ($period as $dt) {
          $isoYear = $dt->isoWeekYear;
          $isoWeek = $dt->isoWeek;
          $keys[]   = sprintf('%d-%02d', $isoYear, $isoWeek); // e.g. 2025-37
          $categories[] = sprintf('W%02d %s', $isoWeek, substr((string)$isoYear, -2)); // e.g. W37 25
        }
        // %x = ISO week-year, %v = ISO week number (01..53)
        $bucketSql    = "DATE_FORMAT(created_at, '%x-%v')";
      }

      // --- Ambil status yang ada di range (biar legend rapi & relevan) ---
      $statuses = TComplaint::whereBetween('created_at', [$start, $end])
        ->distinct()->pluck('status')->filter()->values()->all();

      // --- Query agregasi: total per bucket & per status per bucket ---
      $totals = TComplaint::selectRaw("$bucketSql as bucket, COUNT(*) as total")
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('bucket')
        ->get()
        ->keyBy('bucket');

      $rows = TComplaint::selectRaw("status, $bucketSql as bucket, COUNT(*) as total")
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('status', 'bucket')
        ->get();

      // --- Susun series "Total" ---
      $totalData = [];
      foreach ($keys as $i => $k) {
        $totalData[$i] = isset($totals[$k]) ? (int)$totals[$k]->total : 0;
      }
      $series = [['name' => 'Total', 'data' => $totalData]];

      // --- Susun series per status ---
      foreach ($statuses as $status) {
        $data = array_fill(0, count($keys), 0);
        foreach ($rows as $r) {
          if ($r->status === $status) {
            $idx = array_search($r->bucket, $keys, true);
            if ($idx !== false) $data[$idx] = (int)$r->total;
          }
        }
        $series[] = [
          'name' => ucfirst($status),
          'data' => $data,
        ];
      }

      $rows = Part::query()
        ->leftJoin('t_contract as c', 'c.m_part_id', '=', 'm_part.id')
        ->select('m_part.name', DB::raw('COUNT(c.id) as total'))
        ->groupBy('m_part.id', 'm_part.name')
        ->orderBy('m_part.name')
        ->get()
        ->map(fn($r) => ['x' => $r->name, 'y' => (int)$r->total]);

      return view('content.dashboard.dashboards-analytics', compact(
        'complaints',
        'series',
        'categories',
        'totalComplaintsThisMonth',
        'maleCount',
        'femaleCount',
        'rows',
        'complaintPending',
        'complaintInProgress',
        'complaintResolved',
      ));
    } else {
      $member = Member::with('contracts')->where('m_user_id', Auth::user()->id)->first();

      if ($member->contracts->where('end_date', '>=', now()->format('Y-m-d'))->count() > 0) {
        abort(403, 'Anda bukan anggota aktif. Silakan hubungi admin untuk memperbarui status keanggotaan Anda.');
      }

      $contracts = TContract::query()
        ->with('part')
        ->where('m_member_id', $member->id)
        ->latest()
        ->paginate(10)
        ->withQueryString();
      $parts = Part::all();

      $complaintPending = TComplaint::where('status', 'pending')->where('m_member_id', $member->id)->count();
      $complaintInProgress = TComplaint::where('status', 'in_progress')->where('m_member_id', $member->id)->count();
      $complaintResolved = TComplaint::where('status', 'resolved')->where('m_member_id', $member->id)->count();

      return view('content.dashboard.dashboard-member', compact(
        'member',
        'contracts',
        'parts',
        'complaintPending',
        'complaintInProgress',
        'complaintResolved'
      ));
    }
  }
}
