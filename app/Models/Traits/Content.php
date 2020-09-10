<?php

namespace App\Models\Traits;

trait Content
{
    public function getContentShortAttribute()
    {
        if (empty($this->attributes['content'])) {
            return '';
        }
        $return = $content = \Parsedown::instance()->parse($this->attributes['content']);
        mb_strlen($content) > 200 && $return = mb_substr($content, 0, 200) . '...';
        $return = str_replace("\n", ' ', strip_tags($return));
        return $return;
    }

    public function getContentBase64Attribute()
    {
        return isset($this->attributes['content']) ? base64_encode($this->attributes['content']) : '';
    }
}