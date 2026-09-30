<template>
  <AppShell title="Surat Masuk">
    <BackButton fallback="/app/wali-murid" />
    <div class="bg-white border border-outline-variant rounded-xl shadow-sm">
      <EmptyState
        icon="mail"
        title="Modul Surat belum tersedia"
        description="Fitur surat masuk dan pengumuman resmi sekolah belum terhubung ke backend. Anda akan dapat melihat surat di sini setelah modul tersedia."
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
