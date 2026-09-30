import { createRouter, createWebHistory } from 'vue-router'
import Landing from './Pages/Landing.vue'
import Profil from './Pages/Profil.vue'
import Fasilitas from './Pages/Fasilitas.vue'
import Prestasi from './Pages/Prestasi.vue'
import Kontak from './Pages/Kontak.vue'
import Login from './Pages/Login.vue'
import PPDB from './Pages/PPDB.vue'
import AdminDashboard from './Pages/Admin/Dashboard.vue'
import GuruDashboard from './Pages/Guru/Dashboard.vue'
import BendaharaDashboard from './Pages/Bendahara/Dashboard.vue'
import WaliMuridDashboard from './Pages/WaliMurid/Dashboard.vue'
import AdminSettings from './Pages/Admin/Settings.vue'
import AdminStudents from './Pages/Admin/Students.vue'
import AdminTeachers from './Pages/Admin/Teachers.vue'
import AdminFinance from './Pages/Admin/Finance.vue'
import AdminSchedules from './Pages/Admin/Schedules.vue'
import AdminKelas from './Pages/Admin/Kelas.vue'
import AdminMapel from './Pages/Admin/Mapel.vue'
import ImportSiswa from './Pages/Admin/ImportSiswa.vue'
import GuruAbsensi from './Pages/Guru/Absensi.vue'
import GuruImportAbsensi from './Pages/Guru/ImportAbsensi.vue'
import GuruJadwal from './Pages/Guru/Jadwal.vue'
import GuruNilai from './Pages/Guru/Nilai.vue'
import WaliNilaiAnak from './Pages/WaliMurid/NilaiAnak.vue'
import WaliSurat from './Pages/WaliMurid/Surat.vue'
import WaliPertemuan from './Pages/WaliMurid/Pertemuan.vue'
import BeritaDetail from './Pages/BeritaDetail.vue'
import GaleriDetail from './Pages/GaleriDetail.vue'
import BeritaList from './Pages/BeritaList.vue'
import GaleriList from './Pages/GaleriList.vue'

const roleMap = { admin: '/app/admin', guru: '/app/guru', bendahara: '/app/bendahara', wali_murid: '/app/wali-murid' }
function currentRole() {
  try { return JSON.parse(localStorage.getItem('madani_user') || 'null')?.role || null } catch { return null }
}

const routes = [
    { path: '/', component: Landing },
    { path: '/profil', component: Profil },
    { path: '/fasilitas', component: Fasilitas },
    { path: '/prestasi', component: Prestasi },
    { path: '/kontak', component: Kontak },
    { path: '/ppdb', component: PPDB },
    { path: '/berita', component: BeritaList },
    { path: '/galeri', component: GaleriList },
    { path: '/berita/:id', component: BeritaDetail },
    { path: '/galeri/:id', component: GaleriDetail },
    { path: '/login', component: Login, meta: { guestOnly: true } },
    { path: '/app', redirect: () => roleMap[currentRole()] || '/' },
    { path: '/app/admin', component: AdminDashboard, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/app/admin/settings', component: AdminSettings, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/app/admin/students', component: AdminStudents, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/app/admin/teachers', component: AdminTeachers, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/app/admin/finance', component: AdminFinance, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/app/admin/kelas', component: AdminKelas, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/app/admin/mapel', component: AdminMapel, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/app/admin/schedules', component: AdminSchedules, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/app/admin/import-siswa', component: ImportSiswa, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/app/guru', component: GuruDashboard, meta: { requiresAuth: true, role: 'guru' } },
    { path: '/app/guru/jadwal', component: GuruJadwal, meta: { requiresAuth: true, role: 'guru' } },
    { path: '/app/guru/absensi', component: GuruAbsensi, meta: { requiresAuth: true, role: 'guru' } },
    { path: '/app/guru/import-absensi', component: GuruImportAbsensi, meta: { requiresAuth: true, role: 'guru' } },
    { path: '/app/guru/nilai', component: GuruNilai, meta: { requiresAuth: true, role: 'guru' } },
    { path: '/app/bendahara', component: BendaharaDashboard, meta: { requiresAuth: true, role: 'bendahara' } },
    { path: '/app/wali-murid', component: WaliMuridDashboard, meta: { requiresAuth: true, role: 'wali_murid' } },
    { path: '/app/wali-murid/nilai', component: WaliNilaiAnak, meta: { requiresAuth: true, role: 'wali_murid' } },
    { path: '/app/wali-murid/surat', component: WaliSurat, meta: { requiresAuth: true, role: 'wali_murid' } },
    { path: '/app/wali-murid/pertemuan', component: WaliPertemuan, meta: { requiresAuth: true, role: 'wali_murid' } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})
router.beforeEach((to) => {
  const token = localStorage.getItem('madani_token')
  if (to.meta.requiresAuth && !token) return '/login'
  if (to.meta.guestOnly && token) {
    const r = currentRole()
    return roleMap[r] || '/'
  }
  if (to.meta.role && token) {
    const r = currentRole()
    if (r && r !== to.meta.role) return roleMap[r] || '/login'
  }
  return true
})

export default router