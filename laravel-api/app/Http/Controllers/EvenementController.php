<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAll()
    {
        $evenements = Evenement::all();
        return response()->json([
            'status' => true,
            'evenements' => $evenements
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required',
        ]);

        Evenements:create($request->all());

        return redirect()->route('Evenements.index')
                         ->with('success', 'Evenement créé sans erreur');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return view('Evenements.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
