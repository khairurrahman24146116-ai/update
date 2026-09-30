# Rencana Implementasi & Pengujian - SMA Madani Akademik v2

## 1. IDOR Hardening (High Priority)
Penerapan *policy-based authorization* sesuai PRD Bagian 7.
- **Tugas:** Implementasi `StudentPolicy`, `ScorePolicy`, `AttendancePolicy`, `MeetingPolicy`, `LetterPolicy`.
- **Target:** Setiap endpoint resource (misal: `GET /students/{id}`) wajib memverifikasi kepemilikan/relasi user (bukan hanya cek role).
- **Test:** Login sebagai wali A, coba akses data anak wali B → harus 403.

## 2. CRUD Akademik UI (Medium Priority)
Penyelesaian modul akademik (Siswa, Mapel, Kelas) ke UI dashboard.
- **Tugas:** Membuat tabel & form CRUD di Admin Dashboard.
- **Target:** Integrasi Vue dengan API resource (`index`, `store`, `update`, `destroy`).

## 3. PPDB Integrasi (Medium Priority)
Penyelesaian alur registrasi calon siswa.
- **Tugas:** Menyelesaikan `PPDBRegistrationController` & form di halaman `/ppdb`.
- **Target:** Data pendaftar masuk ke DB dengan status `baru` (perlu verifikasi admin).

---

## Panduan Test Manual (Wajib Dijalankan)

### Uji 1: Autentikasi & Authorization
1. **Login Admin:** Akses `http://localhost:8000/login`, masukkan `admin@madani.test` / `secret`.
2. **Redirect:** Pastikan masuk ke `/app/admin`.
3. **Cek Akses:** Coba akses `/app/guru` (sebagai admin) → harusnya **bisa** (sesuai role). Jika sebaliknya (user guru akses admin) → harus **403**.

### Uji 2: IDOR (Insecure Direct Object Reference)
1. **Data:** Pastikan ada data santri A (milik Wali A) dan santri B (milik Wali B).
2. **Skenario:** Login sebagai Wali A → akses API `/api/students/{id_santri_B}`.
   - **Harapan:** `403 Forbidden` (bukan 200).

### Uji 3: Landing Page & Dynamic Settings
1. **Admin Settings:** Buka `/app/admin/settings` (tab "Identitas Dayah").
2. **Ubah Data:** Ganti "Nama Lembaga", simpan.
3. **Verifikasi:** Refresh halaman utama `/` (Landing Page).
4. **Harapan:** Nama di footer dan elemen landing berubah otomatis (fetch dinamis).

### Uji 4: Audit Logging
1. **Aksi:** Update profil Kepala Sekolah di tab Settings.
2. **Verifikasi:** Jalankan perintah di terminal untuk cek log:
   `php artisan tinker --execute="dump(App\Models\ActivityLog::latest()->first()->toArray());"`
3. **Harapan:** Muncul record baru dengan `action: principal.update` dan `payload` berisi data lama & baru.
