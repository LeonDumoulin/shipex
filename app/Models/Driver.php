<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Driver extends Authenticatable 
{
    use HasApiTokens;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password','phone','username','car','name','user_rate','count_rate','image'
    ];
    protected $appends = array('rate');

    protected $table = 'drivers';
    public $timestamps = true;

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function getRateAttribute(){
        return ($this->count_rate == 0) ? 0 :
        ( $this->user_rate / ($this->count_rate*5) );
    }

}