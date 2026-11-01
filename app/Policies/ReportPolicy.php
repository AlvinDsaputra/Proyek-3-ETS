<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    // Pelapor hanya bisa melihat laporan miliknya sendiri. Pemantau dan Koordinator dapat melihat seluruh laporan.
    public function view(User $user, Report $report): bool
    {
        if ($user->role === 'pelapor') {
            return $user->id === $report->user_id;
        }

        return in_array($user->role, ['pemantau', 'koordinator']);
    }

    // Pelapor hanya boleh mengedit laporan miliknya dan jika status masih 'diajukan'.Jika status sudah 'ditinjau', 'diteruskan', atau 'selesai', laporan terkunci.
  
    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id && $report->status === 'diajukan';
    }

    // Hak Akses Pemantau Komunitas untuk meninjau laporan.
    public function review(User $user, Report $report): bool
    {
        return $user->role === 'pemantau' && in_array($report->status, ['diajukan', 'ditinjau']);
    }

    // Hak Akses Koordinator Komunitas untuk mencatat penerusan informasi
    public function forward(User $user, Report $report): bool
    {
        return $user->role === 'koordinator' && in_array($report->status, ['ditinjau', 'diteruskan']);
    }
}