<?php

namespace App\Http\Controllers;

use App\Models\Clan;
use Illuminate\Http\Request;
use App\Models\Jelo;

class ClanController extends Controller
{
    /**
     * Display a listing of the members.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve all members from the database
        $clanovi = Clan::all();
        $jela = Jelo::all();
        // Pass the members to the index view
        return view('index', compact('clanovi', 'jela'));
    }

    /**
     * Show the form for creating a new member.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Return the create view
        return view('create_clan');
    }

    /**
     * Store a newly created member in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'ime' => 'required|string|max:255',
        ]);

        // Create a new member in the database
        Clan::create($validated);

        // Redirect to the home page with a success message
        return redirect()->route('home')->with('success', 'Član je uspješno dodan!');
    }

    /**
     * Show the form for editing the specified member.
     *
     * @param  \App\Models\Clan  $clan
     * @return \Illuminate\View\View
     */
    public function edit(Clan $clan)
    {
        // Return the edit view with the specified member
        return view('edit_clan', compact('clan'));
    }

    /**
     * Update the specified member in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clan  $clan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Clan $clan)
    {
        // Validate the input data
        $validated = $request->validate([
            'ime' => 'required|string|max:255',
        ]);

        // Update the member in the database
        $clan->update($validated);

        // Redirect to the home page with a success message
        return redirect()->route('home')->with('success', 'Član je uspješno ažuriran!');
    }
}
