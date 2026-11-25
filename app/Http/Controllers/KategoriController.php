<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = mb_substr(trim((string) $request->query('q', '')), 0, 100);

        $kategori = Kategori::withCount('buku')
            ->when($keyword !== '', fn ($query) => $query->where('nama', 'like', "%{$keyword}%"))
            ->orderBy('nama')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('admin.kategori.index', compact('kategori', 'keyword'));
    }

    public function create(): View
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(
            ['nama' => ['required', 'string', 'max:100', 'unique:kategori,nama']],
            [
                'nama.required' => 'Nama kategori wajib diisi.',
                'nama.max' => 'Nama kategori maksimal 100 karakter.',
                'nama.unique' => 'Nama kategori sudah digunakan.',
            ]
        );

        Kategori::create($data);

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori): View
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $data = $request->validate(
            ['nama' => ['required', 'string', 'max:100', Rule::unique('kategori', 'nama')->ignore($kategori->id)]],
            [
                'nama.required' => 'Nama kategori wajib diisi.',
                'nama.max' => 'Nama kategori maksimal 100 karakter.',
                'nama.unique' => 'Nama kategori sudah digunakan.',
            ]
        );

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        if ($kategori->buku()->exists()) {
            return back()->with('galat', 'Kategori tidak dapat dihapus karena masih memiliki buku.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil dihapus.');
    }
}
