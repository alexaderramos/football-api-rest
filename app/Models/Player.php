<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'id',
        'team_id',
        'name',
        'position',
        'shirtNumber'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
