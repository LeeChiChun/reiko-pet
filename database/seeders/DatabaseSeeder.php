<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            StoreSeeder::class,
            ServiceSeeder::class,
            AddonServiceSeeder::class,
            PetSeeder::class,
            ArticleSeeder::class,
            ProductSeeder::class,
            AppointmentSeeder::class,
            AnnouncementSeeder::class,
            PromotionSeeder::class,
            FlyerSeeder::class,
        ]);
    }
}
