<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Json;

class viewEvenement extends Model
{
    use HasFactory;

    protected $table = 'listevenements';

    protected $casts = [
        'auteur' => Json::class,
        'adresse'=> Json::class,
        'categories'=>Json::class,
        'recurrence'=>Json::class
    ];


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
