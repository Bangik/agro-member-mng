<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ContractExport;
use App\Http\Controllers\Controller;
use App\Models\TContract;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ContractController extends Controller
{
  public function export()
  {
    $fileName = 'contracts_' . date('Ymd_His') . '.xlsx';
    return Excel::download(new ContractExport, $fileName);
  }

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

  public function update(Request $request, $id)
  {
    $contract = TContract::findOrFail($id);

    $validated = $request->validate([
      'm_member_id' => ['required', 'exists:m_member,id'],
      'part'     => ['required', 'string'],
      'contract_number' => ['required', 'string', 'max:255'],
      'start_date'   => ['required', 'date'],
      'end_date'   => ['required', 'date', 'after:start_date'],
    ]);

    $contract->update($validated);

    return redirect()->back()->with('success', 'Contract updated successfully.');
  }
}
