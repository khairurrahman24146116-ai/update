<template>
<AppShell title="Dashboard Guru">
  <div class="p-6 bg-white border border-outline-variant rounded-xl shadow-sm">
    <h2 class="font-headline-md text-headline-md mb-2">Kelas Hari Ini</h2>
    <p class="text-sm text-on-surface-variant">Jadwal dan kehadiran mengajar hari ini.</p>
    <router-link to="/app/guru/absensi" class="btn btn--primary btn--sm mt-3">Buka Absensi</router-link>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <router-link to="/app/guru/absensi" class="p-5 border border-outline-variant rounded-xl shadow-sm bg-white hover:shadow-md hover:-translate-y-0.5 transition-[transform,box-shadow] group">
      <span class="w-10 h-10 rounded-full bg-secondary-container border border-outline-variant flex items-center justify-center"><span class="material-symbols-outlined !w-5 !h-5 text-primary">fact_check</span></span>
      <div class="font-bold mt-3">Absensi Mengajar</div>
      <div class="text-xs text-on-surface-variant mt-1">Check-in / Check-out & absen siswa</div>
      <div class="text-xs font-bold text-primary mt-3 group-hover:underline">Buka →</div>
    </router-link>
    <router-link to="/app/guru/jadwal" class="p-5 border border-outline-variant rounded-xl shadow-sm bg-white hover:shadow-md hover:-translate-y-0.5 transition-[transform,box-shadow] group">
      <span class="w-10 h-10 rounded-full bg-primary-fixed border border-outline-variant flex items-center justify-center"><span class="material-symbols-outlined !w-5 !h-5 text-primary">calendar_month</span></span>
      <div class="font-bold mt-3">Jadwal Mengajar</div>
      <div class="text-xs text-on-surface-variant mt-1">Lihat jadwal harian mengajar</div>
      <div class="text-xs font-bold text-primary mt-3 group-hover:underline">Lihat →</div>
    </router-link>
    <router-link to="/app/guru/nilai" class="p-5 border border-outline-variant rounded-xl shadow-sm bg-white hover:shadow-md hover:-translate-y-0.5 transition-[transform,box-shadow] group">
      <span class="w-10 h-10 rounded-full bg-tertiary-fixed border border-outline-variant flex items-center justify-center"><span class="material-symbols-outlined !w-5 !h-5 text-primary">edit_note</span></span>
      <div class="font-bold mt-3">Input Nilai</div>
      <div class="text-xs text-on-surface-variant mt-1">Kelas & mapel → siswa → nilai</div>
      <div class="text-xs font-bold text-primary mt-3 group-hover:underline">Buka →</div>
    </router-link>
  </div>
</AppShell>
</template>
<script setup>
import AppShell from '../../Components/AppShell.vue'
</script>
