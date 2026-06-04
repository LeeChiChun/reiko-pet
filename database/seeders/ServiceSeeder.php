<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // 單項服務
            [
                'name'             => '指甲修剪',
                'category'         => 'single',
                'price'            => 150,
                'duration_minutes' => 15,
                'description'      => '使用專業寵物指甲剪，快速精準修剪，並磨除銳角，避免刮傷主人和自己。附贈指甲護理精油塗抹，維持肉墊彈性。',
                'is_active'        => true,
            ],
            [
                'name'             => '耳朵清潔',
                'category'         => 'single',
                'price'            => 200,
                'duration_minutes' => 20,
                'description'      => '使用獸醫認證耳朵清潔液，輕柔溶解耳垢並以棉花棒擦除。可有效預防耳炎，特別適合長耳朵下垂的犬種（貴賓、可卡等）定期保養。',
                'is_active'        => true,
            ],
            [
                'name'             => '基礎造型修剪',
                'category'         => 'single',
                'price'            => 500,
                'duration_minutes' => 45,
                'description'      => '針對毛髮生長過長的部分（眼周、嘴周、腳底、肛門周圍）進行精細修剪，維持整潔外觀，不做全身造型。適合兩次全套美容之間的維護保養。',
                'is_active'        => true,
            ],
            [
                'name'             => '肛門腺清潔',
                'category'         => 'single',
                'price'            => 180,
                'duration_minutes' => 10,
                'description'      => '由專業美容師手動清除肛門腺分泌物，預防肛門腺發炎。建議每 4–6 週一次，若寵物有在地板磨蹭的行為，則應提前處理。',
                'is_active'        => true,
            ],
            // 狗狗套餐
            [
                'name'             => '狗狗小套餐',
                'category'         => 'small_pkg',
                'price'            => 980,
                'duration_minutes' => 90,
                'description'      => '適合小型犬（10kg 以下）的基礎美容套餐。包含：基礎洗澡、全身吹乾梳毛、耳朵清潔、指甲修剪、肛門腺清潔。讓毛孩保持日常清潔，維持基本整潔。',
                'is_active'        => true,
            ],
            [
                'name'             => '狗狗大套餐',
                'category'         => 'large_pkg',
                'price'            => 1800,
                'duration_minutes' => 150,
                'description'      => '最完整的狗狗美容體驗。包含：基礎洗澡、全身吹乾梳毛、全身造型修剪、耳朵清潔、指甲修剪、肛門腺清潔、護毛素護理。美容後煥然一新，是毛孩最享受的尊寵時光。',
                'is_active'        => true,
            ],
            [
                'name'             => '狗狗全套（犬種專用造型）',
                'category'         => 'dog',
                'price'            => 2400,
                'duration_minutes' => 180,
                'description'      => '針對特定犬種（貴賓、比熊、雪納瑞等）設計的專業造型服務。美容師會依照犬種標準或主人需求量身打造造型，包含所有大套餐項目，另加專業造型設計與定型處理。',
                'is_active'        => true,
            ],
            // 貓咪套餐
            [
                'name'             => '貓咪小套餐',
                'category'         => 'small_pkg',
                'price'            => 1200,
                'duration_minutes' => 90,
                'description'      => '適合短毛貓的基礎美容套餐。包含：溫和洗澡、全身吹乾、梳毛去除浮毛、耳朵清潔、指甲修剪。使用貓咪專用低敏洗毛精，全程輕聲細語，讓貓咪保持冷靜。',
                'is_active'        => true,
            ],
            [
                'name'             => '貓咪大套餐',
                'category'         => 'large_pkg',
                'price'            => 2200,
                'duration_minutes' => 150,
                'description'      => '長毛貓最推薦的完整美容方案。包含：深層洗澡、全身吹乾、全身梳理去毛結、局部修剪（衛生毛、腳底毛）、耳朵清潔、指甲修剪、護毛素護理。徹底解決長毛貓的打結困擾。',
                'is_active'        => true,
            ],
            [
                'name'             => '貓咪全套（含剃毛）',
                'category'         => 'cat',
                'price'            => 1600,
                'duration_minutes' => 120,
                'description'      => '夏日最受歡迎的貓咪剃毛服務，保留頭部與尾巴，全身毛髮修短至 0.5cm，搭配洗澡與全套清潔。讓貓咪在炎夏舒爽涼快，主人也省去大量梳毛時間。',
                'is_active'        => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
