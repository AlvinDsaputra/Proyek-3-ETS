<?php

namespace App\Services;

use App\Models\Report;
use InvalidArgumentException;

class ReportService
{
    // Definisi hirarki transisi status yang valid
    protected array $allowedTransitions = [
        'diajukan' => ['ditinjau'],
        'ditinjau' => ['diteruskan', 'selesai'],
        'diteruskan' => ['selesai'],
        'selesai' => [], // Status selesai tidak dapat diubah lagi
    ];

    // Validasi apakah perubahan status yang diminta diperbolehkan oleh sistem.

    public function validateTransition(string $currentStatus, string $newStatus): void
    {
        if ($currentStatus === $newStatus) {
            return;
        }

        $allowedNextStates = $this->allowedTransitions[$currentStatus] ?? [];

        if (!in_array($newStatus, $allowedNextStates)) {
            throw new InvalidArgumentException(
                "Perubahan status dari '{$currentStatus}' ke '{$newStatus}' tidak diperbolehkan berdasarkan aturan alur laporan."
            );
        }
    }
}