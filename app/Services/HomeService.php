<?php

namespace App\Services;

use App\Models\Home;

class HomeService
{
    public function genToken($userId, $homeId, $rand = '')
    {
        $token = md5(time() . $userId . $homeId . $rand);
        if (Home::query()->where('token', $token)->exists()) {
            return $this->genToken($userId, $homeId, mt_rand(1000, 9999));
        }
        return $token;
    }
}