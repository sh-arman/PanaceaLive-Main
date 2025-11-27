<?php

namespace Panacea;

use Illuminate\Database\Eloquent\Model;

class Prob_model extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prob_model';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'expected',
        'actual',
        'first',
        'second',
        'third',
        'fourth',
        'steps'
    ];
}
