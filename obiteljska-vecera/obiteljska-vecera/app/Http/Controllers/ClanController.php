<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;

class ClanController extends Controller
{
    public function index()
    {
        $clanovi = Clan::all();
        return view('index', ['clanovi' => $clanovi]);
    }

    public function create()
    {
        if (Clan::count() >= 4) {
            return redirect('/resetiraj-glasanje')->with('error', 'Maksimalan broj članova je postignut.');
        }

        return view('create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ime' => 'required|max:255',
        ]);

        Clan::create($validated);

        if (Clan::count() >= 4) {
            return redirect('/resetiraj-glasanje')->with('success', 'Svi članovi su uspješno dodani!');
        }

        return redirect('/clanovi')->with('success', 'Član uspješno dodan!');
    }
}
