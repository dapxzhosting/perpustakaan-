<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(): View
    {
        return view('admin.buku.index', [
            'buku' => Buku::with('kategori')->orderBy('judul')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.buku.create', [
            'buku' => new Buku,
            'kategori' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('sampul')) {
            $data['sampul'] = $request->file('sampul')->store('sampul-buku', 'public');
        }

        Buku::create($data);

        return redirect()->route('admin.buku.index')->with('sukses', 'Buku berhasil ditambahkan.');
    }

    public function show(Buku $buku): View
    {
        return view('admin.buku.show', [
            'buku' => $buku->load('kategori'),
        ]);
    }

    public function edit(Buku $buku): View
    {
        return view('admin.buku.edit', [
            'buku' => $buku,
            'kategori' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Buku $buku): RedirectResponse
    {
        $data = $this->validatedData($request, $buku);

        if ($request->hasFile('sampul')) {
            $sampulLama = $buku->sampul;
            $data['sampul'] = $request->file('sampul')->store('sampul-buku', 'public');
            $buku->update($data);

            if ($sampulLama) {
                Storage::disk('public')->delete($sampulLama);
            }
        } else {
            $buku->update($data);
        }

        return redirect()->route('admin.buku.index')->with('sukses', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku): RedirectResponse
    {
        if ($buku->peminjaman()->exists()) {
            return back()->with('galat', 'Buku tidak dapat dihapus karena sudah memiliki riwayat peminjaman.');
        }

        $sampul = $buku->sampul;
        $buku->delete();

        if ($sampul) {
            Storage::disk('public')->delete($sampul);
        }

        return redirect()->route('admin.buku.index')->with('sukses', 'Buku berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Buku $buku = null): array
    {
        $uniqueKode = Rule::unique('buku', 'kode_buku');

        if ($buku) {
            $uniqueKode->ignore($buku->id);
        }

        return $request->validate([
            'kategori_id' => ['required', 'exists:kategori,id'],
            'kode_buku' => ['required', 'string', 'max:20', $uniqueKode],
            'judul' => ['required', 'string', 'max:200'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'digits:4', 'integer', 'lte:'.date('Y')],
            'stok' => ['required', 'integer', 'min:0'],
            'sampul' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak tersedia.',
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.max' => 'Kode buku maksimal 20 karakter.',
            'kode_buku.unique' => 'Kode buku sudah digunakan.',
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 200 karakter.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.digits' => 'Tahun terbit harus terdiri dari 4 angka.',
            'tahun_terbit.lte' => 'Tahun terbit tidak boleh melebihi tahun berjalan.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa bilangan bulat.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'sampul.image' => 'Sampul harus berupa file gambar.',
            'sampul.mimes' => 'Sampul hanya boleh berformat JPG atau PNG.',
            'sampul.max' => 'Ukuran sampul maksimal 2 MB.',
        ]);
    }
}
