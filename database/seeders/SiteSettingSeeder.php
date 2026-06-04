<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── Hero 區塊 ──────────────────────────────────────────
            ['key' => 'hero_badge',    'label' => 'Hero 副標（英文小字）', 'type' => 'text',     'group' => 'hero',  'value' => 'Premium Pet Grooming'],
            ['key' => 'hero_title',    'label' => 'Hero 主標題',           'type' => 'textarea', 'group' => 'hero',  'value' => null],
            ['key' => 'hero_subtitle', 'label' => 'Hero 副標題說明文字',   'type' => 'textarea', 'group' => 'hero',  'value' => null],
            ['key' => 'hero_image',    'label' => 'Hero 背景圖片',          'type' => 'image',    'group' => 'hero',  'value' => null],

            // ── 品牌故事 ─────────────────────────────────────────
            ['key' => 'brand_story_title',  'label' => '品牌故事標題',   'type' => 'text',     'group' => 'brand', 'value' => '以「禮」為心，以溫柔為手'],
            ['key' => 'brand_story_text_1', 'label' => '品牌故事第一段', 'type' => 'textarea', 'group' => 'brand', 'value' => '「禮寵」中的「禮」，日文讀作「れい（Rei）」，象徵敬意與溫柔；品牌英文名 Reiko 正是由此而來。禮，是我們對每一位毛孩的承諾。'],
            ['key' => 'brand_story_text_2', 'label' => '品牌故事第二段', 'type' => 'textarea', 'group' => 'brand', 'value' => '美容師皆受過嚴格培訓，擅長以「零壓力」方式讓寵物放鬆，讓每次美容都成為毛孩期待的美好時光。'],

            // ── OUR PHILOSOPHY 區塊（左側圖片 & 統計數字）────────
            ['key' => 'philosophy_image',    'label' => 'Philosophy 左側圖片', 'type' => 'image', 'group' => 'philosophy', 'value' => null],
            ['key' => 'stat_stores',         'label' => '高雄門市數字',         'type' => 'text',  'group' => 'philosophy', 'value' => '3+'],
            ['key' => 'stat_pets',           'label' => '服務毛孩數字',         'type' => 'text',  'group' => 'philosophy', 'value' => '500+'],
            ['key' => 'stat_satisfaction',   'label' => '顧客滿意數字',         'type' => 'text',  'group' => 'philosophy', 'value' => '98%'],

            // ── 關於我們頁面 ─────────────────────────────────────
            ['key' => 'about_hero_subtitle', 'label' => '關於我們 Hero 副標',      'type' => 'textarea', 'group' => 'about', 'value' => '2026 年，在燕巢的一間小美容室，一個大帥哥與一隻臘腸吉娃娃混血犬一起寫下了這個故事。'],
            ['key' => 'about_founder_image', 'label' => '創辦人照片',              'type' => 'image',    'group' => 'about', 'value' => null],
            ['key' => 'about_story_title',   'label' => '創辦人故事大標題',        'type' => 'text',     'group' => 'about', 'value' => '從高雄出發，以燕巢為根'],
            ['key' => 'about_story_p1',      'label' => '故事段落一',              'type' => 'textarea', 'group' => 'about', 'value' => '集雋（Justin），一個從小在高雄長大的大帥哥。2026 年，他帶著對寵物美容的熱愛與在燕巢三年深耕的專業技術，創辦了禮寵 Reiko Pet。'],
            ['key' => 'about_story_p2',      'label' => '故事段落二',              'type' => 'textarea', 'group' => 'about', 'value' => '故事的起點，是他的毛孩旺財——一隻臘腸與吉娃娃混血的調皮小鬼。正是因為旺財，Justin 開始深入研究如何讓美容過程對寵物真正無壓力、真正舒適。'],
            ['key' => 'about_story_p3',      'label' => '故事段落三',              'type' => 'textarea', 'group' => 'about', 'value' => '「禮」字，日文讀作「れい（Rei）」，意指敬意與溫柔。品牌英文名 Reiko 正是由此而來——我們相信每一位毛孩都值得被以最高的敬意對待。'],
            ['key' => 'about_story_p4',      'label' => '故事段落四',              'type' => 'textarea', 'group' => 'about', 'value' => '從燕巢旗艦店出發，禮寵一步步走向更多城市，將這份職人精神帶給更多毛孩家庭。'],
            ['key' => 'about_stat_founded',  'label' => '創立年份',                'type' => 'text',     'group' => 'about', 'value' => '2026'],
            ['key' => 'about_val1_icon',     'label' => '核心價值一 字符',         'type' => 'text',     'group' => 'about', 'value' => '礼'],
            ['key' => 'about_val1_title',    'label' => '核心價值一 標題',         'type' => 'text',     'group' => 'about', 'value' => '以禮相待'],
            ['key' => 'about_val1_desc',     'label' => '核心價值一 說明',         'type' => 'textarea', 'group' => 'about', 'value' => '每一次觸碰，都帶著對毛孩的尊重。我們不趕時間，不嫌麻煩，因為你的毛孩值得最好的對待。'],
            ['key' => 'about_val2_icon',     'label' => '核心價值二 字符',         'type' => 'text',     'group' => 'about', 'value' => '匠'],
            ['key' => 'about_val2_title',    'label' => '核心價值二 標題',         'type' => 'text',     'group' => 'about', 'value' => '職人精神'],
            ['key' => 'about_val2_desc',     'label' => '核心價值二 說明',         'type' => 'textarea', 'group' => 'about', 'value' => '美容師皆通過嚴格的燕巢職人訓練，精通犬貓各品種特性，以「零壓力」手法讓寵物安心。'],
            ['key' => 'about_val3_icon',     'label' => '核心價值三 字符',         'type' => 'text',     'group' => 'about', 'value' => '愛'],
            ['key' => 'about_val3_title',    'label' => '核心價值三 標題',         'type' => 'text',     'group' => 'about', 'value' => '用愛守護'],
            ['key' => 'about_val3_desc',     'label' => '核心價值三 說明',         'type' => 'textarea', 'group' => 'about', 'value' => '每隻毛孩都是你的家人，也是我們認真對待的對象。從進門到離開，全程以愛相伴。'],

            // ── 寵物住宿頁面 ────────────────────────────────────
            ['key' => 'accom_hero_title',    'label' => '住宿頁 Hero 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '寵物住宿'],
            ['key' => 'accom_hero_subtitle', 'label' => '住宿頁 Hero 副標', 'type' => 'textarea', 'group' => 'accommodation', 'value' => '當你需要出差或旅行，把毛孩安心託付給禮寵。我們提供舒適、安全、充滿溫度的住宿環境。'],
            // 住宿福利 8 項
            ['key' => 'accom_b1_icon',  'label' => '福利1 圖示', 'type' => 'text',     'group' => 'accommodation', 'value' => '🌡️'],
            ['key' => 'accom_b1_title', 'label' => '福利1 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '恆溫空調'],
            ['key' => 'accom_b1_desc',  'label' => '福利1 說明', 'type' => 'textarea', 'group' => 'accommodation', 'value' => '全年恆溫環境，夏涼冬暖，確保毛孩舒適。'],
            ['key' => 'accom_b2_icon',  'label' => '福利2 圖示', 'type' => 'text',     'group' => 'accommodation', 'value' => '📹'],
            ['key' => 'accom_b2_title', 'label' => '福利2 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '24 小時監控'],
            ['key' => 'accom_b2_desc',  'label' => '福利2 說明', 'type' => 'textarea', 'group' => 'accommodation', 'value' => '全館攝影機守護，主人可遠端查看狀況。'],
            ['key' => 'accom_b3_icon',  'label' => '福利3 圖示', 'type' => 'text',     'group' => 'accommodation', 'value' => '🍽️'],
            ['key' => 'accom_b3_title', 'label' => '福利3 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '定時餵食'],
            ['key' => 'accom_b3_desc',  'label' => '福利3 說明', 'type' => 'textarea', 'group' => 'accommodation', 'value' => '依毛孩飲食習慣準時提供餐食與飲水。'],
            ['key' => 'accom_b4_icon',  'label' => '福利4 圖示', 'type' => 'text',     'group' => 'accommodation', 'value' => '🩺'],
            ['key' => 'accom_b4_title', 'label' => '福利4 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '健康關注'],
            ['key' => 'accom_b4_desc',  'label' => '福利4 說明', 'type' => 'textarea', 'group' => 'accommodation', 'value' => '專業人員每日觀察健康狀況，異常即通知。'],
            ['key' => 'accom_b5_icon',  'label' => '福利5 圖示', 'type' => 'text',     'group' => 'accommodation', 'value' => '🧸'],
            ['key' => 'accom_b5_title', 'label' => '福利5 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '互動陪伴'],
            ['key' => 'accom_b5_desc',  'label' => '福利5 說明', 'type' => 'textarea', 'group' => 'accommodation', 'value' => '每日互動時間，讓毛孩不孤單不焦慮。'],
            ['key' => 'accom_b6_icon',  'label' => '福利6 圖示', 'type' => 'text',     'group' => 'accommodation', 'value' => '🛁'],
            ['key' => 'accom_b6_title', 'label' => '福利6 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '清潔消毒'],
            ['key' => 'accom_b6_desc',  'label' => '福利6 說明', 'type' => 'textarea', 'group' => 'accommodation', 'value' => '每日房間清潔消毒，維持衛生環境。'],
            ['key' => 'accom_b7_icon',  'label' => '福利7 圖示', 'type' => 'text',     'group' => 'accommodation', 'value' => '📱'],
            ['key' => 'accom_b7_title', 'label' => '福利7 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '動態回報'],
            ['key' => 'accom_b7_desc',  'label' => '福利7 說明', 'type' => 'textarea', 'group' => 'accommodation', 'value' => '每日傳送毛孩照片，讓主人安心出遊。'],
            ['key' => 'accom_b8_icon',  'label' => '福利8 圖示', 'type' => 'text',     'group' => 'accommodation', 'value' => '🏠'],
            ['key' => 'accom_b8_title', 'label' => '福利8 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '獨立空間'],
            ['key' => 'accom_b8_desc',  'label' => '福利8 說明', 'type' => 'textarea', 'group' => 'accommodation', 'value' => '各房型獨立隔間，確保毛孩隱私與安全。'],
            // 住宿須知 4 組，每組 items 以換行分隔
            ['key' => 'accom_r1_title', 'label' => '須知1 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '預約與付款'],
            ['key' => 'accom_r1_items', 'label' => '須知1 內容（每行一項）', 'type' => 'textarea', 'group' => 'accommodation', 'value' => "住宿需提前 48 小時以上預約，節假日建議 7 天前預約。\n付款採線上轉帳或入住時現場付款。\n取消訂房請於入住 48 小時前告知，48 小時內退款 50%。"],
            ['key' => 'accom_r2_title', 'label' => '須知2 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '健康要求'],
            ['key' => 'accom_r2_items', 'label' => '須知2 內容（每行一項）', 'type' => 'textarea', 'group' => 'accommodation', 'value' => "入住毛孩需具備有效疫苗接種證明（狂犬病、混合疫苗）。\n有傳染性疾病、皮膚病或寄生蟲問題的毛孩恕不接受入住。\n若入住期間出現健康狀況，將立即聯繫主人並安排就醫。"],
            ['key' => 'accom_r3_title', 'label' => '須知3 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '攜帶物品'],
            ['key' => 'accom_r3_items', 'label' => '須知3 內容（每行一項）', 'type' => 'textarea', 'group' => 'accommodation', 'value' => "請自備慣用飼料，份量請依住宿天數提供並標示。\n可攜帶毛孩熟悉的玩具或毛毯，有助減少分離焦慮。\n請勿攜帶貴重物品，本館不負相關遺失責任。"],
            ['key' => 'accom_r4_title', 'label' => '須知4 標題', 'type' => 'text',     'group' => 'accommodation', 'value' => '其他規定'],
            ['key' => 'accom_r4_items', 'label' => '須知4 內容（每行一項）', 'type' => 'textarea', 'group' => 'accommodation', 'value' => "入住時間：13:00 – 20:00；退房時間：12:00 前。\n住宿期間若需美容服務，可加購，費用另計。\n本館保留拒絕攻擊性或嚴重焦慮寵物入住之權利。"],
        ];

        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
