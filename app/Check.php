<?php

namespace Panacea;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'check_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_number',
        'code',
        'source',
        'remarks',
        'location'
    ];

    public function code()
    {
        return $this->belongsTo('Panacea\Code');
    }
}
