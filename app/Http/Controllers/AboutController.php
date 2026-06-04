<?php
namespace App\Http\Controllers;

use App\Models\{Store, SiteSetting, SurveyQuestion};

class AboutController extends Controller
{
    public function index()
    {
        $stores          = Store::active()->get();
        $surveyQuestions = SurveyQuestion::active()->get();

        $settings = [
            'hero_subtitle' => SiteSetting::get('about_hero_subtitle', '2026 年，在燕巢的一間小美容室，一個大帥哥與一隻臘腸吉娃娃混血犬一起寫下了這個故事。'),
            'founder_image' => SiteSetting::get('about_founder_image', null),
            'story_title'   => SiteSetting::get('about_story_title',   '從高雄出發，以燕巢為根'),
            'story_p1'      => SiteSetting::get('about_story_p1',      '集雋（Justin），一個從小在高雄長大的大帥哥。2026 年，他帶著對寵物美容的熱愛與在燕巢三年深耕的專業技術，創辦了禮寵 Reiko Pet。'),
            'story_p2'      => SiteSetting::get('about_story_p2',      '故事的起點，是他的毛孩旺財——一隻臘腸與吉娃娃混血的調皮小鬼。正是因為旺財，Justin 開始深入研究如何讓美容過程對寵物真正無壓力、真正舒適。'),
            'story_p3'      => SiteSetting::get('about_story_p3',      '「禮」字，日文讀作「れい（Rei）」，意指敬意與溫柔。品牌英文名 Reiko 正是由此而來——我們相信每一位毛孩都值得被以最高的敬意對待。'),
            'story_p4'      => SiteSetting::get('about_story_p4',      '從燕巢旗艦店出發，禮寵一步步走向更多城市，將這份職人精神帶給更多毛孩家庭。'),
            'stat_founded'  => SiteSetting::get('about_stat_founded',  '2026'),
            'stat_stores'   => SiteSetting::get('stat_stores',         '3+'),
            'stat_satisfaction' => SiteSetting::get('stat_satisfaction', '98%'),
            'val1_icon'     => SiteSetting::get('about_val1_icon',     '礼'),
            'val1_title'    => SiteSetting::get('about_val1_title',    '以禮相待'),
            'val1_desc'     => SiteSetting::get('about_val1_desc',     '每一次觸碰，都帶著對毛孩的尊重。我們不趕時間，不嫌麻煩，因為你的毛孩值得最好的對待。'),
            'val2_icon'     => SiteSetting::get('about_val2_icon',     '匠'),
            'val2_title'    => SiteSetting::get('about_val2_title',    '職人精神'),
            'val2_desc'     => SiteSetting::get('about_val2_desc',     '美容師皆通過嚴格的燕巢職人訓練，精通犬貓各品種特性，以「零壓力」手法讓寵物安心。'),
            'val3_icon'     => SiteSetting::get('about_val3_icon',     '愛'),
            'val3_title'    => SiteSetting::get('about_val3_title',    '用愛守護'),
            'val3_desc'     => SiteSetting::get('about_val3_desc',     '每隻毛孩都是你的家人，也是我們認真對待的對象。從進門到離開，全程以愛相伴。'),
        ];

        return view('about', compact('stores', 'settings', 'surveyQuestions'));
    }
}
