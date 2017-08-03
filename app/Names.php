<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Names extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'nickname', 'votes', 'rank_last_week', 'last_rank_update'
    ];
}
