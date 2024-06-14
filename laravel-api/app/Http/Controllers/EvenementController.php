<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\viewEvenement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EvenementController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function getAll(Request $request)
    {

        $filtre = $request->input('filtre');

        $query = viewEvenement::query();

        if ($filtre['search']) {
            $query->whereRaw('UPPER(titre) LIKE ?', ['%' . strtoupper($filtre['search']) . '%']);
        }
        if ($filtre['date']) {
            $date = Carbon::createFromFormat('d/m/Y', $filtre['date'])->startOfDay();
            $query->where('date_debut','>', $date);
        }

        if ($filtre['cat']){

            $query->whereJsonContains('categories', $filtre['cat'][0]);
    
            for($i = 1; $i < count($filtre['cat']); $i++) {
               $query->orWhereJsonContains('categories', $filtre['cat'][$i]);      
            }

        }
        $evenements = $query->get();

        return response()->json([
            'status' => true,
            'evenements' => $evenements
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required',
        ]);

        $evenement = Evenements::create($request->all());

        return response()->json($evenement, 201);
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
