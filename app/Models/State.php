<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    //State Schema
    protected $fillable = [
        'id',
        'name',
        'country_id',
    ];


    public function state(){
        return $this->hasMany(City::class);
   }
}
