<template>
  <AppShell title="Jadwal Mengajar">
    <BackButton fallback="/app/guru" />
    <div v-if="loading" class="p-8 text-center text-sm text-on-surface-variant">Memuat jadwal...</div>
    <EmptyState v-else-if="!list.length" icon="calendar_month" title="Belum ada jadwal" description="Jadwal mengajar harian akan tampil di sini." />
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="s in list" :key="s.id" class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm space-y-2">
        <div class="flex justify-between"><span class="font-bold">{{ s.subject?.name ?? '—' }}</span><span class="badge">{{ labelDay(s.day_of_week) }}</span></div>
        <div class="text-sm font-semibold">{{ s.classroom?.name ?? '—' }}</div>
        <div class="text-xs text-on-surface-variant">{{ s.start_time.slice(0,5) }} — {{ s.end_time.slice(0,5) }}</div>
        <div v-if="s.classroom" class="text-xs text-on-surface-variant">Ruang: {{ s.classroom.name }}</div>
      </div>
    </div>
  </AppShell>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../../composables/useAuth.js'
import BackButton from '../../Components/BackButton.vue'
import EmptyState from '../../Components/EmptyState.vue'
import AppShell from '../../Components/AppShell.vue'
const { user } = useAuth()
const list = ref([])
const loading = ref(false)
const dayMap = { mon:'Senin', tue:'Selasa', wed:'Rabu', thu:'Kamis', fri:'Jumat', sat:'Sabtu', sun:'Minggu' }
function labelDay(d) { return dayMap[d] ?? d }
onMounted(async()=>{
  loading.value = true
  try {
    const r = await fetch(`/api/schedules?teacher_id=${user.value?.id}`)
    const j = await r.json()
    list.value = j.data ?? j
    if (!Array.isArray(list.value)) list.value = []
  } catch {}
  finally { loading.value = false }
})
</script>
