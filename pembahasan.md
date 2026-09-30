# Pembahasan Uji IDOR (Insecure Direct Object Reference)

Berikut adalah rencana pengujian untuk memastikan proteksi IDOR pada sistem informasi akademik SMA Madani Al-Aziziyah, khususnya pada modul Siswa (`StudentPolicy`).

## Skenario Uji

1. **Wali Murid - Akses Tidak Sah:**
   - Login sebagai Wali Murid A.
   - Melakukan `GET /api/students/{id_siswa_B}` (Siswa milik Wali Murid B).
   - **Harapan:** `403 Forbidden`.

2. **Wali Murid - Akses Sah:**
   - Login sebagai Wali Murid A.
   - Melakukan `GET /api/students/{id_siswa_A}` (Siswa milik Wali Murid A).
   - **Harapan:** `200 OK`.

3. **Guru - Akses Tidak Sah:**
   - Login sebagai Guru A.
   - Melakukan `GET /api/students/{id_siswa_kelas_lain}` (Siswa dari kelas yang tidak diampu oleh Guru A).
   - **Harapan:** `403 Forbidden`.

4. **Admin - Akses Sah:**
   - Login sebagai Admin.
   - Melakukan `GET /api/students/{id_siswa_mana_saja}`.
   - **Harapan:** `200 OK`.

## Kesimpulan Audit IDOR

Status proteksi IDOR saat ini:
- **Modul Siswa (`StudentController`):** Sudah menerapkan `$this->authorize('view', $student)` yang terhubung ke `StudentPolicy`. Ini adalah baseline proteksi yang sudah oke.
- **Modul Lain:** Belum dilakukan pengecekan mendalam pada `Classroom`, `Subject`, `TeacherSubject`, `Attendance`, `Score`, `SPP`, `Letter`, dan `Meeting`.
- **Target:** Baru memetakan 4 skenario uji untuk model `Student`. Secara keseluruhan, kita baru mengerjakan **1/8 (satu dari delapan)** modul utama yang memerlukan proteksi IDOR.

Tugas berikutnya adalah memperluas perencanaan dan pengujian ke modul-modul lainnya (terutama `ScorePolicy` dan `TeacherSubjectPolicy`) untuk memastikan keamanan menyeluruh pada sistem.







/////////final?//////



