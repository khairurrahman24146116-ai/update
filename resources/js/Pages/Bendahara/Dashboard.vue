<template>
<AppShell title="Dashboard Bendahara">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-5 bg-white border border-outline-variant rounded-xl shadow-sm">
        <div class="text-xs text-on-surface-variant">Total Tagihan</div>
        <div class="text-2xl font-black mt-1">—</div>
        <div class="text-xs text-on-surface-variant">Belum ada data</div>
      </div>
      <div class="p-5 bg-white border border-outline-variant rounded-xl shadow-sm">
        <div class="text-xs text-on-surface-variant">Terbayar</div>
        <div class="text-2xl font-black mt-1">—</div>
        <div class="text-xs text-on-surface-variant">Belum ada data</div>
      </div>
      <div class="p-5 bg-white border border-outline-variant rounded-xl shadow-sm">
        <div class="text-xs text-on-surface-variant">Tunggakan</div>
        <div class="text-2xl font-black mt-1">—</div>
        <div class="text-xs text-on-surface-variant">Belum ada data</div>
      </div>
    </div>
</AppShell>
</template>
<script setup>
import AppShell from '../../Components/AppShell.vue'
</script>
