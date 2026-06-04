<?php

namespace Database\Seeders;

use App\Models\Flyer;
use Illuminate\Database\Seeder;

class FlyerSeeder extends Seeder
{
    public function run(): void
    {
        Flyer::create([
            'title'       => '2026 五月 DM',
            'period'      => '2026 年 5 月',
            'description' => '本月主題：「母親節，給毛孩最特別的愛。」收錄全館優惠、限定套餐、門市活動資訊，歡迎下載或分享給有需要的朋友。',
            'image_path'  => null,
            'sort_order'  => 1,
        ]);
    }
}
