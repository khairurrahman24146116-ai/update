<template>
  <PublicLayout>
    <section class="w-full bg-surface-container-low px-gutter py-space-sm">
      <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-space-xs">
        <div class="flex items-center gap-space-xs font-label-sm text-label-sm text-on-surface-variant">
          <router-link to="/" class="hover:text-primary transition-colors">Beranda</router-link>
          <span class="material-symbols-outlined text-[13px] text-outline">chevron_right</span>
          <span class="text-on-surface font-semibold">Penerimaan Peserta Didik Baru (PPDB)</span>
        </div>
        <span class="font-code-md text-code-md text-outline">PPDB SMA MADANI AL-AZIZIYAH</span>
      </div>
    </section>

    <section class="w-full max-w-7xl mx-auto px-gutter pt-space-xl pb-space-lg">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-end">
        <div class="lg:col-span-8 flex flex-col gap-space-xs">
          <Reveal slow>
            <div class="inline-flex items-center gap-space-xs w-fit bg-primary-fixed text-primary px-space-md py-1 rounded-full font-label-sm text-label-sm">
              <span class="material-symbols-outlined text-[15px]">how_to_reg</span>
              <span>PENDAFTARAN SANTRI BARU</span>
            </div>
          </Reveal>
          <Reveal slow :delay="160">
            <h1 class="font-display-lg text-display-lg text-on-surface font-bold tracking-tight">Pendaftaran Online SMA Madani Al-Aziziyah</h1>
          </Reveal>
          <Reveal slow :delay="320">
            <p class="font-body-md text-body-md text-on-surface-variant max-w-3xl leading-relaxed">Pilih gelombang pendaftaran yang sedang dibuka, lalu isi formulir pendaftaran. Informasi gelombang ditetapkan oleh panitia sekolah dan diumumkan melalui laman ini.</p>
          </Reveal>
        </div>
      </div>
    </section>

    <section class="w-full max-w-7xl mx-auto px-gutter pb-space-xl">
      <div class="grid grid-cols-1 gap-space-md pb-6">
        <div v-for="(w, i) in waves" :key="w.id" class="stagger-slow landing-card bg-surface-container-lowest border border-outline-variant rounded-[28px] shadow-sm p-space-md flex flex-col md:flex-row md:items-center justify-between gap-3" :style="{ animationDelay: `${Math.min(i, 6) * 140}ms` }">
          <div>
            <div class="font-title-md text-title-md text-on-surface font-semibold">{{ w.name }}</div>
            <div class="font-code-md text-code-md text-on-surface-variant">{{ w.start_date?.slice(0, 10) }} — {{ w.end_date?.slice(0, 10) }} • {{ statusLabel(w.status) }}</div>
            <p v-if="w.description" class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ w.description }}</p>
          </div>
          <button @click="openForm(w)" class="landing-cta px-5 py-2 bg-primary text-on-primary border border-outline-variant rounded-full text-sm font-semibold whitespace-nowrap shadow-sm hover:bg-primary-hover">Daftar</button>
        </div>
        <EmptyState
          v-if="!loaded"
          icon="schedule"
          title="Memuat informasi gelombang"
          description="Mohon tunggu, informasi gelombang PPDB sedang dimuat."
        />
        <EmptyState
          v-else-if="!waves.length"
          icon="calendar_month"
          title="Belum ada gelombang PPDB yang dibuka"
          description="Jadwal gelombang ditetapkan oleh panitia sekolah dan akan diumumkan melalui laman ini. Silakan pantau halaman ini secara berkala."
        />
      </div>

      <Transition name="panel">
      <div v-if="showForm" class="w-full bg-surface-container-lowest border border-outline-variant rounded-[28px] shadow-md p-space-lg space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-outline-variant">
          <h3 class="font-headline-md text-headline-md text-on-surface font-semibold">Formulir Pendaftaran — {{ selected?.name }}</h3>
          <button @click="showForm = false" class="w-8 h-8 rounded-full text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low flex items-center justify-center">✕</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <input v-model="form.name" placeholder="Nama lengkap *" class="px-4 py-2.5 border border-outline-variant rounded-md bg-surface-container-lowest text-sm focus:outline-none focus:border-primary-container" />
          <input v-model="form.nisn" placeholder="NISN" class="px-4 py-2.5 border border-outline-variant rounded-md bg-surface-container-lowest text-sm focus:outline-none focus:border-primary-container" />
          <input v-model="form.phone" placeholder="No. HP" class="px-4 py-2.5 border border-outline-variant rounded-md bg-surface-container-lowest text-sm focus:outline-none focus:border-primary-container" />
          <input v-model="form.email" placeholder="Email" type="email" class="px-4 py-2.5 border border-outline-variant rounded-md bg-surface-container-lowest text-sm focus:outline-none focus:border-primary-container" />
          <textarea v-model="form.address" placeholder="Alamat" rows="2" class="md:col-span-2 px-4 py-2.5 border border-outline-variant rounded-md bg-surface-container-lowest text-sm focus:outline-none focus:border-primary-container"></textarea>
        </div>
        <p v-if="formError" class="text-xs text-error bg-error-container border border-error rounded px-2 py-1 text-on-error-container">{{ formError }}</p>
        <div class="flex gap-2">
          <button @click="submit" :disabled="submitting" class="px-5 py-2 bg-primary text-on-primary border border-outline-variant rounded-full text-sm font-semibold shadow-sm disabled:opacity-50 hover:bg-primary-hover transition-[transform,box-shadow,background-color,opacity]">{{ submitting ? 'Mengirim...' : 'Kirim Pendaftaran' }}</button>
          <button @click="showForm = false" class="px-4 py-2 bg-surface-container-low text-on-surface border border-outline-variant rounded-full text-sm font-semibold">Batal</button>
        </div>
        <p class="font-label-sm text-label-sm text-on-surface-variant">* Nama wajib diisi. Informasi yang Anda kirimkan digunakan untuk keperluan pendaftaran.</p>
      </div>
      </Transition>

      <Transition name="toast">
        <div v-if="toast" class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 px-4 py-2 rounded-full text-sm font-bold border border-outline-variant shadow-sm" :class="toastType === 'error' ? 'bg-error-container text-on-error-container' : 'bg-primary-fixed text-on-surface'">{{ toast }}</div>
      </Transition>
    </section>
  </PublicLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import PublicLayout from '../Components/PublicLayout.vue'
