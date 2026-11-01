<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;
use App\Models\MealDistribution;
use App\Models\Report;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Demo untuk Setiap Peran Pengguna 
        $pelapor = User::create([
            'name' => 'Bu Rina (Pelapor)',
            'email' => 'pelapor@mbg.test',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
        ]);

        $pemantau = User::create([
            'name' => 'Pak Dedi (Pemantau)',
            'email' => 'pemantau@mbg.test',
            'password' => Hash::make('password'),
            'role' => 'pemantau',
        ]);

        $koordinator = User::create([
            'name' => 'Bu Sari (Koordinator)',
            'email' => 'koordinator@mbg.test',
            'password' => Hash::make('password'),
            'role' => 'koordinator',
        ]);

        // 2. Data Demo Sekolah
        $smp1 = School::create([
            'nama_sekolah' => 'SMP Paslon 02',
            'alamat' => 'Jl. Pendidikan No. 12, Jakarta',
        ]);

        $sd2 = School::create([
            'nama_sekolah' => 'SD Antek-Antek Asing 2',
            'alamat' => 'Jl. Tidak Merdeka No. 45, Jakarta',
        ]);

        // 3. Data Demo Distribusi Makanan MBG
        $dist1 = MealDistribution::create([
            'school_id' => $smp1->id,
            'tanggal_distribusi' => now()->subDays(2)->format('Y-m-d'),
            'menu_makanan' => 'Nasi, Buaya Goreng, Sayur Kingkong, Susu',
            'sppg_penyedia' => 'SPPG Sehat Sendiri',
        ]);

        $dist2 = MealDistribution::create([
            'school_id' => $sd2->id,
            'tanggal_distribusi' => now()->subDays(1)->format('Y-m-d'),
            'menu_makanan' => 'Nasi, Telur Dinosaurus, Tumis Buncis, Pisang Prasejarah',
            'sppg_penyedia' => 'SPPG Tidak Berkah',
        ]);

        // 4. Data Demo Laporan Kejadian 
        Report::create([
            'user_id' => $pelapor->id,
            'school_id' => $smp1->id,
            'meal_distribution_id' => $dist1->id,
            'tanggal_kejadian' => now()->subDays(2)->format('Y-m-d'),
            'deskripsi_kejadian' => 'Beberapa siswa mengalami mual dan sakit perut 3 jam setelah mengonsumsi makanan program MBG.',
            'jumlah_terdampak' => 5,
            'status' => 'diajukan',
        ]);

        Report::create([
            'user_id' => $pelapor->id,
            'school_id' => $sd2->id,
            'meal_distribution_id' => $dist2->id,
            'tanggal_kejadian' => now()->subDays(1)->format('Y-m-d'),
            'deskripsi_kejadian' => 'Keluhan bau tidak sedap pada kemasan lauk pauk distribusi makanan.',
            'jumlah_terdampak' => 2,
            'status' => 'ditinjau',
            'catatan_pemantau' => 'Laporan sedang diperiksa, menunggu konfirmasi pihak sekolah.',
        ]);
    }
}