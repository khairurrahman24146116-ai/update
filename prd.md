# Product Requirements Document (PRD) v2.0
## Sistem Informasi Akademik SMA Madani Al-Aziziyah
**Status:** Revisi — divalidasi langsung terhadap source code repo `madaniv2`
**Tanggal:** 7 September 2026

---

## 0. Catatan Revisi — Kenapa Versi Ini Berbeda

PRD sebelumnya ditolak client. Setelah divalidasi ulang langsung ke source code (bukan dari ingatan/chat), ditemukan **dua akar masalah nyata**:

1. **PRD lama mendeskripsikan role yang tidak ada di sistem.** "Kepala Sekolah" ditulis sebagai role login terpisah dengan dashboard & menu sendiri — padahal di database, kolom `role` pada tabel `users` hanya berisi enum: `admin`, `bendahara`, `guru`, `wali_murid`. Tidak ada akun/login "Kepala Sekolah". String `kepala_sekolah` memang muncul di kode, tapi hanya sebagai **label** pada tabel `tanda_tangans` (penanda pemilik tanda tangan digital untuk rapor) — bukan role, bukan akun, bukan dashboard. Yang menjalankan fungsi itu tetap Admin.
2. **Routing dan logic-nya memang berantakan di level kode**, bukan cuma soal dokumentasi. Ditemukan duplikasi logic besar-besaran antara `routes/api.php` dan `routes/web.php`.

Detail lengkap kedua temuan ada di Bagian 6.

---

## 1. Ringkasan Sistem (Sesuai Kondisi Aktual)

Sistem informasi akademik untuk SMA Madani Al-Aziziyah (Laravel 13, PHP 8.3, MySQL, Tailwind CSS 4, Alpine.js). Mendigitalisasi absensi, penjadwalan, penilaian/rapor, SPP, surat-menyurat, dan manajemen pengguna.

## 2. Role & Hak Akses (Terkonfirmasi dari `users` table enum)

Hanya **4 role** yang benar-benar ada sebagai akun login:

| Role | Fungsi Utama (dari controller & route aktual) |
|---|---|
| **admin** | Kontrol penuh: kelas, mapel, siswa (+import/export Excel), penugasan guru-mapel, jadwal, komponen nilai, absensi guru (lihat), manajemen user, surat resmi, pesan masuk, pertemuan (approve/reject), activity log. Admin **juga bertindak sebagai penandatangan rapor** ("Kepala Sekolah") — bukan role terpisah, hanya label tanda tangan yang di-upload lewat menu profil Admin. |
| **guru** | Absensi guru (check-in/out), absensi siswa per sesi, input nilai (termasuk batch & import Excel), lihat jadwal, surat masuk untuk guru. |
| **bendahara** | Dashboard keuangan, rekap SPP (+export CSV), tandai bayar/batal pembayaran SPP. Ini **satu-satunya role** yang boleh mencatat pembayaran — Admin hanya bisa melihat. |
| **wali_murid** | Dashboard anak, lihat rapor, surat masuk, ajukan kontak/pertemuan ke sekolah. |

> Catatan penting: Jika client memang menginginkan Kepala Sekolah sebagai role login terpisah dengan dashboard monitoring sendiri (kehadiran guru, aktivitas admin, dsb — seperti di draft lama), ini **belum diimplementasikan sama sekali** di kode dan perlu masuk sebagai kebutuhan baru (lihat Bagian 7), bukan dianggap fitur yang sudah ada.

## 3. Struktur Database — Validasi Tabel (26 tabel aplikasi + tabel bawaan Laravel)

Semua tabel di bawah **dicek satu per satu**: apakah punya Model, dan apakah Model itu dipakai minimal 1 Controller.

