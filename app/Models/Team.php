<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{


    protected $fillable = [
        'id',
        'competition_id',
        'name',
        'shortName',
        'crest',
        'clubColors',
        'founded'
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

}
