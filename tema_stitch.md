# Rencana Re-teme Frontend → Gaya Stitch "Premium Sovereign Identity"

> Dokumen kerja. Tujuan akhir: mengalihkan seluruh SPA Vue dari Academic Neo-Modern
> (neubrutalist) ke gaya Stitch sesuai 3 screen pada proyek Google Stitch
> (project ID `862623093795084651`).
>
> **Status: DIAJUKAN, belum dieksekusi.** Saat mulai dikerjakan, jalankan bertahap
> dan verifikasi build + test di akhir (Fase E). Backend/API tidak boleh disentuh.

---

## 1. Konteks & Keputusan User

- **Cakupan:** seluruh SPA, semua role (Admin, Guru, Bendahara, Wali Murid) + halaman publik.
- **Sistim desain:** Ganti penuh ke gaya Stitch. `design.md` + `tokens.css`/`app.css` + font ikut di-update.
- **Konten/data:** Pertahankan konten asli sekolah & data jujur — angka statistik diambil dari API, modul kosong memakai `EmptyState`. **Dilarang** menyalin KPI/grafik/angka fiktif dari screen (token AI, proyek, tagihan, uptime, dll).
- **Font:** Ganti ke **DM Sans** (body) + **Plus Jakarta Sans** (headline). Hapus Inter & JetBrains Mono.
- **Backend protected:** routes/api.php, Controllers/Api, Models, migrations, Services, Policies, Middleware, Requests, .env, seeder eksisting — semua TIDAK boleh diubah.

---

## 2. Sumber Design (Stitch Screen)

Tiga screen yang dijadikan acuan dari proyek Stitch:

| Screen | Screen ID | File unduhan (temp) |
|---|---|---|
| Platform Desain UI Berbasis AI (landing) | `304bc41ff5354ec780976e9b16654640` | `platform.html` / `platform.png` |
| Authentication (login) | `57c95e7cdfb747829e8bbbb1da2fc3ae` | `auth.html` / `auth.png` |
| Dashboard Admin (console) | `d44b17fff10142dca18d52e51eaa1a21` | `dashboard.html` / `dashboard.png` |

Lokasi unduhan: `C:\Users\SHANKSYIEMAN\AppData\Local\Temp\opencode\stitch\`

Catatan: model tidak bisa membaca PNG; acuan utama adalah **HTML** (tailwind-config JSON
di dalamnya memuat seluruh token resmi).

### 2.1 Token resmi Stitch (diekstrak dari `tailwind.config` di dashboard.html)

**Warna:**
| Token | Stitch (baru) | Lama (Neo-Modern) |
|---|---|---|
| surface / background | `#faf9f5` | `#faf8ff` |
| surface-dim | `#dadad6` | `#d2d9f4` |
| surface-bright | `#faf9f5` | `#faf8ff` |
| surface-container-lowest | `#ffffff` | `#ffffff` |
| surface-container-low | `#f4f4f0` | `#f2f3ff` |
| surface-container | `#eeeeea` | `#eaedff` |
| surface-container-high | `#e8e8e4` | `#e2e7ff` |
| surface-container-highest | `#e2e3df` | `#dae2fd` |
| surface-variant | `#e2e3df` | `#dae2fd` |
| on-surface | `#1a1c1a` | `#0F172A` |
| on-surface-variant | `#44474e` | `#444651` |
| outline | `#75777f` | `#757682` |
| outline-variant | `#c4c6cf` | `#c5c5d3` |
| primary | `#000d27` | `#1E3A8A` (bukan: `#00236f`) |
| on-primary | `#ffffff` | `#ffffff` |
| primary-container | `#0b2347` (navy, tombol utama) | `#1e3a8a` |
| on-primary-container | `#778bb5` | `#90a8ff` |
| secondary | `#455e8f` | `#0D9488` (teal) / `#006a61` |
| on-secondary | `#ffffff` | `#ffffff` |
| secondary-container | `#adc7fe` | `#86f2e4` / `#89f5e7` |
| on-secondary-container | `#395282` | `#006f66` / `#005049` |
| tertiary | `#010e22` | `#3e2400` |
| tertiary-container | `#162439` | `#5c3800` |
| on-tertiary-container | `#7d8ba5` | `#ef9900` |
| error | `#ba1a1a` | `#ba1a1a` (sama) |
| on-error | `#ffffff` | `#ffffff` |
| error-container | `#ffdad6` | `#ffdad6` |
| on-error-container | `#93000a` | `#93000a` |