| Tabel | Model | Status Pemakaian |
|---|---|---|
| users | User | Aktif — inti autentikasi & role |
| classrooms | Classroom | Aktif — 5 controller |
| subjects | Subject | Aktif — 4 controller |
| teacher_subjects | TeacherSubject | Aktif — 3 controller (mapping guru↔mapel↔kelas) |
| students | Student | Aktif — 8 controller |
| schedules | Schedule | Aktif — 4 controller |
| attendances | Attendance | Aktif — absensi siswa |
| teacher_attendances | TeacherAttendance | Aktif — absensi guru |
| score_components | ScoreComponent | Aktif — bobot nilai (Tugas/PH/UTS/UAS) |
| scores | Score | Aktif — nilai siswa + generator rapor PDF |
| student_fees | StudentFee | Aktif — data tagihan SPP |
| payment_receipts | PaymentReceipt | Aktif — bukti bayar SPP |
| financial_sequences | FinanceSequence | Aktif — dipakai `ReceiptNumberService` untuk penomoran kuitansi otomatis (tidak muncul di controller manapun, makanya sekilas terlihat "tidak terpakai" — padahal dipakai lewat service layer) |
| letters | Letter | Aktif — surat resmi/pengumuman yang dipublikasikan admin |
| student_letter_requests | ActiveLetterRequest | Aktif — **fitur berbeda** dari `letters`: ini pengajuan "Surat Aktif Siswa" oleh wali/siswa dengan alur approve, verifikasi SPP, dan pengambilan fisik surat |
| contact_messages | ContactMessage | Aktif — pesan masuk dari wali murid |
| meetings | Meeting | Aktif — pengajuan pertemuan wali↔sekolah dengan approve/reject |
| activity_logs | ActivityLog | Aktif — ditulis lewat `ActivityLogger` service |
| tanda_tangans | TandaTangan | Aktif — file tanda tangan digital (Kepala Sekolah & Wali Kelas) untuk PDF rapor/surat |
| password_reset_tokens, sessions, cache, jobs, personal_access_tokens, dst | — | Tabel bawaan Laravel (Sanctum, session driver `database`, queue driver `database`) — normal, bukan tabel custom |

**Kesimpulan validasi database:** Tidak ditemukan tabel custom yang benar-benar "mati" (zero-usage). Yang membuatnya *terlihat* seperti banyak tabel tak terpakai:
- `financial_sequences` hanya dipanggil dari service layer, tidak dari controller — mudah terlewat saat audit sekilas.
- `letters` dan `student_letter_requests` sama-sama tentang "surat" tapi untuk keperluan berbeda (pengumuman resmi vs pengajuan surat aktif siswa), dan tidak ada penjelasan/pemisahan yang jelas di UI/menu — ini kemungkinan besar sumber kesan "database ganda/tidak jelas" yang dirasakan client.
- Penamaan file migrasi `score_components_table.php` tidak konsisten (kurang prefix `create_`) dibanding migrasi lain — bukan bug, tapi indikasi housekeeping yang kurang rapi.

## 4. Modul Fitur (per controller aktual)

| Modul | Controller | Ringkasan |
|---|---|---|
| Kelas & Mapel | ClassroomController, SubjectController, TeacherSubjectController | CRUD kelas, mapel, penugasan guru |
| Siswa | StudentController, StudentImportExportController | CRUD siswa, pindah kelas, import/export Excel |
| Jadwal | ScheduleController | CRUD jadwal, tampilan mobile |
| Absensi | AttendanceController, TeacherAttendanceController | Absensi siswa & guru, realtime, export CSV |
| Nilai & Rapor | ScoreComponentController, ScoreController, RaporVerificationController | Input nilai, import Excel, generate rapor PDF dengan QR verifikasi publik |
| SPP | SPPController, BendaharaController | Tagihan, pembayaran, rekap, export |
| Surat | LetterController, ActiveLetterController | Surat resmi & pengajuan surat aktif siswa |
| Komunikasi | ContactController, MeetingController | Pesan wali↔sekolah, pengajuan pertemuan |
| Manajemen User | UserController | CRUD akun, reset password, aktif/nonaktif |
| Audit | ActivityLogController | Log aktivitas admin |
| Profil & TTD | ProfileController | Ganti password, upload tanda tangan digital |

## 5. Dashboard per Role (Aktual)

