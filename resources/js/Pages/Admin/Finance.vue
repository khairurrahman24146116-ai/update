<template>
  <AppShell title="Keuangan SPP">
    <div class="space-y-4">
      <BackButton fallback="/app/admin" />
      <div class="bg-white p-6 rounded-xl border border-outline-variant shadow-sm">
        <p class="text-sm text-on-surface-variant">Rekapitulasi, tagihan &amp; pembayaran SPP.</p>
      </div>
      <div class="bg-white border border-outline-variant rounded-xl shadow-sm">
        <EmptyState
          icon="payments"
          title="Modul Keuangan belum tersedia"
          description="Rekap SPP, tagihan, dan pembayaran belum terhubung ke backend. Anda akan dapat mengelola keuangan di sini setelah modul tersedia."
          :show-gap="true"
        >
        </EmptyState>
      </div>
    </div>
  </AppShell>
</template>
<script setup>
import BackButton from '../../Components/BackButton.vue'
import EmptyState from '../../Components/EmptyState.vue'
import AppShell from '../../Components/AppShell.vue'
</script>
