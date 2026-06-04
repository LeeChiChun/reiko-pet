<?php

namespace Database\Seeders;

use App\Models\AddonService;
use Illuminate\Database\Seeder;

class AddonServiceSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            ['name' => '香氛護毛', 'price' => 200, 'description' => '添加天然香氛精油，讓毛髮更亮澤。', 'applicable_to' => 'both'],
            ['name' => '牙齒清潔', 'price' => 250, 'description' => '專業牙齒清潔，預防口腔疾病。', 'applicable_to' => 'both'],
            ['name' => '深層去油（限貓咪）', 'price' => 350, 'description' => '專為貓咪設計的深層去油配方。', 'applicable_to' => 'cat'],
            ['name' => '跳蚤防治', 'price' => 300, 'description' => '使用安全驅蟲產品，有效防治跳蚤。', 'applicable_to' => 'both'],
            ['name' => '毛色護理', 'price' => 280, 'description' => '專業護毛素，增加毛色光澤。', 'applicable_to' => 'dog'],
        ];

        foreach ($addons as $addon) {
            AddonService::create($addon);
        }
    }
}