| Role | Dashboard |
|---|---|
| admin | `/app/admin` — statistik keseluruhan |
| guru | `/app/dashboard` — kelas hari ini, absensi belum diisi |
| bendahara | `/app/bendahara` — rekap SPP & keuangan |
| wali_murid | `/app/wali-murid` — kehadiran & nilai anak |

## 6. Temuan Validasi Routing — Ini Akar Masalah Utamanya

### 6.1 Duplikasi logic API vs Web (masalah paling serius)

Hampir semua resource inti (Classroom, Subject, Student, Schedule, ScoreComponent, TeacherSubject, Score, Attendance) **diimplementasikan dua kali**:
- Satu set method JSON API murni (`index`, `store`, `show`, `update`, `destroy`) di `routes/api.php`
- Satu set method terpisah untuk Blade view (`webIndex`, `webCreate`, `webStore`, `webEdit`, `webUpdate`, `webDestroy`, dst) di `routes/web.php`

Contoh nyata di `StudentController`: ada 6 method versi API dan 8 method versi Web yang melakukan hal serupa dengan cara berbeda. Pola yang sama terulang di 6 controller lain. Ini bukan cuma soal "gaya kode" — ini artinya **setiap perubahan bisnis logic harus dilakukan dua kali**, dan gampang sekali salah satu sisi ketinggalan update (itulah kemungkinan besar sumber bug/inkonsistensi yang bikin client komplain "logikanya hancur").

### 6.2 Konfigurasi `apiPrefix` kosong

Di `bootstrap/app.php`, `apiPrefix` di-set ke string kosong (`''`), sehingga rute API tidak punya prefix `/api` sama sekali. Rute seperti `GET /students` (API) hidup di root path yang sama dengan rute web lain, bukan konvensi standar Laravel. Ini menambah kebingungan struktural dan risiko konflik penamaan ke depan.

### 6.3 Daftar pengecualian CSRF yang sangat luas

`validateCsrfTokens(except: [...])` mengecualikan hampir semua endpoint resource utama (`students/*`, `classrooms/*`, `schedules/*`, dst) dari proteksi CSRF — ini diperlukan untuk endpoint API berbasis token Sanctum, tapi karena API dan Web tidak dipisahkan dengan jelas (lihat 6.2), pengecualian ini berpotensi melebar ke rute yang seharusnya tetap dilindungi.

### 6.4 Kode mati (dead code)

`app/Listeners/LogSuccessfulLogin.php` dan `LogSuccessfulLogout.php` adalah stub kosong — terdaftar sebagai listener tapi tidak melakukan apa-apa. Kemungkinan sisa scaffolding yang lupa diisi atau dihapus.

### 6.5 Role "Kepala Sekolah" tidak pernah diimplementasikan sebagai akun

Lihat Bagian 0 & 2 — tidak ada `role:kepala_sekolah` di middleware manapun, tidak ada di enum database.

## 7. Kebutuhan Keamanan — Proteksi IDOR (Insecure Direct Object Reference)

> Catatan: karena repo `madaniv2` **tidak akan dilanjutkan**, bagian ini ditulis sebagai kebutuhan wajib untuk pengembangan berikutnya (bukan hasil audit ulang kode lama). Semua poin di bawah adalah syarat yang harus dipenuhi di setiap endpoint yang menerima ID resource dari user (URL, form, maupun JSON body).

### 7.1 Prinsip Dasar
Setiap request yang mengakses data berdasarkan ID (`{student}`, `{letter}`, `{meeting}`, `{studentFee}`, `{teacher_subject}`, dst) **wajib** melalui dua lapis validasi sebelum data ditampilkan/diubah/dihapus:
1. **Autentikasi** — user sudah login (sudah ada via `auth:sanctum`).
2. **Otorisasi kepemilikan/relasi** — user yang login benar-benar berhak atas record spesifik itu, bukan cuma "punya role yang benar."

Role-check (`role:guru`, `role:admin`, dst) saja **tidak cukup** — itu cuma memastikan jenis user, bukan memastikan record itu miliknya. Ini titik paling rawan IDOR di sistem seperti ini.

### 7.2 Kebutuhan Fungsional per Role

