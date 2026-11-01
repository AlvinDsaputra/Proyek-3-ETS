# Mini Project Proyek 3 - Pelaporan dan Pemantauan Keamanan Makanan MBG (Kasus A)

Aplikasi web berbasis Laravel untuk pelaporan, peninjauan, penerusan informasi, dan pemantauan kejadian terkait keamanan makanan Program Makan Bergizi Gratis (MBG).

---

## 1. Identitas Mahasiswa
* **Nama:** Alvin Dwi Saputra
* **NIM:** 251511001
* **Kelas:** D3 2A
* **Judul Mini Project:** Pelaporan dan Pemantauan Keamanan Makanan MBG (Kasus A)
* **URL Repository:** https://github.com/AlvinDsaputra/Proyek-3-ETS

---

## 2. Kebutuhan Environment
* **PHP:** ^8.2
* **Composer:** ^2.x
* **Node.js & npm:** ^18.x / ^20.x
* **Database:** MySQL / MariaDB
* **Framework:** Laravel 11.x

---

## 3. Langkah Instalasi

1. **Clone Repository:**
   ```bash
   git clone [https://github.com/AlvinDsaputra/Proyek-3-ETS.git](https://github.com/AlvinDsaputra/Proyek-3-ETS.git)
   cd Proyek-3-ETS
   ```

2. **Install Dependensi PHP:**
   ```bash
   composer install
   ```

3. **Install Dependensi JavaScript:**
   ```bash
   npm install && npm run build
   ```

4. **Salin File Environment:**
   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Buat Symbolic Link Storage:**
   ```bash
   php artisan storage:link
   ```

---

## 4. Konfigurasi Database

Buka file `.env` dan sesuaikan pengaturan koneksi database MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_mbg
DB_USERNAME=root
DB_PASSWORD=
```

---

## 5. Migration & Seeding Data Demo

Jalankan perintah berikut untuk membuat skema database dan mengisi data pengujian awal:

```bash
php artisan migrate:fresh --seed
```

---

## 6. Akun Demo Setiap Role

| Peran (Role) | Email | Password | Hak Akses Utama |
| :---                      | :---                   | :---       |                                                    :---                                                                      |
| **Pelapor**               | `pelapor@mbg.test`     | `password` | Membuat laporan, unggah bukti, edit laporan status *diajukan*, memantau status laporan milik sendiri (AC-02, AC-04, AC-05).  |
| **Pemantau Komunitas**    | `pemantau@mbg.test`    | `password` | Meninjau kelengkapan laporan, memberi catatan, mengubah status ke *ditinjau*, melihat rekap (AC-06, AC-07, AC-13).           |
| **Koordinator Komunitas** | `koordinator@mbg.test` | `password` | Mencatat penerusan informasi ke pihak eksternal, mengubah status ke diteruskan/selesai, melihat rekap (AC-06, AC-07, AC-13). |

---

## 7. Cara Menjalankan Aplikasi

Jalankan server lokal Laravel:

```bash
php artisan serve
```

Akses aplikasi melalui browser di: `http://127.0.0.1:8000`

---

## 8. Ringkasan Fitur Teknis & Acceptance Criteria
* **AC-01 Authentication:** Login & Logout berbasis Laravel Breeze dengan pembatasan hak akses berbasis Role.
* **AC-02 Pelaporan Kejadian:** Pelapor dapat mendaftarkan laporan gangguan kesehatan MBG terkait sekolah & distribusi makanan.
* **AC-03 Validation:** Validasi server-side pada input formulir pelaporan.
* **AC-04 File Storage:** Upload bukti format JPG/PNG/PDF maks 2 MB dikelola via Laravel Storage.
* **AC-05 Ownership & Privacy:** Pelapor hanya dapat melihat dan mengedit laporan miliknya sendiri (Policy restriction).
* **AC-06 & AC-07 Authorization & Process:** Pemantau & Koordinator memproses status laporan sesuai hirarki alur.
* **AC-08 Business Rules:** Menerapkan BR-01 (Kesesuaian distribusi & sekolah), BR-02 (Locking edit laporan), BR-03 (Hirarki transisi status).
* **AC-09 Asynchronous Interaction:** Pilihan sekolah memuat dropdown distribusi makanan via JavaScript `fetch()`.
* **AC-10 Search & Filter:** Filter berdasarkan sekolah, status, dan kata kunci pencarian.
* **AC-11 Pagination:** Pagination 10 data dengan parameter filter aktif (`withQueryString()`).
* **AC-12 Eloquent Observer:** Pencatatan otomatis log perubahan status laporan ke tabel `report_logs` via `ReportObserver`.
* **AC-13 Rekap Sederhana:** Halaman `/recap` menyediakan 1 filter sekolah, 3 indikator ringkasan, dan 1 tabel rekapitulasi per sekolah.