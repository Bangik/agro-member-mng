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

    // $memberStatus = $request->input('member_status', 'all');

    if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
      $memberStatus = $request->input('member_status', 'all');

      $currentMonth = now()->month;
      $end  = $request->filled('end_date')
        ? Carbon::parse($request->input('end_date'))->endOfDay()
        : now()->endOfDay();

      $start = $request->filled('start_date')
        ? Carbon::parse($request->input('start_date'))->startOfDay()
        : $end->copy()->subYear()->addDay()->startOfDay();

      $viewBy = in_array($request->input('view_by'), ['month', 'week']) ? $request->input('view_by') : 'month';

      // === BASE FILTER (tanpa mengubah logic existing) ===
      $today = now()->toDateString();

      // Base query untuk keluhan, ter-filter status member
      $complaintQ = fn() => TComplaint::query()
        ->when(
          $memberStatus === 'active',
          fn($q) =>
          $q->whereHas('member.contracts', fn($c) => $c->where('end_date', '>=', $today))
        )
        ->when(
          $memberStatus === 'inactive',
          fn($q) =>
          $q->whereDoesntHave('member.contracts', fn($c) => $c->where('end_date', '>=', $today))
        );

      // Base query untuk member, ter-filter status member
      $memberQ = fn() => Member::query()
        ->when(
          $memberStatus === 'active',
          fn($q) =>
          $q->whereHas('contracts', fn($c) => $c->where('end_date', '>=', $today))
        )
        ->when(
          $memberStatus === 'inactive',
          fn($q) =>
          $q->whereDoesntHave('contracts', fn($c) => $c->where('end_date', '>=', $today))
        );

      // === Tetap pakai logic-mu, tapi ganti TComplaint::... jadi $complaintQ()->... ===
      $complaints = $complaintQ()->with('member')->latest()->take(10)->get();
      $totalComplaintsThisMonth = $complaintQ()->whereMonth('created_at', $currentMonth)->count();
      $complaintPending = $complaintQ()->where('status', 'pending')->count();
      $complaintInProgress = $complaintQ()->where('status', 'in_progress')->count();
      $complaintResolved = $complaintQ()->where('status', 'resolved')->count();

      // Gender count ikut ter-filter status member
      $maleCount = $memberQ()->where('gender', 'male')->count();
      $femaleCount = $memberQ()->where('gender', 'female')->count();

      $keys = [];
      $categories = [];

      if ($viewBy === 'month') {
        $period = CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth());
        foreach ($period as $dt) {
          $keys[]       = $dt->format('Y-m');          // e.g. 2025-09
          $categories[] = $dt->isoFormat('MMM YY');    // e.g. Sep 25
        }
        $bucketSql = "DATE_FORMAT(created_at, '%Y-%m')";
      } else { // week (ISO week)
        $period = CarbonPeriod::create(
          $start->copy()->startOfWeek(Carbon::MONDAY),
          '1 week',
          $end->copy()->startOfWeek(Carbon::MONDAY)
        );
        foreach ($period as $dt) {
          $isoYear = $dt->isoWeekYear;
          $isoWeek = $dt->isoWeek;
          $keys[]       = sprintf('%d-%02d', $isoYear, $isoWeek);         // e.g. 2025-37
          $categories[] = sprintf('W%02d %s', $isoWeek, substr((string)$isoYear, -2)); // e.g. W37 25
        }
        // %x = ISO week-year, %v = ISO week number (01..53)
        $bucketSql = "DATE_FORMAT(created_at, '%x-%v')";
      }

      // Status yang ada di range (ikut filter member)
      $statuses = $complaintQ()
        ->whereBetween('created_at', [$start, $end])
        ->distinct()->pluck('status')->filter()->values()->all();

      // Agregasi total per bucket (ikut filter member)
      $totals = $complaintQ()
        ->selectRaw("$bucketSql as bucket, COUNT(*) as total")
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('bucket')
        ->get()
        ->keyBy('bucket');

      // Agregasi per status per bucket (ikut filter member)
      $rowsAgg = $complaintQ()
        ->selectRaw("status, $bucketSql as bucket, COUNT(*) as total")
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('status', 'bucket')
        ->get();

      // Susun series "Total"
      $totalData = [];
      foreach ($keys as $i => $k) {
        $totalData[$i] = isset($totals[$k]) ? (int)$totals[$k]->total : 0;
      }
      $series = [['name' => 'Total', 'data' => $totalData]];

      // Susun series per status
      foreach ($statuses as $status) {
        $data = array_fill(0, count($keys), 0);
        foreach ($rowsAgg as $r) {
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

      // Contoh chart lain yang tidak terkait member (biarkan seperti semula)
      $rows = DB::table('t_contract')
        ->selectRaw('TRIM(part) AS name, COUNT(*) AS total')
        ->whereNotNull('part')
        ->whereRaw("TRIM(part) <> ''")
        ->groupBy('name')
        ->orderBy('name')
        ->get()
        ->map(fn($r) => ['x' => $r->name, 'y' => (int) $r->total]);


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

      if (!$member->contracts->where('end_date', '>=', now()->format('Y-m-d'))->count() > 0) {
        abort(404, 'Anda bukan anggota aktif. Silakan hubungi admin untuk memperbarui status keanggotaan Anda.');
      }

      $contracts = TContract::query()
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
