<?php

namespace Panacea;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'company_admin_id',
        'med_id',
        'template_message',
        'flag'
    ];
}
