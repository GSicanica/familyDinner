<?php

namespace App\Http\Controllers;

use App\Models\Prijedlog;
use App\Models\Jelo;
use App\Models\Clan;
use Illuminate\Http\Request;

class PrijedlogController extends Controller
{
    public function glasanje(Request $request)
    {
        // Get all dishes and members
        $jela = Jelo::all();
        $clanovi = Clan::all();

        // Get the current member voting
        $trenutni_clan_id = $request->session()->get('trenutni_clan_id', $clanovi->first()->id);

        return view('glasanje', [
            'jela' => $jela,
            'clanovi' => $clanovi,
            'trenutni_clan_id' => $trenutni_clan_id,
        ]);
    }

    public function glasaj(Request $request)
    {
        // Validate the selected dish
        $validated = $request->validate([
            'jelo_id' => 'required|exists:jelos,id',
        ]);

        // Get the current member voting
        $trenutni_clan_id = $request->session()->get('trenutni_clan_id');
        $clanovi = Clan::all();
        $currentClan = $clanovi->firstWhere('id', $trenutni_clan_id);

        // Record the vote
        Prijedlog::create([
            'clan_id' => $trenutni_clan_id,
            'jelo_id' => $validated['jelo_id'],
        ]);

        // Move to the next member
        $nextClan = $clanovi->skipWhile(function ($clan) use ($trenutni_clan_id) {
            return $clan->id != $trenutni_clan_id;
        })->skip(1)->first();

        if ($nextClan) {
            // Save the next member to vote in session
            $request->session()->put('trenutni_clan_id', $nextClan->id);
            return redirect()->route('prijedlozi.glasanje');
        } else {
            // If all members have voted, show the results
            return redirect()->route('prijedlozi.rezultat');
        }
    }

    public function rezultat()
    {
        // Calculate the voting results
        $rezultati = Prijedlog::select('jelo_id', \DB::raw('count(*) as brojGlasova'))
            ->groupBy('jelo_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Jelo::find($item->jelo_id)->naziv => $item->brojGlasova];
            });

        return view('rezultat', ['rezultati' => $rezultati]);
    }

    public function resetiraj(Request $request)
    {
        // Reset all votes
        Prijedlog::truncate();

        // Reset the voting session
        $request->session()->forget('trenutni_clan_id');

        return redirect('/')->with('success', 'Glasanje je resetirano!');
    }
}