| Role | Aturan Wajib |
|---|---|
| **wali_murid** | Hanya boleh mengakses data (rapor, absensi, tagihan SPP, surat) milik anak yang terdaftar sebagai anaknya sendiri. Endpoint `wali-murid/rapor/{student}`, `wali.letters.show`, dsb wajib memverifikasi `student.parent_id === auth()->id()` (atau relasi setara) sebelum return data — **bukan** cuma cek role `wali_murid`. |
| **guru** | Hanya boleh mengakses/mengubah data absensi, nilai, dan siswa dari kelas/mapel yang benar-benar diampu (relasi `teacher_subjects`). Endpoint seperti `scores/{score}`, `attendances/{attendance}` wajib cek kepemilikan lewat relasi guru↔mapel↔kelas, bukan hanya cek `role:guru`. |
| **bendahara** | Boleh akses semua data SPP (memang lingkup kerjanya lintas siswa), tapi transaksi (`mark-paid`, `mark-unpaid`) wajib mencatat `user_id` pelaku di `activity_logs`/`payment_receipts` untuk audit trail. |
| **admin** | Akses luas by design, tapi tetap wajib logging di setiap aksi CRUD sensitif (hapus siswa, reset password user lain, dsb) agar bisa ditelusuri. |

### 7.3 Implementasi Teknis yang Disyaratkan

- **Gunakan Laravel Policy per model** (`StudentPolicy`, `ScorePolicy`, `AttendancePolicy`, `MeetingPolicy`, `LetterPolicy`, dst) dan panggil `$this->authorize()` / `Gate::authorize()` di setiap method controller yang menerima route-model-binding — jangan andalkan middleware role saja.
- **Route-model binding wajib discope** ke relasi user, bukan sekadar `findOrFail($id)` global. Contoh: siswa yang diambil untuk wali murid harus lewat query yang sudah difilter `where('parent_id', auth()->id())`, bukan `Student::find($id)` lalu baru dicek belakangan (rawan lupa dicek).
- **ID yang predictable (auto-increment) + tidak ada ownership check = celah IDOR langsung tereksploitasi** dengan cara mengganti angka di URL. Untuk resource yang diakses publik/semi-publik (contoh: `rapor/verifikasi/{kode}`), pastikan `{kode}` adalah token acak/hash yang tidak bisa ditebak — bukan ID database biasa.
- **Endpoint export/print (PDF, CSV)** — sering luput dari proteksi karena dianggap "cuma nampilin," padahal ini yang paling sering jadi celah IDOR. Semua endpoint `*/export`, `*/print`, `*-pdf` wajib melalui pengecekan otorisasi yang sama seperti endpoint biasa.
- **Response error yang konsisten** — saat akses ditolak karena bukan pemilik data, kembalikan `403 Forbidden` yang sama persis dengan kasus role salah, supaya tidak membocorkan informasi apakah suatu ID "ada tapi bukan milik user" vs "tidak ada sama sekali" (mencegah enumerasi ID).

### 7.4 Kriteria Uji (Definition of Done untuk keamanan IDOR)

Sebelum fitur dianggap selesai, wajib diuji manual minimal untuk skenario berikut:
- Login sebagai wali murid A, coba akses ID siswa milik wali murid B lewat URL langsung → harus ditolak (403), bukan malah menampilkan data.
- Login sebagai guru A, coba input/ubah nilai untuk kelas yang bukan diampunya → harus ditolak.
- Login sebagai satu wali murid, coba akses/print rapor & surat aktif siswa lain via ID di URL → harus ditolak.
- Ganti angka ID secara berurutan (1, 2, 3, ...) di endpoint yang menerima parameter ID pada semua role non-admin → tidak boleh ada satupun yang bocor data user lain.

## 8. Rekomendasi Tindak Lanjut

