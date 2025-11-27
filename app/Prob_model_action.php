<?php

namespace Panacea;

use Illuminate\Database\Eloquent\Model;

class Prob_model_action extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prob_model_action';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prob_model_id',
        'progress_number',
        'update_details',
    ];
}
