<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['tag' => '系統公告', 'title' => '線上預約系統全新上線，歡迎提前預約您的美容時段', 'content' => '為提供更便捷的預約體驗，禮寵線上預約系統正式啟用。您可隨時透過網站選擇門市、服務及時段，即時確認，不再需要電話預約。', 'published_at' => '2026-05-01', 'sort_order' => 4],
            ['tag' => '門市公告', 'title' => '木星門市正式開幕，現正接受預約，開幕限定優惠同步啟動', 'content' => '禮寵第三家門市——木星門市——正式開幕！地址位於高雄市楠梓區，開幕期間享有首次預約 8 折優惠，歡迎把握！', 'published_at' => '2026-04-28', 'sort_order' => 3],
            ['tag' => '節日公告', 'title' => '勞動節（5/1）正常營業，母親節（5/11）加開美容時段', 'content' => '勞動節當天照常提供美容服務。母親節為感謝每位毛孩的媽媽，特別加開下午及傍晚時段，敬請提早預約。', 'published_at' => '2026-04-20', 'sort_order' => 2],
            ['tag' => '政策調整', 'title' => '即日起預約取消請於24小時前告知，以確保服務品質', 'content' => '為避免時段閒置、影響其他顧客預約，預約取消或更改時段請於 24 小時前透過系統或電話告知，感謝配合。', 'published_at' => '2026-04-10', 'sort_order' => 1],
        ];

        foreach ($items as $item) {
            Announcement::create($item);
        }
    }
}
