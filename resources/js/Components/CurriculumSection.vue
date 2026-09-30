<template>
  <section class="max-w-7xl mx-auto w-full px-gutter py-space-lg">
    <div class="bg-surface-container-lowest p-space-lg sm:p-space-xl shadow-md">
      <Reveal slow>
        <div class="flex flex-col md:flex-row md:items-end justify-between pb-space-lg gap-space-md border-b border-outline-variant mb-space-lg">
          <div>
            <span class="font-code-md text-code-md text-primary font-semibold">BAGIAN 03 • STRUKTUR KURIKULUM</span>
            <h2 class="font-headline-lg text-headline-lg text-on-surface mt-1">Struktur Kurikulum SMA Madani Al-Aziziyah</h2>
          </div>
          <p class="font-body-sm text-body-sm text-on-surface-variant max-w-md">Kurikulum terpadu yang menyelaraskan pendidikan agama berbasis dayah salafiyah dengan pendidikan umum formal serta pengembangan keterampilan santri.</p>
        </div>
      </Reveal>

      <Reveal slow>
        <div class="bg-surface-container-low p-space-md sm:p-space-lg relative overflow-hidden mb-space-lg">
          <div class="absolute -left-6 -top-10 w-40 h-40 rounded-full bg-primary-fixed/40 pointer-events-none"></div>
          <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 bg-primary text-on-primary px-2.5 py-1 font-label-sm text-label-sm font-semibold uppercase tracking-wider">
              <span class="material-symbols-outlined text-[16px]">calendar_month</span>
              Kurikulum Terpadu 2026/2027
            </span>
            <h3 class="font-headline-md text-headline-md font-bold text-on-surface mt-2">Kurikulum Terpadu Dayah &amp; Pendidikan Formal</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mt-2 leading-relaxed max-w-3xl">Kurikulum Dayah Madani Al-Aziziyah Tahun Ajaran 2026/2027 mengusung konsep integrasi (terpadu) yang menyelaraskan pendidikan agama berbasis dayah salafiyah tradisional dengan pendidikan umum formal kementerian.</p>
          </div>
        </div>
      </Reveal>

      <Reveal slow>
        <div class="mb-space-lg">
          <span class="font-label-sm text-label-sm font-bold text-primary uppercase tracking-widest">Integrasi Pendidikan</span>
          <div class="mt-space-sm flex flex-col lg:flex-row items-stretch gap-space-sm">
            <template v-for="(el, i) in integrasi" :key="el.label">
              <div class="flex-1 bg-surface-container-low border border-outline-variant rounded p-space-md flex items-center gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-[transform,box-shadow]">
                <span class="material-symbols-outlined text-primary text-[22px]">{{ el.icon }}</span>
                <span class="font-label-sm text-label-sm font-semibold text-on-surface leading-tight">{{ el.label }}</span>
              </div>
              <span v-if="i < integrasi.length - 1" class="hidden lg:inline-flex items-center self-center text-outline shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
              </span>
            </template>
          </div>
        </div>
      </Reveal>

      <Reveal slow>
        <div role="tablist" aria-label="Struktur Kurikulum" class="flex flex-wrap items-center gap-space-xs pb-space-md">
          <button
            v-for="t in curriculumTabs"
            :key="t.id"
            type="button"
            role="tab"
            :aria-selected="activeTab === t.id"
            @click="activeTab = t.id"
            class="rounded-full px-space-md py-2 font-title-md text-title-md flex items-center gap-2 text-left leading-tight transition-[background-color,color,transform] duration-200"
            :class="activeTab === t.id ? 'bg-primary text-on-primary shadow-sm hover:bg-primary-hover' : 'bg-surface-container-low text-on-surface hover:bg-surface-container hover:-translate-y-0.5'"
          >
            <span class="material-symbols-outlined text-[18px] shrink-0">{{ t.icon }}</span>
            <span>{{ t.no }}. {{ t.title }}</span>
          </button>
        </div>
      </Reveal>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
        <div class="lg:col-span-8">
          <Transition name="tab" mode="out-in">
            <div :key="currentTab.id" class="space-y-space-lg">
              <div v-if="currentTab.id === 'formal'" class="space-y-space-lg">
                <div class="bg-surface-container-low p-space-md sm:p-space-lg shadow-sm">
                  <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ currentTab.heading }}</h3>
                  <p class="font-title-md text-title-md text-secondary font-semibold mt-1">{{ currentTab.subheading }}</p>
                  <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mt-2">{{ currentTab.desc }}</p>
                  <ul class="mt-space-md space-y-space-sm">
                    <li v-for="p in currentTab.points" :key="p" class="flex items-start gap-2">
                      <span class="material-symbols-outlined text-[18px] text-secondary mt-0.5 shrink-0">verified</span>
                      <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">{{ p }}</p>
                    </li>
                  </ul>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                  <div v-for="u in currentTab.units" :key="u.code" class="bg-surface-container-low p-space-md sm:p-space-lg flex flex-col gap-space-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-[transform,box-shadow]">
                    <div class="flex items-center justify-between">
                      <span class="w-11 h-11 rounded-full bg-primary-fixed text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[24px]">school</span>
                      </span>
                      <span class="font-label-sm text-label-sm font-bold text-primary uppercase tracking-wider">{{ u.code }}</span>
                    </div>
                    <div>
                      <h4 class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ u.name }}</h4>
                      <div class="mt-2 flex items-baseline gap-2">
                        <span class="font-label-sm text-label-sm text-outline uppercase">Akreditasi</span>
                        <span class="font-headline-lg text-headline-lg font-bold text-secondary">{{ u.akreditasi }}</span>
                      </div>
                    </div>
                    <a
                      :href="u.href"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="mt-auto inline-flex items-center gap-1.5 text-primary hover:text-primary-hover font-label-md text-label-md font-semibold transition-colors"
                    >
                      <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                      <span>{{ u.linkLabel }}</span>
                    </a>
                  </div>
                </div>
              </div>

              <div v-else-if="currentTab.id === 'pesantren'" class="space-y-space-lg">
                <div class="bg-surface-container-low p-space-md sm:p-space-lg shadow-sm">
                  <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ currentTab.heading }}</h3>
                  <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mt-2">{{ currentTab.desc }}</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-space-md">
                  <div v-for="c in currentTab.kitabCards" :key="c.label" class="group bg-surface-container-low p-space-md flex flex-col gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-[transform,box-shadow]">
                    <span class="material-symbols-outlined text-primary text-[24px] transition-transform duration-200 group-hover:scale-110">{{ c.icon }}</span>
                    <h4 class="font-title-md text-title-md font-bold text-on-surface uppercase leading-snug">{{ c.label }}</h4>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ c.desc }}</p>
                  </div>
                </div>
              </div>

              <div v-else class="space-y-space-lg">
                <div class="bg-surface-container-low p-space-md sm:p-space-lg shadow-sm">
                  <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ currentTab.heading }}</h3>
                </div>
                <div class="grid grid-cols-1 gap-space-md sm:grid-cols-2">
                  <div v-for="c in currentTab.programCards" :key="c.label" class="group bg-surface-container-low p-space-md flex flex-col gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-[transform,box-shadow]">
                    <span class="material-symbols-outlined text-primary text-[24px] transition-transform duration-200 group-hover:scale-110">{{ c.icon }}</span>
                    <h4 class="font-title-md text-title-md font-bold text-on-surface uppercase leading-snug">{{ c.label }}</h4>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ c.desc }}</p>
                  </div>
                </div>
                <div>
                  <span class="font-label-sm text-label-sm font-bold text-primary uppercase tracking-widest">Pembentukan Karakter &amp; Ekstrakurikuler</span>
                  <div class="mt-space-sm grid grid-cols-1 gap-space-sm sm:grid-cols-2">
                    <div v-for="k in currentTab.karakter" :key="k.label" class="bg-surface-container-low p-space-sm flex items-center gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-[transform,box-shadow]">
                      <span class="material-symbols-outlined text-primary text-[20px]">{{ k.icon }}</span>
                      <span class="font-body-md text-body-md text-on-surface font-medium">{{ k.label }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </Transition>
        </div>

        <aside class="lg:col-span-4">
          <div class="bg-primary text-on-primary p-space-lg relative overflow-hidden lg:sticky lg:top-24 shadow-md">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-on-primary/10 pointer-events-none"></div>
            <span class="font-label-sm text-label-sm font-bold uppercase tracking-widest opacity-80 relative z-10">Fokus Lulusan</span>
            <h4 class="font-headline-md text-headline-md font-bold mt-1 relative z-10">Santri Berilmu, Berkarakter &amp; Adaptif</h4>
            <ul class="mt-space-md space-y-space-sm font-body-sm text-body-sm relative z-10">
              <li v-for="p in lulusan" :key="p" class="flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-secondary-fixed-dim mt-0.5 shrink-0">check</span>
                <span class="leading-snug">{{ p }}</span>
              </li>
            </ul>
            <div class="mt-space-lg pt-space-sm border-t border-on-primary/20 flex items-center justify-between gap-2 relative z-10">
              <span class="font-label-sm text-label-sm uppercase tracking-wider opacity-80">Tahun Ajaran</span>
              <span class="font-headline-sm text-headline-sm font-bold text-right">2026 / 2027</span>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, ref } from 'vue'
