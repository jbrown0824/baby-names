<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voters extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'votes_available', 'refills_on'
    ];

    public function votes() {
        return $this->hasMany(Votes::class);
    }
}
