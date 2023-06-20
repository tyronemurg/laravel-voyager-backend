<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    //use HasFactory;

    protected $fillable=['name', 'slug','main_image', 'description'];

    public function images() {

        return $this->hasMany('App\Models\Image');
    }

    public function scopeActive($query) {
        
    return $query->where('active', 1);
}
}
