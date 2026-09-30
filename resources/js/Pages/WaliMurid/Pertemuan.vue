<template>
  <AppShell title="Ajukan Pertemuan">
    <BackButton fallback="/app/wali-murid" />
    <div class="bg-white border border-outline-variant rounded-xl shadow-sm">
      <EmptyState
        icon="event_available"
        title="Modul Pertemuan belum tersedia"
        description="Fitur pengajuan pertemuan wali murid dengan sekolah belum terhubung ke backend. Anda dapat mengajukan pertemuan di sini setelah modul tersedia."
        :show-gap="true"
      >
      </EmptyState>
    </div>
  </AppShell>
</template>
<script setup>
import BackButton from '../../Components/BackButton.vue'
import EmptyState from '../../Components/EmptyState.vue'
import AppShell from '../../Components/AppShell.vue'
</script>
