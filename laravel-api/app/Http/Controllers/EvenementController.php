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

        $evenement = Evenement::create($request->all());

        return response()->json($evenement, 201);
    }

    /**
     * Display the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $evenement = Evenement::updateOrCreate(
            [
                'id' => $request->input('data.id')==-1?null:$request->input('data.id')
            ],
            [   'titre' => $request->input('data.titre'), 
                'description' => $request->input('data.description'),
                'recurrence' => $request->input('data.recurrence'),
                'adresse' => $request->input('data.adresse'),
                'categories' => $request->input('data.categories'),
                'url' => $request->input('data.url'),
                'actif' => $request->input('data.actif'),
                'auteur' => $request->input('data.auteur'),
                'event_id' => $request->input('data.event_id'),
                'image' => $request->input('data.image'),
                'nb_personnes' => $request->input('nb_personnes'),            ]

        );
        return response()->json($evenement, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
