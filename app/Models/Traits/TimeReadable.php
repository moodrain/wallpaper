<?php

namespace App\Models\Traits;

use Illuminate\Support\Carbon;

trait TimeReadable
{
    public function getCreatedAtReadableAttribute()
    {
        return isset($this->attributes['created_at']) ? Carbon::create($this->attributes['created_at'])->diffForHumans() : '';
    }

    public function getUpdatedAtReadableAttribute()
    {
        return isset($this->attributes['updated_at']) ? Carbon::create($this->attributes['updated_at'])->diffForHumans() : '';
    }
}