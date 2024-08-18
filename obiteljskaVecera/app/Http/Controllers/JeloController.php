<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jelo;

class JeloController extends Controller
{
    public function create()
    {
        return view('create_jelo');
    }

    public function store(Request $request)
    {
        // Validate the dish name
        $validated = $request->validate([
            'naziv' => 'required|max:255',
        ]);

        // Create a new dish
        Jelo::create($validated);

        return redirect('/')->with('success', 'Novo jelo je uspješno dodano!');
    }

    public function edit(Jelo $jelo)
    {
        return view('edit_jelo', compact('jelo'));
    }

    public function update(Request $request, Jelo $jelo)
    {
        // Validate the dish name
        $validated = $request->validate([
            'naziv' => 'required|max:255',
        ]);

        // Update the dish
        $jelo->update($validated);

        return redirect('/')->with('success', 'Naziv jela je uspješno ažuriran!');
    }
}