1. **Putuskan satu sumber kebenaran per resource**: jika web app (Blade) adalah antarmuka utama, evaluasi apakah endpoint API murni untuk resource yang sama (Classroom, Subject, Student, dll) masih diperlukan — jika untuk kebutuhan mobile app terpisah di masa depan, dokumentasikan itu secara eksplisit; jika tidak, pertimbangkan konsolidasi agar tidak ada dua implementasi paralel.
2. **Perbaiki `apiPrefix`** menjadi `/api` standar agar rute API dan Web terpisah jelas secara struktural, lalu sempitkan pengecualian CSRF hanya untuk path `/api/*`.
3. **Bersihkan listener kosong** — isi logic-nya (misal benar-benar mencatat login/logout ke `activity_logs`) atau hapus jika memang tidak dibutuhkan.
4. **Klarifikasi ke client**: apakah kebutuhan "Kepala Sekolah punya akun & dashboard sendiri" itu masih diinginkan? Jika ya, ini fitur baru (role baru + middleware + dashboard), bukan revisi dari yang sudah ada — perlu di-scope terpisah dengan effort tersendiri.
5. **Perjelas beda menu "Surat Resmi" (Letter) vs "Surat Aktif Siswa" (ActiveLetterRequest)** di level UI/naming supaya tidak terkesan fitur duplikat ke user/client.
6. **Rapikan penamaan migrasi** yang tidak konsisten (`score_components_table.php` → idealnya `create_score_components_table.php`) untuk housekeeping.

## 9. Metodologi Pengembangan (Alur Kerja untuk Build Ulang)

Karena repo `madaniv2` tidak dilanjutkan dan pengembangan dimulai dari awal, setiap fitur (mulai dari Bagian 4 — Modul Fitur) wajib dibangun mengikuti alur berikut. Tujuannya: hindari campur-aduk backend, endpoint, UI, dan logic seperti yang jadi salah satu penyebab masalah di repo lama.

**Prinsip dasar:** jangan minta AI/developer membangun satu fitur sekaligus penuh (backend + frontend + login + dashboard). Pecah dulu per lapisan — setiap lapisan punya konteks sendiri, jadi hasilnya lebih rapi dan lebih gampang direvisi ketimbang digabung sekaligus.

### 9.1 Urutan Wajib per Fitur

| # | Tahap | Output |
|---|---|---|
| 1 | Definisikan business process | Alur kerja fitur secara bisnis (siapa melakukan apa, kapan) |
| 2 | Tentukan data yang dibutuhkan | Daftar entitas & field yang terlibat |
| 3 | Buat struktur database | Migration + relasi antar tabel |
| 4 | Buat daftar endpoint | Daftar route (method + path) sebagai kontrak API — lihat format di 9.2 |
| 5 | Buat sample JSON response | Contoh response sukses & error per endpoint, disepakati sebelum coding frontend |
| 6 | Buat UI template reusable | Layout, card, form, table, modal, empty/loading/error state — **belum** terhubung ke API |
| 7 | Buat frontend service | Fungsi pemanggil API (fetch/axios wrapper) |
| 8 | Buat composable/store | State management (loading, error, data) yang menjembatani service ↔ UI |
| 9 | Integrasikan data asli ke UI | Sambungkan store/service ke template yang sudah jadi di tahap 6 |

### 9.2 Endpoint sebagai Kontrak

Backend dan endpoint disepakati **sebelum** frontend disentuh. Endpoint = kontrak resmi antara backend dan frontend, contoh format:

```
POST   /api/students
GET    /api/students
GET    /api/students/:id
PUT    /api/students/:id
DELETE /api/students/:id
```

Backend dibangun lengkap dengan: business process → struktur database → validasi request → endpoint → response JSON → error handling — semua difinalkan dulu sebagai kontrak, baru frontend mengikuti struktur data yang sudah jelas itu (tidak menebak-nebak bentuk data).

> Catatan konsistensi dengan Bagian 6.2: kontrak endpoint ini otomatis mendorong pemakaian prefix `/api` yang jelas dan terpisah dari rute web — sejalan dengan rekomendasi perbaikan `apiPrefix` di PRD ini.

### 9.3 Pisahkan UI Template dari Frontend Logic

Dua fokus yang **tidak digabung** dalam satu langkah:

- **UI Template dulu** — fokus ke struktur tampilan yang reusable: layout, card, form, table, modal, plus state kosong/loading/alert/tombol. Belum perlu mikirin API sama sekali di tahap ini.
- **Baru Frontend Logic** — fokus ke interaksi, state, dan integrasi data: ambil data dari API, submit form, handle loading, handle error, state management, sambungkan data ke template yang sudah dibuat.

### 9.4 Manfaat yang Diharapkan

Dengan alur ini, tiap lapisan (Backend, Endpoint, UI, Frontend Logic) punya konteks sendiri-sendiri, sehingga:
- Struktur project lebih rapi dan tidak campur aduk (menghindari masalah seperti duplikasi logic API/Web di Bagian 6.1)
- Lebih mudah direvisi per lapisan tanpa mengganggu lapisan lain
- Lebih gampang dikembangkan/scalable
- Desain tidak terasa generic/template AI

## 10. Manajemen Identitas Sekolah & Kontrol Admin

### 10.1 Admin = Kontrol Penuh Website

Admin adalah satu-satunya role dengan akses penuh (CRUD) ke seluruh sisi pengelolaan website, bukan cuma modul akademik. Menu dashboard admin wajib mencakup:

| Menu | Cakupan |
|---|---|
| Landing Page | Kelola konten hero/section, teks sambutan, dll — dinamis, tidak hardcoded di kode |
| Identitas & Branding | Logo, favicon, nama sekolah, alamat, kontak |
| Berita | CRUD berita/artikel, kategori, status publish/draft |
| Galeri | Upload & kelola foto/video, pengelompokan per album |
| Pendaftaran (PPDB) | Gelombang pendaftaran, formulir, status pendaftar (baru/verifikasi/diterima/ditolak) |
| Akademik | Kelas, mapel, guru, jadwal, nilai, absensi (lihat Bagian 4) |
| Portal/Akun | Kelola semua akun user lintas role (guru, wali murid, bendahara) — create, reset password, nonaktifkan akun |

Semua modul di atas wajib dilindungi middleware `role:admin` **plus** authorization check per aksi mengikuti aturan proteksi IDOR di Bagian 7 — cek role saja tidak cukup.

### 10.2 "Kepala Sekolah" sebagai Data, Bukan Role/Akun

Menegaskan ulang Bagian 0 & 2: Kepala Sekolah **tidak** dibuatkan akun/login terpisah. Kepala Sekolah adalah **data** yang dikelola Admin lewat menu "Pengaturan > Identitas Sekolah / Pejabat Sekolah", dengan field:

- Nama Kepala Sekolah (yang sedang menjabat)
- Gelar/jabatan
- Foto
- File tanda tangan digital (untuk rapor & surat resmi)
- Tanggal mulai menjabat (opsional, untuk riwayat)

Field ini jadi **single source of truth**. Semua tempat yang menampilkan info Kepala Sekolah — landing page (sambutan), PDF rapor, PDF surat resmi, halaman profil sekolah — wajib mengambil data secara dinamis dari sini. Tidak boleh ada nama/gelar/tanda tangan yang di-hardcode di kode atau digenerate ulang manual per dokumen.

### 10.3 Alur Pergantian Kepala Sekolah

Saat terjadi pergantian jabatan, admin cukup:
1. Buka menu Pengaturan > Identitas Sekolah / Pejabat Sekolah
2. Update nama, foto, dan upload tanda tangan digital yang baru
3. Simpan

Setelah disimpan, seluruh sistem (rapor baru, surat baru, landing page) otomatis menampilkan data Kepala Sekolah yang baru — tanpa deploy ulang kode, tanpa buat akun baru. Dokumen/rapor **lama** yang sudah terbit tetap merujuk ke tanda tangan versi saat dokumen itu dibuat, agar riwayat historis tidak berubah retroaktif.

### 10.4 Logging Perubahan

Setiap perubahan pada data Kepala Sekolah/Identitas Sekolah dicatat di `activity_logs` (admin yang mengubah, waktu, nilai lama vs baru) untuk kebutuhan audit.

## 11. Arsitektur CRUD — Pisahkan Logic dari Routing