Nilai lain yang tersedia: `inverse-surface #2f312e`, `inverse-on-surface #f1f1ed`,
`surface-tint #4a5e86`, `primary-fixed #d7e2ff`, `primary-fixed-dim #b2c7f4`,
`on-primary-fixed #011b3f`, `on-primary-fixed-variant #32476c`, `inverse-primary #b2c7f4`,
`secondary-fixed #d8e2ff`, `secondary-fixed-dim #adc7fe`, `on-secondary-fixed #001a41`,
`on-secondary-fixed-variant #2c4675`, `tertiary-fixed #d5e3ff`, `tertiary-fixed-dim #b9c7e2`,
`on-tertiary-fixed #0d1c30`, `on-tertiary-fixed-variant #3a475e`.

**Tipografi (fontSize):**
| Level | ukuran / lh / ls / weight |
|---|---|
| display-lg | 40px / 48px / -0.03em / 700 |
| display-lg-mobile | 32px / 40px / -0.025em / 700 |
| headline-lg | 28px / 36px / -0.02em / 700 |
| headline-md | 22px / 30px / -0.015em / 600 |
| headline-sm | 18px / 26px / -0.01em / 600 |
| body-lg | 16px / 26px / -0.01em / 400 |
| body-md | 14px / 22px / 0em / 400 |
| body-sm | 12px / 18px / 0.01em / 400 |
| label-md | 12px / 16px / 0.02em / 600 |
| label-sm | 11px / 14px / 0.04em / 500 |
| label-lg | 14px / 20px / 0em / 600 |

Font family: headline/label/display = `Plus Jakarta Sans`; body = `DM Sans` (401 yg benar).

**Radius:** `DEFAULT: 1rem`, `lg: 2rem`, `xl: 3rem`, `full: 9999px`. Tombol & input = pill (`rounded-full`), kartu `rounded-DEFAULT`/`rounded-[28px]`.

**Spacing:** `space-xs 0.25rem`, `space-sm 0.5rem`, `space-md 1rem`, `space-lg 1.5rem`, `space-xl 2.5rem`, `gutter 1.5rem`, `gutter-mobile 1rem`, `margin 3rem`, `margin-mobile 1.25rem`.

**Elevasi:** shadow soft, bukan hard offset. Contoh dari HTML: `shadow-[0_1px_3px_rgba(11,35,71,0.03)]`, `shadow-sm`, `shadow-[0_12px_36px_rgba(11,35,71,0.04)]`.

**Ikon:** Material Symbols (sudah dipakai app ini).

---

## 3. Arsitektur Frontend Saat Ini (untuk referensi)

- Framework: Vue 3 SPA via `welcome.blade.php`, `createWebHistory` (Lihat `resources/js/router.js`).
- Route utama: `/`, `/profil`, `/fasilitas`, `/prestasi`, `/kontak`, `/ppdb`, `/berita/:id`, `/galeri/:id`, `/login`, `/app/admin*` (8 hal), `/app/guru*` (5 hal), `/app/bendahara`, `/app/wali-murid*` (4 hal).
- Roles & guard: `router.beforeEach` (meta `requiresAuth` + `role`).
- CSS: `resources/css/tokens.css` + `resources/css/app.css` (duplikat token! update keduanya).
- Font: `vite.config.js` (bunny plugin) — saat ini PJS [400-800], Inter [400-600], JetBrains Mono [400-600].
- Auth: `composables/useAuth.js` (token localStorage + patch fetch). Site setting: `composables/useSiteSettings.js`.

