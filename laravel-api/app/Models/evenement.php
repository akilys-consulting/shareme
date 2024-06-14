<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evenement extends Model
{
    use HasFactory;

    
    protected $table = 'evenements';

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
