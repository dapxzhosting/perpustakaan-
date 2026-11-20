<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KatalogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'integer', 'exists:kategori,id'],
        ]);
        $keyword = trim($filters['q'] ?? '');
        $kategoriTerpilih = (int) ($filters['kategori'] ?? 0);

        $buku = $this->applySearch(Buku::with('kategori'), $keyword)
            ->when($kategoriTerpilih, fn (Builder $query) => $query->where('kategori_id', $kategoriTerpilih))
            ->orderBy('judul')
            ->paginate(8)
            ->withQueryString();

        return view('welcome', [
            'buku' => $buku,
            'kategoriList' => Kategori::withCount('buku')->orderBy('nama')->get(),
            'kategoriTerpilih' => $kategoriTerpilih,
            'keyword' => $keyword,
        ]);
    }

    public function show(Buku $buku): View
    {
        $buku->load('kategori');

        return view('katalog.buku', [
            'buku' => $buku,
            'bukuTerkait' => Buku::with('kategori')
                ->where('kategori_id', $buku->kategori_id)
                ->whereKeyNot($buku->id)
                ->orderBy('judul')
                ->limit(4)
                ->get(),
        ]);
    }

    public function showKategori(Request $request, Kategori $kategori): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $keyword = trim($filters['q'] ?? '');
        $kategori->loadCount('buku');
        $buku = $this->applySearch($kategori->buku()->with('kategori'), $keyword)
            ->orderBy('judul')
            ->paginate(8)
            ->withQueryString();

        return view('katalog.kategori', compact('kategori', 'buku', 'keyword'));
    }

    private function applySearch(Builder $query, string $keyword): Builder
    {
        if ($keyword !== '') {
            $query->where(function (Builder $query) use ($keyword) {
                $query->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('penulis', 'like', "%{$keyword}%")
                    ->orWhere('kode_buku', 'like', "%{$keyword}%");
            });
        }

        return $query;
    }
}
