<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    //Country Schema
    protected $fillable = [
        'id',
        'name',
        'sortname',
        'phonecode',
    ];
    public function country(){
        return $this->hasMany(State::class);
   }
}