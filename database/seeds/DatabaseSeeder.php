<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        App\Models\User::query()->create([
            'name' => 'muyu',
            'email' => 'moerain@qq.com',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);
        \App\Models\Home::query()->create([
            'userId' => 1,
            'name' => '默认',
            'token' => (new \App\Services\HomeService())->genToken(1, 1),
        ]);
    }
}
