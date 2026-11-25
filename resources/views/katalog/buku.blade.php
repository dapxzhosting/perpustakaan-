@extends('layouts.app')

@section('judul', $buku->judul)

@section('konten')
    <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500" aria-label="breadcrumb">
        <a class="inline-flex items-center gap-1.5 transition hover:text-brand-600" href="{{ route('katalog') }}"><i class="bi bi-house-door"></i>Katalog</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="font-medium text-slate-900" aria-current="page">Detail buku</span>
    </nav>

    <article class="grid overflow-hidden rounded-3xl border border-slate-200 bg-white md:grid-cols-[300px_1fr]">
        <div class="bg-slate-100">
            @if ($buku->sampul)
                <img class="h-full w-full object-cover md:min-h-[420px]" src="{{ asset('storage/'.$buku->sampul) }}" alt="Sampul {{ $buku->judul }}">
            @else
                <div class="flex aspect-[4/3] h-full w-full items-center justify-center bg-gradient-to-br from-brand-500 to-violet-500 text-8xl font-extrabold text-white/90 md:aspect-auto md:min-h-[420px]" aria-hidden="true">
                    {{ mb_strtoupper(mb_substr($buku->judul, 0, 1)) }}
                </div>
            @endif
        </div>

        <div class="p-6 sm:p-10">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700"><i class="bi bi-tag"></i>{{ $buku->kategori->nama }}</span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"><i class="bi bi-upc"></i>{{ $buku->kode_buku }}</span>
            </div>

            <h1 class="mt-4 text-2xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-3xl">{{ $buku->judul }}</h1>
            <p class="mt-1 inline-flex items-center gap-2 text-slate-500"><i class="bi bi-person"></i>{{ $buku->penulis }}</p>

            <dl class="mt-6 divide-y divide-slate-100 border-y border-slate-100">
                <div class="flex items-center justify-between gap-4 py-3.5">
                    <dt class="inline-flex items-center gap-3 text-sm text-slate-500"><i class="bi bi-building text-base text-brand-600"></i>Penerbit</dt>
                    <dd class="text-right text-sm font-medium text-slate-900">{{ $buku->penerbit ?: '-' }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-3.5">
                    <dt class="inline-flex items-center gap-3 text-sm text-slate-500"><i class="bi bi-calendar-event text-base text-brand-600"></i>Tahun terbit</dt>
                    <dd class="text-right text-sm font-medium text-slate-900">{{ $buku->tahun_terbit }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-3.5">
                    <dt class="inline-flex items-center gap-3 text-sm text-slate-500"><i class="bi bi-tags text-base text-brand-600"></i>Kategori</dt>
                    <dd class="text-right text-sm font-medium text-slate-900">{{ $buku->kategori->nama }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-3.5">
                    <dt class="inline-flex items-center gap-3 text-sm text-slate-500"><i class="bi bi-stack text-base text-brand-600"></i>Stok tersedia</dt>
                    <dd class="text-right text-sm font-medium text-slate-900">{{ $buku->stok > 0 ? $buku->stok.' eksemplar' : 'Sedang dipinjam semua' }}</dd>
                </div>
            </dl>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                @if ($buku->stok > 0)
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700"><i class="bi bi-check-circle"></i>Tersedia untuk dipinjam</span>
                @else
                    <span class="inline-flex items-center gap-2 rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700"><i class="bi bi-x-circle"></i>Sedang dipinjam semua</span>
                @endif
                <a class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600" href="{{ route('katalog') }}">
                    <i class="bi bi-arrow-left"></i>Kembali ke katalog
                </a>
            </div>
        </div>
    </article>
@endsection
