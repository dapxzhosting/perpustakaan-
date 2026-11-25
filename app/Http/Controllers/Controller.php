<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /** Baris per halaman dari ?per_page=, hanya nilai yang ada di pilihan tabel. */
    protected function perPage(Request $request): int
    {
        $n = $request->integer('per_page', 10);

        return in_array($n, [10, 20, 50, 100], true) ? $n : 10;
    }
}
