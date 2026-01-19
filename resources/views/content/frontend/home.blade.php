@php
$configData = Helper::appClasses();
@endphp
{{-- @extends('layouts/horizontalLayout') --}}
@extends('layouts/layoutMaster')

@section('title', 'الرئيسية')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/nouislider/nouislider.scss', 'resources/assets/vendor/libs/swiper/swiper.scss'])
@endsection

<!-- Page Styles -->
@section('page-style')
@vite(['resources/assets/vendor/scss/pages/front-page-landing.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite(['resources/assets/vendor/libs/nouislider/nouislider.js', 'resources/assets/vendor/libs/swiper/swiper.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite(['resources/assets/js/front-page-landing.js'])
@endsection

@section('content')
<section class="p-12 flex items-center justify-center overflow-hidden bg-footer-theme" style="margin-bottom: 100px; margin-top: 100px">
    <div class="flex items-center justify-center w-100">
        <img src="{{ asset('assets/img/personal/06bf32b8375ba6c9f5821ff6f957a605.png') }}"
            class="h-32 md:h-30 object-contain img-theme-aware">

        <h2 class="font-extrabold img-theme-aware leading-tight hero-text-shadow text-center">
            لجنة التنمية الاقتصادية والاستثمار
            <p class="text-xl font-light max-w-2xl mx-auto">نحو مستقبل اقتصادي مستدام وبيئة استثمارية رائدة</p>
        </h2>
    </div>
    <div class="flex flex-col justify-center items-center text-center w-75">
        <img src="{{ asset('assets/img/personal/IMG_0700.png') }}" style="width: 300px">

        <img src="{{ asset('assets/img/personal/IMG_0700 - Cop12121y.png') }}"
            class="h-24 md:h-24 object-contain img-theme-aware mt-n4" style="width: 300px; ">
    </div>
</section>

<!-- Hero Slider -->
<section class="section-padding px-0">
    <div id="carouselExample" class="rounded carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($featuredNews as $index => $news)
            <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="{{ $index }}"
                class="{{ $index == 0 ? 'active' : '' }}" aria-current="true"
                aria-label="Slide {{ ++$index }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner rounded">
            @foreach($featuredNews as $index => $news)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" style="height: 450px !important">
                <img class="d-block w-100" src="{{ $news['image'] }}" alt="{{ $news['title'] }}"
                    style="height: 450px !important" />
                <div class="carousel-caption d-none d-md-block text-block">
                    <h3 class="hero-text-shadow">{{ $news['title'] }}</h3>
                    <p class="hero-text-shadow">{{ str()->limit($news['description'], 100) }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </a>
    </div>
</section>

{{-- الأخبار العاجلة --}}
<section class="section-padding">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="mb-4">الأخبار العاجلة</h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div
                class="bg-ieg-light-blue-bg p-6 border rounded-xl hover:shadow-xl transition-shadow duration-300 flex flex-col items-center text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="h-10 w-10 text-ieg-red mb-4">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <h3 class="text-xl font-semibold text-ieg-text-dark mb-2">الخبرة</h3>
                <p class="text-base text-ieg-text-gray leading-relaxed">مع سنوات من الخبرة في هذا
                    المجال، لدينا الخبرة اللازمة لتخطيط وتنفيذ فعاليات ناجحة.</p>
            </div>
            <div
                class="bg-ieg-light-blue-bg p-6 border rounded-xl hover:shadow-xl transition-shadow duration-300 flex flex-col items-center text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="h-10 w-10 text-ieg-red mb-4">
                    <circle cx="12" cy="12" r="10"></circle>
                    <circle cx="12" cy="12" r="6"></circle>
                    <circle cx="12" cy="12" r="2"></circle>
                </svg>
                <h3 class="text-xl font-semibold text-ieg-text-dark mb-2">الاهتمام بالتفاصيل</h3>
                <p class="text-base text-ieg-text-gray leading-relaxed">نولي اهتمامًا وثيقًا لكل تفاصيل
                    فعاليتك لضمان تلبيتها لتوقعاتك.</p>
            </div>
            <div class="bg-ieg-light-blue-bg p-6 border rounded-xl hover:shadow-xl transition-shadow duration-300 flex flex-col items-center text-center"
                style="opacity: 1; transform: none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="h-10 w-10 text-ieg-red mb-4">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                <h3 class="text-xl font-semibold text-ieg-text-dark mb-2">الاحترافية</h3>
                <p class="text-base text-ieg-text-gray leading-relaxed">فريقنا محترف ومكرس لتقديم خدمة
                    استثنائية في جميع الأوقات.</p>
            </div>
            <div class="bg-ieg-light-blue-bg p-6 border rounded-xl hover:shadow-xl transition-shadow duration-300 flex flex-col items-center text-center"
                style="opacity: 1; transform: none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="h-10 w-10 text-ieg-red mb-4">
                    <path d="M20 7h-9"></path>
                    <path d="M14 17H5"></path>
                    <circle cx="17" cy="17" r="3"></circle>
                    <circle cx="7" cy="7" r="3"></circle>
                </svg>
                <h3 class="text-xl font-semibold text-ieg-text-dark mb-2">حلول مخصصة</h3>
                <p class="text-base text-ieg-text-gray leading-relaxed">نتفهم أن لكل عميل احتياجات
                    وأهداف فريدة. لهذا السبب نقوم بتطوير حلول مخصصة مصممة خصيصًا لمتطلبات كل عميل.</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest News -->
<section class="section-padding">
    <div class="section-title">
        <h2>آخر الأخبار</h2>
    </div>
    <div class="row g-4">
        @foreach($latestNews as $news)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <img src="{{ $news['image'] }}" class="card-img-top" alt="{{ $news['title'] }}"
                    style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge badge-primary">{{ $news['category'] }}</span>
                        <small class="text-muted ms-2">
                            <i class="far fa-clock"></i> {{ $news['date'] ?? 'غير محدد' }}
                        </small>
                    </div>
                    <h5 class="card-title">{{ $news['title'] }}</h5>
                    <p class="card-text text-muted">{{ str()->limit($news['description'], 80) }}</p>
                    <a href="/news/{{ $news['id'] }}" class="btn btn-primary btn-sm">اقرأ المزيد</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- الأقسام -->
<section class="section-padding">
    <div class="section-title">
        <h2>الأقسام</h2>
    </div>
    <div class="row g-4">
        @foreach($categories as $category)
        <div class="col-md-6 col-lg-3 rounded-2xl">
            <a href="/categories/{{ $category['slug'] }}" class="text-decoration-none rounded">
                <div class="card border-0 h-100">
                    <div class="rounded"
                        style="background: linear-gradient(135deg, {{ $category['color1'] }} 0%, {{ $category['color2'] }} 100%); height: 150px; display: flex; align-items: center; justify-content: center;">
                        <div class="text-center text-white">
                            <i class="ri {{ $category['icon'] }} icon-48px mb-3"></i>
                            <h5 class="text-black fw-bolder">{{ $category['name'] }}</h5>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <small class="text-muted">{{ $category['count'] }} أخبار</small>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>

<!-- Trending News -->
<section class="section-padding">
    <div class="section-title">
        <h2>الأخبار الرائجة</h2>
    </div>
    <div class="row g-4">
        @foreach($trendingNews as $news)
        <div class="col-md-6">
            <div class="card mb-3 rounded-start-5" style="flex-direction: row;">
                <img src="{{ $news['image'] }}" class="rounded-start-5"
                    style="width: 200px; height: 200px; object-fit: cover;">
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge badge-primary">{{ $news['category'] }}</span>
                        <small class="text-muted ms-2">
                            <i class="fas fa-fire"></i> {{ $news['views'] }} مشاهدة
                        </small>
                    </div>
                    <h5 class="card-title">{{ $news['title'] }}</h5>
                    <p class="card-text text-muted">{{ str()->limit($news['description'], 100) }}</p>
                    <a href="/news/{{ $news['id'] }}" class="btn btn-primary btn-sm">اقرأ المزيد</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Latest Gallery Images -->
<section class="section-padding">
    <div class="section-title d-flex justify-content-between align-items-center">
        <h2>أحدث الصور</h2>
        <a href="{{ url('/gallery') }}" class="btn btn-outline-primary btn-sm">عرض الكل</a>
    </div>
    <div class="row g-4">
        @foreach($galleryImages as $image)
        <div class="col-md-6 col-lg-3">
            <a href="{{ $image['full'] }}" data-lightbox="gallery-home" data-title="{{ $image['title'] }}">
                <div class="card h-100">
                    <img src="{{ $image['thumbnail'] }}" class="card-img-top" alt="{{ $image['title'] }}"
                        style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title">{{ str()->limit($image['title'], 30) }}</h6>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>

<!-- Latest Videos -->
<section class="section-padding">
    <div class="section-title d-flex justify-content-between align-items-center">
        <h2>أحدث الفيديوهات</h2>
        <a href="{{ url('/videos') }}" class="btn btn-outline-primary btn-sm">عرض الكل</a>
    </div>
    <div class="row g-4">
        @foreach($videos as $video)
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 video-card" data-video-id="{{ $video['video_id'] }}"
                data-video-title="{{ $video['title'] }}">
                <img src="{{ $video['thumbnail'] }}" class="card-img-top" alt="{{ $video['title'] }}"
                    style="height: 150px; object-fit: cover;">
                <div class="card-body">
                    <h6 class="card-title">{{ str()->limit($video['title'], 40) }}</h6>
                </div>
                <div class="play-icon-overlay">
                    <i class="fas fa-play-circle fa-4x text-white"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Subscribe Newsletter -->
<section class="section-padding">
    <div class="text-center">
        <h2 class="mb-3 text-primary">اشترك في النشرة البريدية</h2>
        <p class="mb-4">تابع أحدث الأخبار مباشرة في بريدك الإلكتروني</p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form class="input-group">
                    <input type="email" class="form-control text-white" placeholder="أدخل بريدك الإلكتروني" required>
                    <button class="btn btn-light z-0" type="submit">اشترك الآن</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection