<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TContract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
  public function store(Request $request)
  {
    $validated = $request->validate([
      'm_member_id' => ['required', 'exists:m_member,id'],
      'part'     => ['required', 'string'],
      'contract_number' => ['required', 'string', 'max:255'],
      'start_date'   => ['required', 'date'],
      'end_date'   => ['required', 'date', 'after:start_date'],
    ]);

    TContract::create($validated);

    return redirect()->back()->with('success', 'Contract added successfully.');
  }
}
