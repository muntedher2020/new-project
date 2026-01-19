<!-- Footer: Start -->
<footer class=""  {{-- style="background-color: #0D2242" --}}>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h5>عن الموقع</h5>
                <p class="text-primary" style="line-height: 30px;">موقع أخبار مودرن وجميل يقدم أحدث الأخبار والمعلومات في مختلف المجالات.</p>
            </div>
            <div class="col-md-3">
                <h5 class="text-primary border-bottom border-primary w-75">الأقسام الرئيسية</h5>
                <ul class="list-unstyled">
                    <li class="mt-3"><a href="/categories/tech">التكنولوجيا</a></li>
                    <li class="mt-3"><a href="/categories/sports">الرياضة</a></li>
                    <li class="mt-3"><a href="/categories/politics">السياسة</a></li>
                    <li class="mt-3"><a href="/categories/business">الأعمال</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5 class="text-primary border-bottom border-primary w-75">روابط مهمة</h5>
                <ul class="list-unstyled">
                    <li class="mt-3"><a href="/about">من نحن</a></li>
                    <li class="mt-3"><a href="/contact">تواصل معنا</a></li>
                    <li class="mt-3"><a href="/privacy">سياسة الخصوصية</a></li>
                    <li class="mt-3"><a href="/terms">الشروط والأحكام</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5 class="text-primary border-bottom border-primary w-75">تابعنا</h5>
                <div class="social-links">
                    <a href="#" class="me-2"><i class="ri ri-facebook-fill icon-30px"></i></a>
                    <a href="#" class="me-2"><i class="ri ri-twitter-x-fill icon-30px"></i></a>
                    <a href="#" class="me-2"><i class="ri ri-instagram-fill icon-30px"></i></a>
                    <a href="#"><i class="ri ri-youtube-fill icon-30px"></i></a>
                    <a href="#"><i class="ri ri-whatsapp-fill icon-30px"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-3">
        <div class="text-center">
            <div class="d-flex align-items-center justify-content-evenly text-center">
              <div>
                <h3 class="font-extrabold img-theme-aware leading-tight hero-text-shadow text-center">
                  رئيس لجنة التنمية الاقتصادية والاستثمار
                  <p class="text-xl font-light max-w-2xl mx-auto">نحو مستقبل اقتصادي مستدام وبيئة استثمارية رائدة</p>
                </h3>
              </div>

              <div class="image-container">
                <img src="{{ asset('assets/img/personal/934e03ec-f1e7-44b9-8530-e40de41dcf96.png') }}" style="width: 300px;
                /* border-radius: 50%; */">

                {{-- <img src="{{ asset('assets/img/personal/IMG_0700 - Cop12121y.png') }}"
                  class="h-24 md:h-24 object-contain img-theme-aware mt-n4" style="width: 300px; "> --}}
              </div>
            </div>
            <hr class="my-3">
            <p class="text-white mt-4">&copy; 2024 موقع الأخبار - جميع الحقوق محفوظة</p>
        </div>
    </div>
</footer>



{{-- <footer class="landing-footer">
  <div class="footer-top position-relative overflow-hidden">
    <img src="{{asset('assets/img/front-pages/backgrounds/footer-bg.png')}}" alt="footer bg" class="footer-bg banner-bg-img" />
    <div class="container">
      <div class="row gx-0 gy-6 g-lg-10">
        <div class="col-lg-5">
          <a href="{{url('front-pages/landing')}}" class="app-brand-link mb-6">
            <span class="app-brand-logo demo">@include('_partials.macros')</span>
            <span class="app-brand-text demo text-white fw-semibold ms-2 ps-1">{{ config('variables.templateName') }}</span>
          </a>
          <p class="footer-text footer-logo-description mb-6">Most Powerful & Comprehensive 🤩 React NextJS Admin Template with Elegant Material Design & Unique Layouts.</p>
          <form class="footer-form">
            <div class="d-flex mt-2 gap-4">
              <div class="form-floating form-floating-outline w-px-250">
                <input type="text" class="form-control bg-transparent" id="newsletter-1" placeholder="Your email" />
                <label for="newsletter-1">Subscribe to newsletter</label>
              </div>
              <button type="submit" class="btn btn-primary">Subscribe</button>
            </div>
          </form>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <h6 class="footer-title mb-4 mb-lg-6">Demos</h6>
          <ul class="list-unstyled mb-0">
            <li class="mb-4">
              <a href="/demo-1" target="_blank" class="footer-link">Vertical Layout</a>
            </li>
            <li class="mb-4">
              <a href="/demo-5" target="_blank" class="footer-link">Horizontal Layout</a>
            </li>
            <li class="mb-4">
              <a href="/demo-2" target="_blank" class="footer-link">Bordered Layout</a>
            </li>
            <li class="mb-4">
              <a href="/demo-3" target="_blank" class="footer-link">Semi Dark Layout</a>
            </li>
            <li>
              <a href="/demo-4" target="_blank" class="footer-link">Dark Layout</a>
            </li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <h6 class="footer-title mb-4 mb-lg-6">Pages</h6>
          <ul class="list-unstyled mb-0">
            <li class="mb-4">
              <a href="{{url('/front-pages/pricing')}}" class="footer-link">Pricing</a>
            </li>
            <li class="mb-4">
              <a href="{{url('/front-pages/payment')}}" class="footer-link">Payment<span class="badge rounded-pill bg-primary ms-2">New</span></a>
            </li>
            <li class="mb-4">
              <a href="{{url('/front-pages/checkout')}}" class="footer-link">Checkout</a>
            </li>
            <li class="mb-4">
              <a href="{{url('/front-pages/help-center')}}" class="footer-link">Help Center</a>
            </li>
            <li>
              <a href="{{url('/auth/login-cover')}}" target="_blank" class="footer-link">Login/Register</a>
            </li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-4">
          <h6 class="footer-title mb-4 mb-lg-6">Download our app</h6>
          <a href="javascript:void(0);" class="d-block footer-link mb-4"><img src="{{asset('assets/img/front-pages/landing-page/apple-icon.png')}}" alt="apple icon" /></a>
          <a href="javascript:void(0);" class="d-block footer-link"><img src="{{asset('assets/img/front-pages/landing-page/google-play-icon.png')}}" alt="google play icon" /></a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom py-5">
    <div class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
      <div class="mb-2 mb-md-0">
        <span class="footer-bottom-text"
          >©
          <script>
            document.write(new Date().getFullYear());
          </script>
          , Made with <i class="icon-base ri ri-heart-fill text-danger"></i> by
        </span>
        <a href="{{config('variables.creatorUrl')}}" target="_blank" class="footer-link fw-medium footer-theme-link">{{config('variables.creatorName')}}</a>
      </div>
      <div>
        <a href="{{config('variables.githubFreeUrl')}}" class="footer-link me-4" target="_blank"><i class="icon-base ri ri-github-fill"></i></a>
        <a href="{{config('variables.facebookUrl')}}" class="footer-link me-4" target="_blank"><i class="icon-base ri ri-facebook-circle-fill"></i></a>
        <a href="{{config('variables.twitterUrl')}}" class="footer-link me-4" target="_blank"><i class="icon-base ri ri-twitter-x-fill"></i></a>
        <a href="{{config('variables.instagramUrl')}}" class="footer-link" target="_blank"><i class="icon-base ri ri-instagram-line"></i></a>
      </div>
    </div>
  </div>
</footer> --}}
<!-- Footer: End -->
