# Akun Testing — password: `password123` untuk semua (kecuali test@example.com)

| Role | Email | Password | Catatan |
|---|---|---|---|
| admin | admin@madani.test | password123 | Full access (jadwal, CRUD) |
| guru (demo UAT nilai) | guru@madani.test | password123 | Terhubung kelas X-1 + Fisika (TeacherSubject/Schedule) — untuk /app/guru/nilai |
| guru | guruA@madani.test | password123 | Alternatif guru |
| guru | guruB@madani.test | password123 | Alternatif guru |
| guru (tanpa mapping) | guru2@madani.test | password123 | Tidak punya TeacherSubject/Schedule — untuk test 403 Input Nilai |
| bendahara | bendahara@madani.test | password123 |  |
| wali_murid (demo) | wali_murid@madani.test / NISN `0071234567` | password123 | Anak: Andi (siswa X-1) — login via email atau NISN Andi + password wali — untuk /app/wali-murid/nilai |
| wali_murid | wali2@madani.test | password123 | Anak lain — untuk test isolasi nilai |
| — | test@example.com | (factory) | Akun factory awal, bukan seeder testing |

## URL
- Login: `/login`
- Admin jadwal: `/app/admin/schedules`
- Guru jadwal: `/app/guru/jadwal` (read-only)
- Guru absensi: `/app/guru/absensi`
- Guru nilai: `/app/guru/nilai` (pilih kelas X-1 + Fisika)
- Wali nilai: `/app/wali-murid/nilai`
