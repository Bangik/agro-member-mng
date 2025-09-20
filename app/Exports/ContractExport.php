<?php

namespace App\Exports;

use App\Models\TContract;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Carbon\Carbon;

class ContractExport implements FromQuery, WithHeadings, WithMapping, WithCustomValueBinder
{

  protected array $contractCols = [
    'id',
    'contract_number',
    'start_date',
    'end_date',
    'part',
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  protected array $memberCols = [
    'id',
    'reg_number',
    'national_id_number',
    'name',
    'birth_place',
    'birth_date',
    'gender',
    'address',
    'rt',
    'rw',
    'village',
    'district',
    'city',
    'state',
    'post_code',
    'phone',
    'email',
    'religion',
    'blood_type',
    'is_married',
    'hobbies',
    'photo_url',
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  public function __construct(
    // opsional: kamu bisa kirim filter via __construct (tanggal, status, dsb.)
    protected ?string $startDate = null,
    protected ?string $endDate   = null,
  ) {}

  public function query()
  {
    return TContract::query()
      ->with(['member' => function ($q) {
        // kalau pakai soft deletes dan ingin ikut mengekspor yg terhapus:
        $q->withTrashed();
      }])
      // contoh filter opsional:
      ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
      ->when($this->endDate,   fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
      ->orderBy('created_at', 'desc');
  }

  public function headings(): array
  {
    // Buat header: prefix kolom member agar jelas
    $contractHead = array_map(fn($c) => 'contract_' . $c, $this->contractCols);
    $memberHead   = array_map(fn($m) => 'member_'   . $m, $this->memberCols);

    return array_merge($contractHead, $memberHead);
  }

  /**
   * @param \App\Models\TContract $contract
   */
  public function map($contract): array
  {
    $contractRow = [];
    foreach ($this->contractCols as $col) {
      $val = data_get($contract, $col);
      $contractRow[] = $this->toString($val, isDate: in_array($col, ['start_date', 'end_date', 'created_at', 'updated_at'], true));
    }

    $m = $contract->member;
    $memberRow = [];
    foreach ($this->memberCols as $col) {
      $val = $m ? data_get($m, $col) : null;

      if (in_array($col, ['birth_date'], true)) {
        $memberRow[] = $this->toString($val, isDate: true);
        continue;
      }

      if (in_array($col, ['is_maried', 'is_married'], true)) {
        // handle 1/0, true/false, "1"/"0", null
        $memberRow[] = ($val === 1 || $val === true || $val === '1') ? 'Menikah' : 'Belum menikah';
        continue;
      }

      $memberRow[] = $this->toString($val);
    }

    return array_merge($contractRow, $memberRow);
  }

  /**
   * Paksa semua cell menjadi TEXT di Excel (anti scientific notation).
   */
  public function bindValue(Cell $cell, $value)
  {
    // Pastikan null jadi string kosong
    if ($value === null) {
      $value = '';
    }
    // Set eksplisit sebagai STRING
    $cell->setValueExplicit((string)$value, DataType::TYPE_STRING);
    return true;
  }

  private function toString($val, bool $isDate = false): string
  {
    if ($val === null) return '';

    // Format tanggal ke string supaya tidak jadi serial Excel
    if ($isDate) {
      if ($val instanceof Carbon) return $val->format('Y-m-d H:i:s');
      // kalau castable string tanggal
      try {
        $c = Carbon::parse($val);
        return $c->format('Y-m-d H:i:s');
      } catch (\Exception $e) {
        // biarkan apa adanya
      }
    }

    // Boolean umum (selain is_maried sudah ditangani) → jadikan "1"/"0"
    if (is_bool($val)) return $val ? '1' : '0';

    // Angka besar (NIK/HP) tetap return string apa adanya
    // Jika numeric, tetap cast ke string agar leading zero aman.
    return (string)$val;
  }
}
