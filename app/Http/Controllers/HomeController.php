<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Part;
use App\Models\TComplaint;
use App\Models\TContract;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
  public function index()
  {

    if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
      $currentMonth = now()->month;

      $complaints = TComplaint::with('member')->latest()->take(10)->get();
      $totalComplaintsThisMonth = TComplaint::whereMonth('created_at', $currentMonth)->count();
      $maleCount = Member::where('gender', 'male')->count();
      $femaleCount = Member::where('gender', 'female')->count();

      $rows = Part::query()
        ->leftJoin('t_contract as c', 'c.m_part_id', '=', 'm_part.id')
        ->select('m_part.name', DB::raw('COUNT(c.id) as total'))
        ->groupBy('m_part.id', 'm_part.name')
        ->orderBy('m_part.name')
        ->get()
        ->map(fn($r) => ['x' => $r->name, 'y' => (int)$r->total]);

      return view('content.dashboard.dashboards-analytics', compact(
        'complaints',
        'totalComplaintsThisMonth',
        'maleCount',
        'femaleCount',
        'rows',
      ));
    } else {
      $member = Member::where('m_user_id', Auth::user()->id)->first();
      $contracts = TContract::query()
        ->with('part')
        ->where('m_member_id', $member->id)
        ->latest()
        ->paginate(10)
        ->withQueryString();
      $parts = Part::all();

      return view('content.dashboard.dashboard-member', compact(
        'member',
        'contracts',
        'parts'
      ));
    }
  }
}
