<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['badge' => '限時優惠', 'period' => '即日起 – 2026.05.31', 'title' => '母親節感恩方案', 'description' => '凡預約狗狗或貓咪大套餐，即享 9 折優惠，並贈送香氛護毛加值服務乙次。', 'tag' => '全館適用', 'color' => 'bg-accent', 'link_url' => '/booking', 'sort_order' => 3],
            ['badge' => '新客優惠', 'period' => '首次預約', 'title' => '初次光臨，體驗優惠', 'description' => '首次預約會員享 85 折優惠，適用全部美容服務，讓毛孩以最優惠的價格體驗禮寵品質。', 'tag' => '單次使用', 'color' => 'bg-warm-gray', 'link_url' => '/booking', 'sort_order' => 2],
            ['badge' => '包月方案', 'period' => '長期有效', 'title' => '月月美容，省更多', 'description' => '購買四次美容套票，享第五次免費。適合定期安排美容的毛孩家庭，省力又省錢。', 'tag' => '需預先購買', 'color' => 'bg-accent', 'link_url' => '/booking', 'sort_order' => 1],
        ];

        foreach ($items as $item) {
            Promotion::create($item);
        }
    }
}
