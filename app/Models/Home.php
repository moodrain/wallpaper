<?php

namespace App\Models;

class Home extends Model
{
    public static $searchRule = [
        'id' => '=',
        'name' => 'like',
        'userId/d' => '=',
    ];

    public static $sortRule = ['id', 'name', 'createdAt', 'updatedAt'];

    protected $hidden = ['token'];

    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    public function getImageIdsAttribute()
    {
        return $this->images->pluck('id');
    }
}