Menindaklanjuti temuan Bagian 6.1 (dulu `web.php` dan `api.php` sama-sama berisi logic penuh, jadi harus diubah dua kali dan gampang tidak sinkron). Untuk build baru, `web.php`/`api.php` **hanya boleh berisi definisi rute** — mapping URL ke Controller method. Tidak ada query, validasi, atau business logic langsung di file routes.

### 11.1 Lapisan Wajib per Fitur CRUD

| Lapisan | Tanggung Jawab | Contoh File |
|---|---|---|
| **Route** (`web.php`/`api.php`) | Hanya mapping `method + path → Controller@method`, plus middleware. Tidak ada logic. | `Route::resource('students', StudentController::class);` |
| **Form Request** | Validasi input + otorisasi kepemilikan (lihat Bagian 7). Terpisah dari controller. | `StoreStudentRequest`, `UpdateStudentRequest` |
| **Controller** | "Tipis" — cuma terima request tervalidasi, panggil Service, kembalikan response/view. Tidak ada query DB langsung, tidak ada logic bisnis. | `StudentController::store()` cukup ±3-5 baris |
| **Service** | Isi logic bisnis sesungguhnya: create/update/delete, aturan bisnis, transaksi DB. **Satu Service dipakai bareng oleh Controller Web dan Controller API** — ini kunci menghindari duplikasi dari Bagian 6.1. | `StudentService` |
| **Model / Eloquent** | Relasi antar tabel, query scope (`scopeActive()`, dst), accessor/mutator. | `Student` model |
| **Resource** (khusus API) | Bentuk response JSON konsisten. | `StudentResource` |
| **Policy** | Otorisasi per-record (aturan IDOR Bagian 7). Dipanggil dari Controller atau Form Request. | `StudentPolicy` |

### 11.2 Contoh Alur Satu Fitur CRUD

```
Route (web.php)          : POST /app/admin/students → StudentController@store
Form Request              : StoreStudentRequest → validasi field + authorize()
Controller (tipis)        : panggil StudentService::create($request->validated())
Service (logic asli)      : cek aturan bisnis, simpan ke Student model, catat activity_log
Response                  : redirect()/view() untuk web, StudentResource untuk API
```

Jika nanti endpoint API untuk resource yang sama juga dibutuhkan, `ApiStudentController` tinggal memanggil `StudentService` yang **sama persis** — bukan menulis ulang logic seperti yang terjadi di repo lama.

### 11.3 Aturan Konkret untuk Agent/Developer

- `web.php` dan `api.php` **dilarang** memuat closure berisi query/logic — hanya `Route::get/post/put/delete(...)->name(...)` yang mengarah ke Controller.
- Controller method **maksimal ±15-20 baris**. Kalau lebih dari itu, tandanya ada logic yang harus dipindah ke Service.
- Semua validasi lewat Form Request class, tidak ditulis manual dengan `$request->validate([...])` langsung di controller.
- Semua otorisasi per-record (Bagian 7) dipanggil lewat Policy, bukan `if` manual dalam controller.
- Kalau ada dua Controller (Web & API) untuk resource yang sama, keduanya **wajib** memanggil Service yang sama — dilarang menyalin logic.

### 11.4 Manfaat

Konsisten dengan alur pengembangan di Bagian 9: begitu struktur database & kontrak endpoint (tahap 3-4) selesai, Service dan Form Request bisa dibangun sebagai "logic murni" tanpa campur tangan routing, lalu Controller tinggal jadi penghubung tipis. Hasilnya: `web.php` tetap pendek dan mudah dibaca sebagai peta rute, bug lebih gampang dilacak (satu tempat per jenis tanggung jawab), dan tidak ada lagi masalah dua implementasi paralel seperti temuan Bagian 6.1.

---

*PRD ini disusun berdasarkan pembacaan langsung terhadap source code repo `madaniv2` (migrations, models, controllers, routes) per 7 September 2026, bukan dari asumsi/dokumen sebelumnya. Metodologi pengembangan (Bagian 9) mengacu pada alur kerja AI-assisted development yang ditetapkan pemilik project.*