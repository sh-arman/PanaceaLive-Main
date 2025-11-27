<?php

namespace Panacea;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name',
        'company_address',
        'contact_name',
        'contact_number',
        'contact_email',
        'status',
        'created_by',
        'updated_by',
    ];

    public function medicines()
    {
        return $this->hasMany('Panacea\Medicine');
    }

}
