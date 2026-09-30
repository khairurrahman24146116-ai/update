# Perubahan — Konsistensi UI/UX Area `/app` (Semua Role)

Dokumen ini memuat pembahasan & rencana penormalan UI/UX untuk seluruh area portal
(dashboard dan halaman dalam) agar konsisten dan tidak berantakan, mengacu pada
standar desain publik (`resources/js/Components/PublicLayout.vue`, `resources/css/tokens.css`,
`resources/css/app.css`) dan arahan audit sebelumnya.

## Konteks

Situs publik (11 halaman: Landing, Profil, Prestasi, PPDB, Kontak, Fasilitas, BeritaList,
BeritaDetail, GaleriList, GaleriDetail, Login) sudah rapi dan konsisten karena memakai satu
`PublicLayout` + token desain. Namun area `/app` (18 halaman lintas 4 role) tidak memiliki
layout/shell terpusat, sehingga setiap role "mendekorasi" sendiri dengan struktur, radius,
tombol, ikon, tipografi, dan jargon yang berbeda.

Halaman area `/app` (18):
- **Admin (9):** Dashboard, Settings, Students, Teachers, Kelas, Mapel, Schedules, Finance, ImportSiswa
- **Guru (5):** Dashboard, Jadwal, Absensi, ImportAbsensi, Nilai
- **Bendahara (1):** Dashboard
- **WaliMurid (4):** Dashboard, NilaiAnak, Surat, Pertemuan

## Temuan Audit

1. **Tidak ada shell app terpusat (akar masalah).**
   - Admin Dashboard: `max-w-[1400px]`, strip pill "ROLE/ADMIN::AKSES PENUH", tanpa sticky nav (`Admin/Dashboard.vue`).
   - Dashboard Guru/Bendahara/Wali: `max-w-[80rem]` + sticky nav + tombol Keluar (`Guru/Dashboard.vue`, `Bendahara/Dashboard.vue`, `WaliMurid/Dashboard.vue`).
   - Halaman dalam Admin: **tanpa nav, tanpa tombol Keluar** — hanya `BackButton` (contoh `Admin/Students.vue`, `Admin/Teachers.vue`, `Admin/Settings.vue`). Admin harus mundur ke dashboard untuk logout.
   - Halaman dalam Guru & Wali: punya nav+logout+BackButton (contoh `Guru/Nilai.vue`, `WaliMurid/NilaiAnak.vue`, `WaliMurid/Pertemuan.vue`).
   - Bahkan tidak konsisten dalam satu role pun: `Guru/Jadwal.vue` menaruh BackButton di nav (tanpa Keluar); `Guru/Nilai.vue` menaruh Keluar di nav.

2. **Radius serampangan untuk komponen semakna** (token `--radius` ada tapi tak dipakai seragam):
   - `rounded-[28px]`: hero/kartu public (`Landing.vue`), hero & CTA Admin Dashboard.
   - `rounded`: kartu stat Admin Dashboard, wrapper tabel Students/Teachers, hero Guru Dashboard.
   - `rounded-full`: kartu stat/header Bendahara & Wali, kontainer utama Bendahara/Wali.

3. **Tombol/input/badge di-handroll** padahal `.btn`, `.btn--primary`, `.input`, `.badge`, `.card--hover` sudah tersedia di `app.css` (hanya dipakai `EmptyState` memakai `.btn--primary`).

4. **Jargon & "aura" teknis tidak seragam** (menyiratkan klaim, mirip isu klaim palsu yang sudah dibersihkan di Settings/Login sesi sebelumnya):
   - Admin: "ADMIN :: AKSES PENUH • API TERHUBUNG", "KONTROL PUSAT SISTEM", "ADMIN :: ROOT", "SANCTUM AUTH", pill pulsing "Live dari API", header stat `[SANTRI_TERDAFTAR]`.
   - Guru: deskriptor kartu `font-mono opacity-60` ("Check-in / Check-out & absen siswa").
   - Bendahara: jujur memakai EmptyState "Backend Gap".
   - Wali: bersih (paling baik).

5. **Ikon campuran:** tile Admin/Guru memakai **emoji** (🎓📅 dsb); sistem resmi **material-symbols** (public, EmptyState, Login). Emoji inkonsisten & bergantung font OS.

