<?php

namespace Panacea;

use Illuminate\Database\Eloquent\Model;

class SMS_records extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'SMS_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'service_id',
        'message',
        'mobile_no',
        'completed'
    ];
}