import EmptyState from '../Components/EmptyState.vue'
import Reveal from '../Components/Reveal.vue'

const waves = ref([])
const loaded = ref(false)
const showForm = ref(false)
const selected = ref(null)
const submitting = ref(false)
const formError = ref('')
const toast = ref('')
const toastType = ref('success')

const form = ref({ name: '', nisn: '', phone: '', email: '', address: '' })

const statusMap = { buka: 'Dibuka', tutup: 'Ditutup', archived: 'Arsip' }
function statusLabel(status) { return statusMap[status] || status || '—' }

onMounted(async () => {
  try {
    const r = await fetch('/api/ppdb-waves/public')
    if (r.ok) waves.value = await r.json()
  } catch { /* abaikan */ } finally {
    loaded.value = true
  }
})

function openForm(w) {
  selected.value = w
  showForm.value = true
  formError.value = ''
}

function notify(m, t = 'success') {
  toast.value = m
  toastType.value = t
  setTimeout(() => { toast.value = '' }, 3000)
}

async function submit() {
  formError.value = ''
  if (!form.value.name) { formError.value = 'Nama wajib diisi'; return }
  submitting.value = true
  try {
    const r = await fetch('/api/ppdb-registrations', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ wave_id: selected.value.id, ...form.value }),
    })
    const j = await r.json()
    if (!r.ok) throw new Error(j.message || Object.values(j.errors || {}).flat().join(', '))
    notify('Pendaftaran berhasil dikirim')
    showForm.value = false
    form.value = { name: '', nisn: '', phone: '', email: '', address: '' }
  } catch (e) {
    formError.value = e.message
    notify(e.message, 'error')
  } finally {
    submitting.value = false
  }
}
</script>