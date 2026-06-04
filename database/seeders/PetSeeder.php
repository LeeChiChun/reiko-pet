<?php

namespace Database\Seeders;

use App\Models\Pet;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $pets = [
            // 王小明的寵物（user_id=4）
            [
                'user_id' => 4,
                'name' => '球球',
                'breed' => '柴犬',
                'gender' => 'male',
                'age' => 3,
                'type' => 'dog',
                'weight' => 10.5,
                'health_note' => '對某些洗毛精過敏，請使用低敏產品。',
                'notes' => '個性活潑，不怕陌生人。',
            ],
            [
                'user_id' => 4,
                'name' => '咪咪',
                'breed' => '英國短毛貓',
                'gender' => 'female',
                'age' => 2,
                'type' => 'cat',
                'weight' => 4.2,
                'health_note' => null,
                'notes' => '偶爾會緊張，請輕柔對待。',
            ],
            // 張小華的寵物（user_id=5）
            [
                'user_id' => 5,
                'name' => '花花',
                'breed' => '貴賓犬',
                'gender' => 'female',
                'age' => 5,
                'type' => 'dog',
                'weight' => 7.8,
                'health_note' => null,
                'notes' => '習慣做美容，很乖。',
            ],
        ];

        foreach ($pets as $pet) {
            Pet::create($pet);
        }
    }
}