6. **Tipografi/heading tidak seragam:** Admin `font-headline` di h1; Guru/Wali `font-bold text-xl`; tidak memakai skala token `text-headline-*`. Ukuran angka stat juga beda-beda (`text-2xl`, `text-3xl`).

7. **Data kosong 3 gaya:** Admin `—`; Bendahara teks "Data belum tersedia"; Wali `—`; Guru tanpa. Belum konsisten memakai `EmptyState`.

## Keputusan

- Ruang lingkup: **Opsi A — Shell app tunggal + normalisasi lengkap** (disetujui).
- Branding area `/app`: **satu identitas portal seragam** (nav sama untuk semua role;
  judul halaman menyesuaikan; aksen role tidak lagi dibedakan per warna).

## Rencana Eksekusi

### 1. Buat `resources/js/Components/AppShell.vue` (baru)
- `<nav class="nav-glass">` → `max-w-7xl mx-auto px-gutter-desktop h-16`.
  - Kiri: `AppLogo` + nama "SMA Madani Al-Aziziyah".
  - Kanan: badge role kecil netral + tombol **Keluar** (`.btn--ghost`).
- `<main class="max-w-7xl mx-auto px-gutter-mobile md:px-gutter-desktop py-space-lg space-y-6">` + slot.
- `<h1>` dari prop `title` memakai `font-headline-lg text-headline-lg text-on-surface`.
- `useAuth().doLogout()` → `router.push('/login')` (diambil dari tiap halaman).

### 2. Refactor 18 halaman ke `AppShell`
- Hapus wrapper `max-w-[1400px]` / `max-w-[80rem]`, hapus `<nav>` inline, hapus fungsi `logout()` per halaman.
- BackButton selalu di dalam konten (bukan di nav); tombol Keluar selalu di nav shell.
- Halaman dalam Admin kini otomatis mendapat nav + logout.

### 3. Normalisasi primitif visual
- Kartu konten/tile: `bg-white border border-outline-variant rounded-[28px] shadow-sm` (+ hover).
- Tombol: `.btn`, `.btn--primary`, `.btn--ghost`, `.btn--destructive`, `.btn--sm`, `.btn--lg`.
- Input/select/search: `h-10 px-3 rounded-full border border-outline-variant bg-white text-sm`.
- Status/chip: `.badge` atau chip `rounded-full` standar; deskriptor `font-mono opacity-60` → `text-xs text-on-surface-variant`.
- Ikon emoji → `material-symbols-outlined` dalam lingkaran `bg-surface-container-low border`.
- Data kosong/loading: `EmptyState` atau teks netral satu gaya.

### 4. Bersihkan jargon/misleading
- `Admin/Dashboard.vue`: hapus strip pill, "ADMIN :: ROOT", "SANCTUM AUTH", "Live dari API", header `[CODE]` → judul "Dashboard Admin" + subtitle netral + label stat "Santri/Guru/Akun Portal/Jadwal Mapel".
- `Settings.vue`: netralkan emoji & label (Riwayat Audit, Backup, dsb) tanpa mengubah logika.
- Wali/Bendahara: teks sudah rapi, hanya ikut. radius + AppShell.

### 5. Tidak disentuh
- Login.vue (sudah konsisten), logika/fetch/auth/API, layout public, routes, blade, DB, CSS tokens.

## Verifikasi

1. `npm run build` hijau.
2. `php artisan test --compact` tetap 67/67 (perubahan UI-only, tanpa sentuh API/PHP).
3. Grep clean di `resources/js`: `AKSES PENUH|API TERHUBUNG|KONTROL PUSAT|SANCTUM|Live dari API|ADMIN :: ROOT` dan emoji di area /app → 0 hasil.
4. QA manual tiap role (`admin|guru|bendahara|wali_murid@madani.test` / `password123`):
   - Nav seragam, tombol Keluar tersedia di semua halaman (termasuk halaman dalam Admin).
   - Radius & heading konsisten; tanpa emoji; tanpa jargon teknis.
   - Responsif 375px & desktop.

## Catatan

- Repo bukan git → tidak ada commit; `vendor/bin/pint --dirty` tidak berlaku (perubahan hanya Vue).
- Perubahan hanya kosmetik/UI; tidak ada perubahan migrasi, model, route, maupun otorisasi.