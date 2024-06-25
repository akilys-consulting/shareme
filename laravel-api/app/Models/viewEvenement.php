<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class viewEvenement extends Model
{
    use HasFactory;

    protected $table = 'listevenements';

    protected $fillable = [
        'titre',
        'description',
        'recurrence',
        'adresse',
        'categories',
        'auteur',
        'event_id',
        'actif',
        'url',
        'image',
        'nb_personnes',
    ];
}
