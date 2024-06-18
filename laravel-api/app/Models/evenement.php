<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Json;

class evenement extends Model
{
    use HasFactory;

    
    protected $table = 'evenements';

    protected $casts = [
        'auteur' => Json::class,
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
        'actif'
    ];
}
