# Landing Page — SMA Madani Al-Aziziyah

Status: Vue 3.5 + GSAP interaktif — build OK (203kB gz 78kB)
File utama: `resources/js/Pages/Landing.vue` | Mount: `resources/js/app.js` → `#app` di `resources/views/welcome.blade.php`

## 1. Analisa Stitch Beranda (ID: 3a1a1d3a6f114fec9530a848966913de)
Design System: Academic Neo-Modern — primary `#1E3A8A` navy, secondary `#0D9488` teal, tertiary `#F59E0B` amber, ink `#0F172A`, surface `#FAF8FF`, font Plus Jakarta Sans (headline) + Inter (body) + JetBrains Mono (telemetry), radius 4px, shadow neubrutalist `4px 4px 0 #0F172A`.

11 section:
1. Top telemetry bar `AKREDITASI: A / UNGGUL • PPDB 2025/2026 DIBUKA`
2. Header glassmorphic `backdrop-blur-md bg-white/85 border-b-2`
3. Hero asymmetric 2-kolom + HUD `[LIVE: COLLABORATIVE COMMONS]` + `94.8% PTN`
4. Profil 4 value `Integritas/Keilmuan/Kepemimpinan/Akhlak Mulia`
5. Stats `98.4% / 35+ / 1:14 / Predikat A`
6. 4 TRACK akademik `SAINS-TEKNIK / GLOBAL STUDIES / AI-COMPUTING / LEADERSHIP`
7. Ekskul filterable 6 kartu (OSIS, Robotika, KIR, Basket, Futsal, Creative Lab)
8. Prestasi 4 kartu `OSN/LKTI/Debate/Youth Summit`
9. Fasilitas 5 kartu `Perpus 25k / Lab / Smart Class 75" / Sport Hall / Masjid 1200`
10. CTA PPDB navy `GELOMBANG 1 / TES 08 Mar / HASIL 15 Mar`
11. Footer ink `NPSN: 20109921`

## 2. Animasi Interaktif (GSAP 3 + ScrollTrigger)
Dep: `gsap` sudah `npm install gsap` — import di `Landing.vue`.

| Animasi | Target | Trigger | Efek |
|---|---|---|---|
| Header hide/show | `header` | `scroll y > lastY && y>80` | `translate-y-full` |
| Hero stagger | `.hero-title/.hero-desc/.hero-cta/.hero-meta span/.hero-visual` | `onMounted` | `y 40→0, opacity 0→1, stagger 0.08` |
| Floating badge | `floatingBadge` | `onMounted loop` | `y -6 yoyo 1.2s sine.inOut` |
| Card reveal | `.reveal-card` | `ScrollTrigger.batch` | `y 30→0, stagger 0.08` |
| Count-up | `.stat-num [data-target]` | `ScrollTrigger once top 90%` | `innerText 0→target 1.2s` |
| 3D tilt | `.tilt-card` | `mousemove` | `rotationY ±6deg, rotationX ±6deg, y -4` |
| Magnetic | `.mag-btn` | `mousemove` | `x*0.15, y*0.2` |
| Ekskul FLIP | `TransitionGroup ekskul` | `activeFilter change` | `enter y20→0 scale 0.98 / leave opacity 0` |

## 3. File yang Diubah
- `vite.config.js` — tambah `vue()` + font `Plus Jakarta Sans/Inter/JetBrains Mono`
- `resources/css/app.css` — token `@theme` + `.shadow-neu/.border-neu`
- `resources/js/app.js` — `createApp(Landing).mount('#app')`
- `resources/js/Pages/Landing.vue` — 11 section + state `activeFilter/mobileOpen/activeNav/headerHidden` + composable `scrollTo/magnetic/resetMagnetic`
- `resources/views/welcome.blade.php` — skeleton `<div id="app">`

## 4. Cara Eksekusi (Checklist)
```bash
npm install          # sudah, gsap@ terbaru
npm run build        # prod: vite build → public/build (OK)
npm run dev          # dev HMR
php artisan serve    # http://localhost:8000
php artisan test --filter=ClassroomApiTest  # backend sanity 2/2 passed
```
Verifikasi visual: http://localhost:8000 — cek header hide, hero, badge float, scroll reveal, tilt hover, magnetic button, filter ekskul animasi.

## 5. Next Step (Belum Dieksekusi)
- [ ] Ganti placeholder kampus gradient dengan image asli Stitch (screens 5412547c / d585cf54 / 0c5a3c95)
- [ ] Tambah `useApi` composable untuk fetch `/api/classrooms` preview di landing (opsional)
- [ ] `php artisan make:policy` lanjutan untuk rapor/SPP bila landing butuh data dinamis
- [ ] Optimasi: `fontaine` atau `optimizedFallbacks:false` hilangkan warning vite fonts