import Reveal from '../Components/Reveal.vue'

const activeTab = ref('formal')

const integrasi = [
  { label: 'Dayah Salafiyah', icon: 'mosque' },
  { label: 'Pendidikan Formal', icon: 'school' },
  { label: 'Bahasa & Tahfidz', icon: 'translate' },
  { label: 'Pembentukan Karakter', icon: 'favorite' },
]

const curriculumTabs = [
  {
    id: 'formal',
    no: '1',
    title: 'Kurikulum Formal Pendidikan Nasional',
    icon: 'school',
    heading: 'Kurikulum Formal Pendidikan Nasional',
    subheading: 'SMP & SMA Madani Al-Aziziyah',
    desc: 'Di bidang pendidikan formal, unit SMP Madani Al-Aziziyah dan SMA Madani Al-Aziziyah mengikuti panduan kurikulum nasional dari kementerian.',
    points: [
      'Materi standar nasional mencakup Sains, Matematika, Sosial, dan Bahasa Indonesia.',
      'Pembelajaran diperkaya dengan penguatan spiritualitas dan adaptasi teknologi dalam metode pembelajaran.',
    ],
    units: [
      {
        code: 'SMP',
        name: 'SMP Madani Al-Aziziyah',
        akreditasi: 'B',
        href: 'https://sekolah.data.kemendikdasmen.go.id/profil-sekolah/BEA3C7A0-A217-46D4-BD87-191D72FD8522',
        linkLabel: 'Profil SMP Madani Al-Aziziyah',
      },
      {
        code: 'SMA',
        name: 'SMA Madani Al-Aziziyah',
        akreditasi: 'B',
        href: 'https://sekolah.data.kemendikdasmen.go.id/profil-sekolah/9050593B-E3B1-4C1A-9970-7159A5479B1D',
        linkLabel: 'Profil SMA Madani Al-Aziziyah',
      },
    ],
  },
  {
    id: 'pesantren',
    no: '2',
    title: 'Kurikulum Kepesantrenan & Kitab Kuning',
    icon: 'menu_book',
    heading: 'Kurikulum Kepesantrenan Dayah Salafiyah & Kitab Kuning',
    desc: 'Merupakan pilar utama kepesantrenan yang wajib diikuti oleh seluruh santri mukim.',
    kitabCards: [
      { icon: 'balance', label: "Fiqih Syafi'iyyah", desc: 'Menjadi fondasi dasar pengajaran ibadah dan pemahaman keislaman para santri.' },
      { icon: 'volunteer_activism', label: "Aqidah Ahlussunnah Waljama'ah", desc: "Menanamkan dasar aqidah Ahlussunnah Waljama'ah dalam pembinaan keagamaan santri." },
      { icon: 'auto_stories', label: 'Kutubut Turats', desc: 'Pembelajaran kitab-kitab klasik yang mencakup Nahwu, Sharaf, Fiqih, Ushul Fiqih, dan Tafsir.' },
    ],
  },
  {
    id: 'unggulan',
    no: '3',
    title: 'Program Unggulan & Pembentukan Karakter',
    icon: 'groups',
    heading: 'Program Unggulan & Keterampilan Bahasa',
    programCards: [
      {
        icon: 'translate',
        label: 'Pengembangan Bahasa Asing',
        desc: "Pengembangan bahasa Arab dan Inggris melalui kegiatan intensif seperti Dauroh Lughowiyah serta pembiasaan berbahasa Arab Fushah dan Inggris di lingkungan tahfizh maupun asrama.",
      },
      {
        icon: 'auto_stories',
        label: "Tahfidz & Tahsin Al-Qur'an",
        desc: "Program setoran hafalan dan perbaikan bacaan Al-Qur'an yang terintegrasi dengan jadwal harian santri.",
      },
    ],
    karakter: [
      { icon: 'dark_mode', label: 'Halaqah malam' },
      { icon: 'record_voice_over', label: 'Muhadharah / latihan pidato' },
      { icon: 'brush', label: 'Seni kaligrafi Islam' },
      { icon: 'sports_soccer', label: 'Olahraga' },
    ],
  },
]

const currentTab = computed(() => curriculumTabs.find((t) => t.id === activeTab.value))

const lulusan = [
  'Menguasai dasar ilmu keislaman dan tradisi keilmuan pesantren.',
  'Memiliki kompetensi akademik sesuai pendidikan formal.',
  'Memiliki kemampuan bahasa Arab dan Inggris.',
  'Memiliki kemandirian, kepemimpinan, dan keterampilan sosial.',
]
</script>

<style scoped>
.tab-enter-active,
.tab-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}

.tab-enter-from {
  opacity: 0;
  transform: translateY(12px);
}

.tab-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

@media (prefers-reduced-motion: reduce) {
  .tab-enter-active,
  .tab-leave-active {
    transition: none;
  }

  .tab-enter-from,
  .tab-leave-to {
    opacity: 1;
    transform: none;
  }
}
</style>