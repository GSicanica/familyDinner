<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;
use App\Models\Jelo;
use App\Models\Prijedlog;

class PrijedlogController extends Controller
{
    public function glasanje()
    {
        $jela = Jelo::all();
        $clanovi = Clan::all();

        return view('glasanje', ['jela' => $jela, 'clanovi' => $clanovi]);
    }

    public function glasaj(Request $request)
    {
        $validated = $request->validate([
            'clan_id' => 'required|exists:clans,id',
            'jelo_id' => 'required|exists:jelos,id',
        ]);

        Prijedlog::create($validated);

        if (Prijedlog::count() >= Clan::count()) {
            return redirect('/rezultat');
        }

        return redirect('/glasanje')->with('success', 'Glas uspješno dodan!');
    }

    public function rezultat()
    {
        $rezultati = Jelo::withCount('prijedlozi')->orderBy('prijedlozi_count', 'desc')->get();

        return view('rezultat', ['rezultati' => $rezultati]);
    }
}
