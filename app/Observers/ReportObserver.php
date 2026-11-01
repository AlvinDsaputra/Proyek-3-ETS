<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\ReportLog;

class ReportObserver
{
    /**
     * Handle the Report "created" event.
     */
    public function created(Report $report): void
    {
        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id(),
            'status_lama' => '-', 
            'status_baru' => $report->status ?? 'diajukan',
        ]);
    }

    /**
     * Handle the Report "updated" event.
     */
    public function updated(Report $report): void
    {
        if ($report->isDirty('status')) {
            ReportLog::create([
                'report_id'   => $report->id,
                'user_id'     => auth()->id(),
                'status_lama' => $report->getOriginal('status') ?? '-',
                'status_baru' => $report->status,
            ]);
        }
    }
}