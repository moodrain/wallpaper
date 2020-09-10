<?php

namespace App\Models;

class Image extends Model
{
    public static $searchRule = [
        'id' => '=',
        'userId/d' => '=',
        'homeId/d' => '=',
    ];

    public static $sortRule = ['id', 'size', 'createdAt', 'updatedAt'];

    protected $appends = ['path', 'url', 'thumb200', 'thumb400', 'thumb800'];

    protected $hidden = ['pivot'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function home()
    {
        return $this->belongsToMany(Home::class);
    }

    public function getUrlAttribute()
    {
        return config('aliyun.oss.cdn') . '/' . $this->path;
    }

    public function getPathAttribute()
    {
        return 'wallpaper-app/' . $this->md5;
    }

    public function getThumb200Attribute()
    {
        return $this->url . '?x-oss-process=image/resize,h_200';
    }

    public function getThumb400Attribute()
    {
        return $this->url . '?x-oss-process=image/resize,h_400';
    }

    public function getThumb800Attribute()
    {
        return $this->url . '?x-oss-process=image/resize,h_800';
    }

    public function getTagIdsAttribute()
    {
        return $this->tags->pluck('id');
    }
}
