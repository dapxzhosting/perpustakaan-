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
            'kategoriList' => Kategori::orderBy('nama')->get(),
            'kategoriTerpilih' => $kategoriTerpilih,
            'keyword' => $keyword,
            'bukuTerbaru' => Buku::with('kategori')->latest('id')->take(8)->get(),
        ]);
    }

    public function show(Buku $buku): View
    {
        $buku->load('kategori');

        return view('katalog.buku', [
            'buku' => $buku,
        ]);
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