### Inventaris gaya per area (hasil audit)

- **Hanya token (aman, cukup edit CSS):** `Profil`, `Fasilitas`, `Prestasi`, `Kontak`, plus komponen `AppLogo`, `SectionHeading`, `PageHeader`, `StatCard`, `EmptyState`.
- **Hybrid (token warna, tapi shadow keras hex):** `Landing.vue`, `PublicLayout.vue`.
- **Fully hardcoded (perlu sweep kelas):** `Login`, `PPDB`, `BeritaDetail`, `GaleriDetail`, seluruh `Admin/*` (incl. 4 fragment Settings), `Guru/*`, `Bendahara/Dashboard`, `WaliMurid/*`, dan komponen `BackButton.vue`.
- Pola berulang pada app pages: `min-h-screen bg-[#faf8ff] text-[#0F172A]`, nav `bg-white/85 border-b-2 border-[#0F172A]`, kartu `border-2 border-[#0F172A] shadow-[4px_4px_0px_#0F172A]`, tombol `bg-[#1E3A8A]`, ribbon kapur `bg-[#d0f03e]`, logout hover `bg-[#ba1a1a]`.
- `font-mono`/`font-code`/`font-code-md` dipakai untuk label identitas (NIS/NISN, kode mapel, koordinat); `telemetry` dipakai di `Fasilitas.vue`, `PageHeader.vue`, `SectionHeading.vue`.
- JetBrains Mono dipakai inline di `Login.vue` (~14) dan ribbon `Admin/Dashboard.vue` + `Admin/Settings.vue`.

---

## 4. Fase Kerja

### Fase A — Fondasi token & primitif
File: `resources/css/tokens.css`, `resources/css/app.css`, `vite.config.js`, `design.md`

1. Update palet di **kedua** file token ke nilai Stitch (tabel §2.1).
2. Ubah shadow keras → soft (var `--shadow-neu-*` / `shadow-card` = `0 1px 3px rgba(11,35,71,.04)` dst).
3. Radius: `--radius-lg: 2rem`, `--radius-xl: 3rem`, DEFAULT `1rem`; `.btn`/`.input` → `rounded-full` (pill).
4. Font `vite.config.js`: buang `Inter` & `JetBrains Mono`; tambah `DM Sans` (400,500,600) + PJS tetap.
5. Map ulang `--font-mono`/`--font-code` → DM Sans tapi pertahankan `font-variant-numeric: tabular-nums` agar label NISN/kode tetap rapi.
6. Update spec `design.md` (nama sistim → "Stitch Student Portal" / per keputusan user, ganti palet + tipografi + elevation).

### Fase B — Komponen bersama
File: `BackButton.vue`, `PublicLayout.vue` (+ verifikasi `AppLogo`, `SectionHeading`, `PageHeader`, `StatCard`, `EmptyState`)

- `BackButton.vue`: ganti hex keras → token & shadow soft + pill.
- `PublicLayout.vue`: swap `#0F172A` di shadow (header, tombol "Portal Akademik/Masuk", hamburger) ke token soft; pertahankan sticky glass header.
- Pastikan komponen token lainnya tidak memakai shadow keras.

### Fase C — Sweep halaman hardcoded (~30 file)
Pemetaan penggantian (mekanis, sedapatnya glob/per-token):

