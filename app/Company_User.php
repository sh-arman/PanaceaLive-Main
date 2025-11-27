<?php

namespace Panacea;

use Illuminate\Database\Eloquent\Model;

class Company_User extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'code',
    ];

    protected $hidden = ['password', 'remember_token'];

}
