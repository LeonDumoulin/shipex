<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $attributes = [
        'state' => 0,
    ];
    protected $fillable = [
        'name','from_address','to_address','user_id','driver_id','client_phone','size','client_name','state','weight'
    ];

    public function driver()
    {
        return $this->belongsTo('App\Models\Driver');
    }

}