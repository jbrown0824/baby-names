<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Votes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_id', 'voter_id', 'note', 'num_votes_used'
    ];

    public function voter() {
        return $this->belongsTo(Voters::class);
    }
}
