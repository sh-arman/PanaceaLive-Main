<?php

namespace Panacea;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'company_admin_id',
        'amount',
        'language',
        'campaign_name',
        'filename',
        'product',
        'message',
        'operator',
        'execution time',
        'status',
        'case'
    ];

}
