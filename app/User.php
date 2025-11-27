<?php

namespace Panacea;

use Cartalyst\Sentinel\Users\EloquentUser;

class User extends EloquentUser
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Array of login column names.
     *
     * @var array
     */
    protected $loginNames = ['phone_number'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'phone_number', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public static function IsValidPhoneNo($phoneNo)
    {
        if ( is_numeric($phoneNo) )
        {
            if (strlen($phoneNo) > 11)
            {
                $phoneNo = substr($phoneNo , -11);
            }
            else if (strlen($phoneNo) < 11)
            {
                return false;
            }
            $startDigits = substr($phoneNo , 0 , 3);
            if ($startDigits=='017' || $startDigits=='016' || $startDigits=='015' || $startDigits=='019' || $startDigits=='018' || $startDigits=='013' || $startDigits=='014')
            {
                return $phoneNo;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}
