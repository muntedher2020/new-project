<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * Sample data arrays
     */
    private $categories = [
        ['id' => 1, 'name' => 'التكنولوجيا', 'slug' => 'tech', 'icon' => 'ri-cpu-line', 'color1' => '#667eea', 'color2' => '#764ba2', 'count' => 45, 'description' => 'أخبار التكنولوجيا والعلوم'],
        ['id' => 2, 'name' => 'الرياضة', 'slug' => 'sports', 'icon' => 'ri-football-fill', 'color1' => '#f093fb', 'color2' => '#f5576c', 'count' => 52, 'description' => 'أخبار الرياضة والألعاب'],
        ['id' => 3, 'name' => 'السياسة', 'slug' => 'politics', 'icon' => 'ri-newspaper-line', 'color1' => '#4facfe', 'color2' => '#00f2fe', 'count' => 38, 'description' => 'الأخبار السياسية والحكومية'],
        ['id' => 4, 'name' => 'الترفيه', 'slug' => 'entertainment', 'icon' => 'ri-movie-line', 'color1' => '#43e97b', 'color2' => '#38f9d7', 'count' => 61, 'description' => 'أخبار الأفلام والمسلسلات'],
    ];

    private $subcategories = [
        ['id' => 1, 'name' => 'الذكاء الاصطناعي', 'slug' => 'ai', 'icon' => 'fa-brain', 'color' => '#667eea', 'count' => 12],
        ['id' => 2, 'name' => 'برامج وتطبيقات', 'slug' => 'apps', 'icon' => 'fa-mobile', 'color' => '#764ba2', 'count' => 18],
        ['id' => 3, 'name' => 'كرة القدم', 'slug' => 'football', 'icon' => 'fa-futbol', 'color' => '#f5576c', 'count' => 25],
        ['id' => 4, 'name' => 'الكرة الطائرة', 'slug' => 'volleyball', 'icon' => 'fa-volleyball', 'color' => '#f093fb', 'count' => 8],
    ];

    private $featuredNews = [
        [
            'id' => 1,
            'title' => 'الذكاء الاصطناعي يُحدث ثورة في الطب والصحة',
            'description' => 'تطبيقات جديدة للذكاء الاصطناعي تساعد الأطباء في تشخيص الأمراض',
            'image' => 'https://picsum.photos/800/400?random=1',
            'date' => '2024-01-15',
            'category' => 'التكنولوجيا',
            'views' => 2540,
        ],
        [
            'id' => 2,
            'title' => 'منتخب الكرة القدم يحقق انتصاراً تاريخياً',
            'description' => 'فوز ساحق على منتخب معروف في بطولة دولية كبرى',
            'image' => 'https://picsum.photos/800/400?random=2',
            'date' => '2024-01-14',
            'category' => 'الرياضة',
            'views' => 3120,
        ],
        [
            'id' => 3,
            'title' => 'إطلاق برنامج جديد لدعم الشركات الناشئة',
            'description' => 'الحكومة تطلق برنامج تمويل بمليارات الدولارات للشركات التقنية',
            'image' => 'https://picsum.photos/800/400?random=3',
            'date' => '2024-01-13',
            'category' => 'السياسة',
            'views' => 1890,
        ],
        [
            'id' => 4,
            'title' => 'فيلم جديد يحطم أرقام الحضور في دور السينما',
            'description' => 'الفيلم الحدث يحقق إيرادات قياسية في أسبوعه الأول',
            'image' => 'https://picsum.photos/800/400?random=4',
            'date' => '2024-01-12',
            'category' => 'الترفيه',
            'views' => 2750,
        ],
    ];

    private $latestNews = [
        [
            'id' => 5,
            'title' => 'تحديثات جديدة في أنظمة التشغيل',
            'description' => 'شركات التكنولوجيا الكبرى تطلق تحديثات أمنية مهمة',
            'image' => 'https://picsum.photos/400/250?random=5',
            'date' => '2024-01-20',
            'category' => 'التكنولوجيا',
            'subcategory' => 'برامج وتطبيقات',
            'subcategory_id' => 2,
            'views' => 1250,
        ],
        [
            'id' => 6,
            'title' => 'بطولة عالمية في الشطرنج تبدأ غداً',
            'description' => 'أفضل لاعبي الشطرنج في العالم يجتمعون في بطولة حاسمة',
            'image' => 'https://picsum.photos/400/250?random=6',
            'date' => '2024-01-19',
            'category' => 'الرياضة',
            'subcategory' => 'الألعاب الذهنية',
            'subcategory_id' => 3,
            'views' => 980,
        ],
        [
            'id' => 7,
            'title' => 'اجتماعات اقتصادية في دافوس تناقش المستقبل',
            'description' => 'قادة العالم والاقتصاديون يجتمعون لمناقشة قضايا اقتصادية عالمية',
            'image' => 'https://picsum.photos/400/250?random=7',
            'date' => '2024-01-18',
            'category' => 'السياسة',
            'subcategory' => 'اقتصاد',
            'subcategory_id' => 4,
            'views' => 1520,
        ],
        [
            'id' => 8,
            'title' => 'نجم عالمي ينضم لمسلسل درامي جديد',
            'description' => 'ممثل شهير يظهر في مسلسل من الموسم القادم',
            'image' => 'https://picsum.photos/400/250?random=8',
            'date' => '2024-01-17',
            'category' => 'الترفيه',
            'subcategory' => 'المسلسلات',
            'subcategory_id' => 1,
            'views' => 2100,
        ],
    ];

    private $trendingNews = [
        [
            'id' => 9,
            'title' => 'اكتشاف علمي جديد يفتح آفاقاً واسعة',
            'description' => 'العلماء يكتشفون مادة جديدة بخصائص فريدة',
            'image' => 'https://picsum.photos/500/300?random=9',
            'category' => 'التكنولوجيا',
            'views' => 5420,
            'date' => '2024-01-16',
        ],
        [
            'id' => 10,
            'title' => 'مباراة حاسمة في نهائيات كأس العالم',
            'description' => 'فريق يصل إلى نهائي البطولة الأهم بفوز مثير',
            'image' => 'https://picsum.photos/500/300?random=10',
            'category' => 'الرياضة',
            'views' => 6890,
            'date' => '2024-01-15',
        ],
        [
            'id' => 11,
            'title' => 'قرار سياسي يثير جدلاً واسعاً في الرأي العام',
            'description' => 'الحكومة تتخذ قراراً جديداً بشأن قضية وطنية مهمة',
            'image' => 'https://picsum.photos/500/300?random=11',
            'category' => 'السياسة',
            'views' => 4720,
            'date' => '2024-01-14',
        ],
        [
            'id' => 12,
            'title' => 'حفل توزيع جوائز عالمي يشهد مفاجآت كبرى',
            'description' => 'حفل حفل توزيع الجوائز السينمائية الأشهر يحمل مفاجآت',
            'image' => 'https://picsum.photos/500/300?random=12',
            'category' => 'الترفيه',
            'views' => 5120,
            'date' => '2024-01-13',
        ],
    ];

    private $galleryImages = [
        ['id' => 1, 'title' => 'مؤتمر تقني عالمي', 'category' => 'التكنولوجيا', 'category_id' => 1, 'thumbnail' => 'https://picsum.photos/300/300?random=1', 'full' => 'https://picsum.photos/1200/800?random=1'],
        ['id' => 2, 'title' => 'مباراة كرة قدم مثيرة', 'category' => 'الرياضة', 'category_id' => 2, 'thumbnail' => 'https://picsum.photos/300/300?random=2', 'full' => 'https://picsum.photos/1200/800?random=2'],
        ['id' => 3, 'title' => 'اجتماع حكومي رسمي', 'category' => 'السياسة', 'category_id' => 3, 'thumbnail' => 'https://picsum.photos/300/300?random=3', 'full' => 'https://picsum.photos/1200/800?random=3'],
        ['id' => 4, 'title' => 'حفل سينمائي فخم', 'category' => 'الترفيه', 'category_id' => 4, 'thumbnail' => 'https://picsum.photos/300/300?random=4', 'full' => 'https://picsum.photos/1200/800?random=4'],
        ['id' => 5, 'title' => 'عرض تكنولوجيا جديدة', 'category' => 'التكنولوجيا', 'category_id' => 1, 'thumbnail' => 'https://picsum.photos/300/300?random=5', 'full' => 'https://picsum.photos/1200/800?random=5'],
        ['id' => 6, 'title' => 'بطولة رياضية عالمية', 'category' => 'الرياضة', 'category_id' => 2, 'thumbnail' => 'https://picsum.photos/300/300?random=6', 'full' => 'https://picsum.photos/1200/800?random=6'],
        ['id' => 7, 'title' => 'مؤتمر اقتصادي دولي', 'category' => 'السياسة', 'category_id' => 3, 'thumbnail' => 'https://picsum.photos/300/300?random=7', 'full' => 'https://picsum.photos/1200/800?random=7'],
        ['id' => 8, 'title' => 'مهرجان فني شهير', 'category' => 'الترفيه', 'category_id' => 4, 'thumbnail' => 'https://picsum.photos/300/300?random=8', 'full' => 'https://picsum.photos/1200/800?random=8'],
    ];

    private $videos = [
        ['id' => 1, 'title' => 'تقرير عن أحدث التطورات التقنية', 'category' => 'التكنولوجيا', 'category_id' => 1, 'description' => 'يشرح هذا الفيديو أحدث الابتكارات في عالم التكنولوجيا', 'video_id' => 'aqz-KE-bpKQ', 'thumbnail' => 'https://img.youtube.com/vi/aqz-KE-bpKQ/hqdefault.jpg', 'date' => '2024-01-15', 'views' => 2400, 'duration' => '12:34'],
        ['id' => 2, 'title' => 'تحليل الفوز التاريخي في البطولة', 'category' => 'الرياضة', 'category_id' => 2, 'description' => 'تحليل مفصل للمباراة التاريخية والتكتيكات المستخدمة', 'video_id' => '9bZkp7q19f0', 'thumbnail' => 'https://img.youtube.com/vi/9bZkp7q19f0/hqdefault.jpg', 'date' => '2024-01-14', 'views' => 3120, 'duration' => '18:45'],
        ['id' => 3, 'title' => 'ندوة سياسية حول المستقبل الاقتصادي', 'category' => 'السياسة', 'category_id' => 3, 'description' => 'خبراء اقتصاديون يناقشون التحديات والفرص الاقتصادية', 'video_id' => 'jNQXAC9IVRw', 'thumbnail' => 'https://img.youtube.com/vi/jNQXAC9IVRw/hqdefault.jpg', 'date' => '2024-01-13', 'views' => 1850, 'duration' => '25:10'],
        ['id' => 4, 'title' => 'عرض حصري للفيلم الجديد', 'category' => 'الترفيه', 'category_id' => 4, 'description' => 'عرض خاص ومقابلة مع نجوم الفيلم الجديد', 'video_id' => 'PWz97HhfnZo', 'thumbnail' => 'https://img.youtube.com/vi/PWz97HhfnZo/hqdefault.jpg', 'date' => '2024-01-12', 'views' => 4560, 'duration' => '15:22'],
        ['id' => 5, 'title' => 'شرح تقنية جديدة في البرمجة', 'category' => 'التكنولوجيا', 'category_id' => 1, 'description' => 'برمج يشرح أحدث تقنيات البرمجة بطريقة سهلة', 'video_id' => 'ibVrx0Kl7Jk', 'thumbnail' => 'https://img.youtube.com/vi/ibVrx0Kl7Jk/hqdefault.jpg', 'date' => '2024-01-11', 'views' => 2100, 'duration' => '20:00'],
        ['id' => 6, 'title' => 'تدريب رياضي متقدم من البطل', 'category' => 'الرياضة', 'category_id' => 2, 'description' => 'بطل عالمي يشرح تقنيات التدريب المتقدمة', 'video_id' => 'AyYp1xDHNO0', 'thumbnail' => 'https://img.youtube.com/vi/AyYp1xDHNO0/hqdefault.jpg', 'date' => '2024-01-10', 'views' => 3400, 'duration' => '14:56'],
    ];

    public function home() {
        $pageConfigs = ['myLayout' => 'front'];

        return view('content.frontend.home', [
            'pageConfigs' => $pageConfigs,
            'featuredNews' => $this->featuredNews,
            'latestNews' => $this->latestNews,
            'categories' => $this->categories,
            'trendingNews' => $this->trendingNews,
            'galleryImages' => collect($this->galleryImages)->take(4)->toArray(),
            'videos' => collect($this->videos)->take(4)->toArray(),
        ]);
    }
}
