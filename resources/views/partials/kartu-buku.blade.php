@php
    $warna = [
        'from-brand-500 to-violet-500',
        'from-sky-500 to-brand-600',
        'from-emerald-500 to-teal-600',
        'from-amber-500 to-orange-500',
        'from-rose-500 to-pink-600',
    ];
    $gradasi = $warna[$item->id % count($warna)];
    $kompak = $kompak ?? false;
@endphp
<a href="{{ route('buku.show', $item) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-200 hover:-translate-y-1 hover:border-brand-300 hover:shadow-lg hover:shadow-brand-600/10 {{ $kompak ? 'w-44 shrink-0 snap-start sm:w-48' : '' }}">
    <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
        @if ($item->sampul)
            <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105" src="{{ asset('storage/'.$item->sampul) }}" alt="Sampul {{ $item->judul }}" loading="lazy">
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br {{ $gradasi }} text-6xl font-extrabold text-white/90" aria-hidden="true">
                {{ mb_strtoupper(mb_substr($item->judul, 0, 1)) }}
            </div>
        @endif
        <span class="absolute left-2.5 top-2.5 inline-flex items-center gap-1 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-slate-700 shadow-sm backdrop-blur">
            <i class="bi bi-tag text-brand-600"></i>{{ $item->kategori->nama }}
        </span>
    </div>
    <div class="flex flex-1 flex-col gap-1 p-3.5">
        <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-slate-900">{{ $item->judul }}</h3>
        <p class="truncate text-xs text-slate-500">{{ $item->penulis }}</p>
        @unless ($kompak)
            <div class="mt-auto pt-2.5">
                @if ($item->stok > 0)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Tersedia {{ $item->stok }}</span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700"><span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>Sedang dipinjam semua</span>
                @endif
            </div>
        @endunless
    </div>
</a>
