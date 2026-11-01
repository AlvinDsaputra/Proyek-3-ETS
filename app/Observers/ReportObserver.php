<?php

namespace App\Observers;

use App\Models\Report;

class ReportObserver
{
    /**
     * Handle the Report "created" event.
     */
    public function created(Report $report): void
    {
        // Catat log hanya jika kolom 'status' mengalami perubahan
        if ($report->isDirty('status')) {
            ReportLog::create([
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'status_lama' => $report->getOriginal('status'),
                'status_baru' => $report->status,
            ]);
        }
    }

    /**
     * Handle the Report "updated" event.
     */
    public function updated(Report $report): void
    {
        //
    }

    /**
     * Handle the Report "deleted" event.
     */
    public function deleted(Report $report): void
    {
        //
    }

    /**
     * Handle the Report "restored" event.
     */
    public function restored(Report $report): void
    {
        //
    }

    /**
     * Handle the Report "force deleted" event.
     */
    public function forceDeleted(Report $report): void
    {
        //
    }
}
