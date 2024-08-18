<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;
use App\Models\Jelo;
use App\Models\Prijedlog;

class PrijedlogController extends Controller
{
    public function glasanje(Request $request)
    {
        $jela = Jelo::all();
        $clanovi = Clan::all();
        $trenutni_clan_id = $request->session()->get('trenutni_clan_id', $clanovi->first()->id);

        return view('glasanje', [
            'jela' => $jela,
            'clanovi' => $clanovi,
            'trenutni_clan_id' => $trenutni_clan_id,
        ]);
    }

    public function glasaj(Request $request)
    {
        $validated = $request->validate([
            'clan_id' => 'required|exists:clans,id',
            'jelo_id' => 'required|exists:jelos,id',
        ]);

        Prijedlog::create($validated);

        // Prebacivanje na sljedećeg člana
        $clanovi = Clan::all();
        $currentClanIndex = $clanovi->pluck('id')->search($request->clan_id);
        $nextClanIndex = ($currentClanIndex + 1) % $clanovi->count();

        // Ako su svi članovi glasali, preusmjerava na rezultat
        if ($nextClanIndex == 0) {
            return redirect('/rezultat');
        }

        $request->session()->put('trenutni_clan_id', $clanovi[$nextClanIndex]->id);

        return redirect('/glasanje')->with('success', 'Glas uspješno dodan!');
    }

    public function rezultat()
    {
        $rezultati = Jelo::withCount('prijedlozi')->orderBy('prijedlozi_count', 'desc')->get();

        return view('rezultat', ['rezultati' => $rezultati]);
    }

    public function resetirajGlasanje()
    {
        // Briše sve prethodne prijedloge
        Prijedlog::truncate();

        // Resetiranje sesije za trenutnog člana
        session()->forget('trenutni_clan_id');

        return redirect('/glasanje')->with('success', 'Glasanje je resetirano. Možete započeti ponovno.');
    }
}