| Kelas lama | Kelas baru |
|---|---|
| `bg-[#faf8ff]` / `bg-[#FAF8FF]` | `bg-surface` |
| `text-[#0F172A]` | `text-on-surface` |
| `text-[#444651]` | `text-on-surface-variant` |
| `border-2 border-[#0F172A]` & `border-[#0F172A]` | border ringan `border-outline-variant/…` (hapus neubrutal 2px keras) |
| `shadow-[2px_2px_0px_#0F172A]`, `[...3px...]`, `[...4px...]` | `shadow-sm` |
| `shadow-[6px_6px_0px_#0F172A]` | `shadow-md` |
| `bg-[#1E3A8A]` | `bg-primary-container` |
| `text-[#1E3A8A]` | `text-primary` |
| `bg-[#d0f03e]` (kapur) | `bg-primary-fixed` / `bg-secondary-container` sesuai makna |
| `bg-[#86f2e4]` | `bg-secondary-container` |
| `bg-[#ffdad6]` / `text-[#93000a]` | `bg-error-container` / `text-on-error-container` |
| logout hover `bg-[#ba1a1a]` | `bg-error` (dengan `text-on-error`) |
| `font-['JetBrains_Mono',...]` (Login) & `style="font-family:'JetBrains Mono'..."` (ribbon) | DM Sans / token |
| tombol aksi `rounded-lg` | `rounded-full` (pill) |

Catatan: `tokens.css` dan `app.css` mereduplikasi token — update keduanya agar konsisten.
Emoji tile di halaman admin dipertahankan (konten, bukan styling).

### Fase D — 3 layar signature
1. **`Login.vue`** ← Acuan `auth.html` (screen Authentication)
   - Kartu login tengah `max-w-480` `rounded-[28px]` bg `surface-container-lowest`, input pill, tombol pill `primary-container`, badge/trust (identitas sekolah), logo; **tetap** gunakan logika `useAuth` (`doLogin`, error, loading).
2. **`Admin/Dashboard.vue`** ← Acuan `dashboard.html` (screen Dashboard Admin)
   - Topbar (search + pill actions), kartu sambutan + chip role, grid StatCard **angka asli** dari `/api/students`, `/api/schedules`, `/api/users` (pola onMounted sekarang dipertahankan), grid modul manajemen `rounded-DEFAULT shadow-sm` + ikon.
   - **Jangan** salin KPI fiktif (1.482 proyek, 28.4M token, Rp 4.250.000, uptime 99.98%, dst). Bagian chart/feed diganti EmptyState/kartu status honest.
3. **`Landing.vue`** ← Acuan `platform.html` (screen Platform)
   - Struktur: hero + pill badge + bento/statistik + steps + social-proof, konten sekolah dari `useSiteSettings`/berita/PPDB dipertahankan, tanpa angka palsu.

### Fase E — Verifikasi
- `npm run build` (pastikan manifest font = PJS + DM Sans, DM Sans ter-bundle).
- `php artisan test --compact` — harus tetap 67/67 (test API tidak tersentuh).
- Checklist visual: `/`, `/login`, `/app/admin`, `/app/guru`, `/app/bendahara`, `/app/wali-murid`, dan halaman publik lain.

---

## 5. Luar Cakupan / Batasan

- Backend, API, migrasi, model, dokumen blade (`resources/views/archive/stitch/`), `.env`.
- Konten teks halaman tetap (kecuali adaptasi layout).
- Tidak membuat KPI/grafik/data baru yang fiktif.

---

## 6. Referensi File Kunci

- Screens Stitch: temp dir `C:\Users\SHANKSYIEMAN\AppData\Local\Temp\opencode\stitch\`
  (`auth.html`, `dashboard.html`, `platform.html` + PNG).
- `resources/css/tokens.css`, `resources/css/app.css`, `vite.config.js`, `design.md`
- `resources/js/Pages/Login.vue`, `Pages/Admin/Dashboard.vue`, `Pages/Landing.vue`
- `resources/js/Components/` (BackButton, PublicLayout, AppLogo, SectionHeading, PageHeader, StatCard, EmptyState)
- `resources/js/router.js`, `composables/useAuth.js`, `composables/useSiteSettings.js`

---

## 7. Log Pekerjaan

- [ ] Fase A — token & font
- [ ] Fase B — komponen bersama
- [ ] Fase C — sweep halaman hardcoded
- [ ] Fase D — 3 screen signature
- [ ] Fase E — build + test + checklist visual