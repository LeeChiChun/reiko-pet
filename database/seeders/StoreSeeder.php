<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            [
                'name'           => '燕巢旗艦店',
                'address'        => '824 高雄市燕巢區深水里深中路 62 號',
                'phone'          => '07-601-8899',
                'business_hours' => '週二至週日 10:00–19:00（週一公休）',
                'description'    => '禮寵的起點，也是創辦人 Justin 的初心所在。旗艦店坐落於燕巢深水里，空間寬敞明亮，備有獨立美容包廂、透明觀察窗與舒適等候區。這裡不只是美容院，是每位毛孩與主人都樂於造訪的放鬆場所。',
                'is_active'      => true,
            ],
            [
                'name'           => '水星門市',
                'address'        => 'XXX',
                'phone'          => 'XXX',
                'business_hours' => '週一至週六 09:00–22:00，週日 10:00–20:00',
                'description'    => '傳聞這間門市的地板會在深夜自己發光，吹乾機的風帶有淡淡的星際薄荷香。前台有一隻不知從哪來的白色貓咪「水晶」，沒有人見過牠進門，但每天早上牠就在那裡了。預約請趁早，時段消失的速度快得像水星繞太陽。',
                'is_active'      => true,
            ],
            [
                'name'           => '木星門市',
                'address'        => 'XXX',
                'phone'          => 'XXX',
                'business_hours' => '全年無休，24 小時接受預約（正式服務 10:00–21:00）',
                'description'    => '木星門市是我們面積最大的據點，大到你需要一張地圖才能找到洗澡間。據說門市內有 63 個美容台，但每次數都數不同。店內的植物全部是從木星土壤種的，所以長得特別快。美容師們個個身高超過兩公尺，但動作輕柔得像在捧著玻璃。',
                'is_active'      => true,
            ],
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}
