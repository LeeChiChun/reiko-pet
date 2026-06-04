<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // 零食點心
            [
                'name'        => '星球餅乾禮盒',
                'category'    => 'snack',
                'price'       => 580,
                'stock'       => 80,
                'image'       => 'images/products/01.jpg',
                'description' => '以八大行星為靈感設計的星球造型餅乾，使用有機雞肉乾燥製成，低熱量高蛋白，適合所有犬貓。禮盒包裝精美，是送禮的絕佳選擇。每顆餅乾大小適中，可直接作為零食獎勵使用。',
                'is_active'   => true,
            ],
            [
                'name'        => '北海道鮭魚凍乾',
                'category'    => 'snack',
                'price'       => 420,
                'stock'       => 60,
                'image'       => 'images/products/02.jpg',
                'description' => '精選北海道新鮮鮭魚，透過低溫凍乾技術保留完整營養。富含 Omega-3 脂肪酸，有助於維持毛髮光澤、關節健康。無添加、無防腐劑，適合挑嘴的貓咪與狗狗。',
                'is_active'   => true,
            ],
            [
                'name'        => '和牛肉條（10 入）',
                'category'    => 'snack',
                'price'       => 350,
                'stock'       => 100,
                'image'       => 'images/products/03.jpg',
                'description' => '使用台灣在地牛肉慢火烘烤，無任何化學添加。韌度適中，適合當作訓練獎勵或日常零食。每條約 10g，熱量控制在 30 卡以下，不用擔心過胖問題。',
                'is_active'   => true,
            ],
            [
                'name'        => '芋見你鴨肉絲',
                'category'    => 'snack',
                'price'       => 290,
                'stock'       => 75,
                'image'       => 'images/products/04.jpg',
                'description' => '嚴選台灣櫻桃鴨肉，手工撕成細絲後低溫烘焙。鴨肉蛋白質高、脂肪低，特別適合需要控制體重的寵物。獨立包裝設計，開封不受潮，保持最佳口感。',
                'is_active'   => true,
            ],
            // 玩具
            [
                'name'        => '宇宙飛碟嗅聞玩具',
                'category'    => 'toy',
                'price'       => 680,
                'stock'       => 40,
                'image'       => 'images/products/05.jpg',
                'description' => '以宇宙飛碟為造型的多層嗅聞慢食玩具。可以在凹槽中藏入零食，讓狗狗透過嗅覺和爪子尋找食物，同時訓練大腦和延緩進食速度，對有狼吞虎嚥習慣的狗狗特別有效。',
                'is_active'   => true,
            ],
            [
                'name'        => '銀河逗貓棒（含替換羽毛）',
                'category'    => 'toy',
                'price'       => 380,
                'stock'       => 55,
                'image'       => 'images/products/06.jpg',
                'description' => '彈性十足的不鏽鋼細桿配上天然羽毛，模擬鳥類飛行的不規則運動，激發貓咪的狩獵本能。附贈三組替換羽毛，使用壽命長。棒身有防滑握柄設計，輕鬆舞動不費力。',
                'is_active'   => true,
            ],
            [
                'name'        => '木星磨牙骨頭（L 號）',
                'category'    => 'toy',
                'price'       => 450,
                'stock'       => 35,
                'image'       => 'images/products/07.jpg',
                'description' => '採用天然橡木製成，硬度經過特別設計——夠硬可以磨牙，但不會硬到傷害牙齒。表面刻有不規則紋路，可在裡面藏入零食增加吸引力。適合中大型犬使用。',
                'is_active'   => true,
            ],
            // 清潔用品
            [
                'name'        => '白月光天然洗毛精（500ml）',
                'category'    => 'cleaning',
                'price'       => 680,
                'stock'       => 90,
                'image'       => 'images/products/08.jpg',
                'description' => '禮寵自主開發配方，以植物萃取成分為主，完全無矽靈、無 SLS、無人工香精。溫和清潔同時保留皮膚天然油脂，適合敏感肌膚或患有皮膚病的寵物使用。輕盈茉莉花香，洗後不殘留。',
                'is_active'   => true,
            ],
            [
                'name'        => '行星耳朵清潔液（120ml）',
                'category'    => 'cleaning',
                'price'       => 380,
                'stock'       => 65,
                'image'       => 'images/products/09.jpg',
                'description' => '獸醫師認證配方，溫和中性 pH 值，有效溶解耳垢、抑制細菌繁殖。使用方式簡單，直接滴入外耳道後輕柔按摩，再用棉花棒擦除即可。定期使用可預防耳炎發生。',
                'is_active'   => true,
            ],
            [
                'name'        => '星辰環境除臭噴霧（300ml）',
                'category'    => 'cleaning',
                'price'       => 320,
                'stock'       => 120,
                'image'       => 'images/products/10.jpg',
                'description' => '採用酵素分解技術，從根源消除寵物體味、尿味、糞便氣味，而非僅僅掩蓋。可直接噴灑於寵物睡窩、地毯、沙發等任何材質表面。不含酒精，對寵物和嬰幼兒完全安全。',
                'is_active'   => true,
            ],
            // 衣著配件
            [
                'name'        => '日式小花紋浴巾（M/L）',
                'category'    => 'clothing',
                'price'       => 580,
                'stock'       => 45,
                'image'       => 'images/products/11.jpg',
                'description' => '採用日本今治認證有機棉，吸水性極佳，洗澡後一條就能搞定。設計了可穿套的頸部開口，讓毛孩不會因亂抖把水甩得到處都是。印有細膩日式小花紋，放在家中也是美麗的家飾。',
                'is_active'   => true,
            ],
            [
                'name'        => '星系印花寵物毛衣（S/M/L/XL）',
                'category'    => 'clothing',
                'price'       => 780,
                'stock'       => 30,
                'image'       => 'images/products/12.jpg',
                'description' => '以銀河星系為靈感的印花設計，墨綠底色配上點點星光，時髦又不失典雅。採用美麗諾羊毛混紡，輕柔保暖不扎皮膚。提供四種尺寸，附贈量身尺寸對照表，輕鬆找到最合適的大小。',
                'is_active'   => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
