<?php

namespace App\Jobs;

use App\Models\TComplaint;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ChangeStatusToSolvedJob implements ShouldQueue
{
  use Queueable;

  /**
   * Create a new job instance.
   */
  public function __construct(private TComplaint $tComplaint) {}

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    if ($this->tComplaint->status === 'in_progress') {
      $this->tComplaint->update(['status' => 'resolved', 'resolved_at' => now()]);
    }
  }
}
