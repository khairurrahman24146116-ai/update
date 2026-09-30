@extends('layouts.app')

@section('title', 'Portal Akademik')

@section('content')
<a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-[#1E3A8A] mb-6 hover:underline">
    ← Kembali ke Beranda
</a>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach(['admin' => 'Admin Dashboard', 'guru' => 'Guru Portal', 'bendahara' => 'Keuangan', 'wali_murid' => 'Wali Murid'] as $role => $label)
    <a href="/app/{{ $role == 'wali_murid' ? 'wali-murid' : $role }}" class="p-6 bg-white border-neu shadow-neu hover:shadow-neu-hover transition-[box-shadow,transform] rounded-lg">
        <h2 class="text-lg font-bold text-[#1E3A8A] mb-2">{{ $label }}</h2>
        <p class="text-sm text-[#444651]">Masuk ke sistem sebagai {{ $label }}</p>
    </a>
    @endforeach
</div>
@endsection
