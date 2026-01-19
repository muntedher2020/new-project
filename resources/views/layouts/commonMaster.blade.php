<!DOCTYPE html>
@php
  use Illuminate\Support\Str;
  use App\Helpers\Helpers;
  use Illuminate\Support\Facades\Auth;

  $menuFixed =
      $configData['layout'] === 'vertical'
          ? $menuFixed ?? ''
          : ($configData['layout'] === 'front'
              ? ''
              : $configData['headerType']);
  $navbarType =
      $configData['layout'] === 'vertical'
          ? $configData['navbarType']
          : ($configData['layout'] === 'front'
              ? 'layout-navbar-fixed'
              : '');
  $isFront = ($isFront ?? '') == true ? 'Front' : '';
  $contentLayout = isset($container) ? ($container === 'container-xxl' ? 'layout-compact' : 'layout-wide') : '';

  // Get skin name from configData - only applies to admin layouts
  $isAdminLayout = !Str::contains($configData['layout'] ?? '', 'front');
  $skinName = $isAdminLayout ? $configData['skinName'] ?? 'default' : 'default';

  // Get semiDark value from configData - only applies to admin layouts
  $semiDarkEnabled = $isAdminLayout && filter_var($configData['semiDark'] ?? false, FILTER_VALIDATE_BOOLEAN);

  // Generate primary color CSS if color is set
  $primaryColorCSS = '';
  if (isset($configData['color']) && $configData['color']) {
      $primaryColorCSS = Helpers::generatePrimaryColorCSS($configData['color']);
  }

@endphp

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}"
  class="{{ $navbarType ?? '' }} {{ $contentLayout ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}"
  dir="{{ $configData['textDirection'] }}" data-skin="{{ $skinName }}" data-assets-path="{{ asset('/assets') . '/' }}"
  data-base-url="{{ url('/') }}" data-framework="laravel" data-template="{{ $configData['layout'] }}-menu-template"
  data-bs-theme="{{ $configData['theme'] }}" @if ($isAdminLayout && $semiDarkEnabled) data-semidark-menu="true" @endif>

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>
    @yield('title') | {{ config('variables.templateName') ? config('variables.templateName') : 'TemplateName' }}
    - {{ config('variables.templateSuffix') ? config('variables.templateSuffix') : 'TemplateSuffix' }}
  </title>
  <meta name="description"
    content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
  <meta name="keywords"
    content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}" />
  <meta property="og:title" content="{{ config('variables.ogTitle') ? config('variables.ogTitle') : '' }}" />
  <meta property="og:type" content="{{ config('variables.ogType') ? config('variables.ogType') : '' }}" />
  <meta property="og:url" content="{{ config('variables.productPage') ? config('variables.productPage') : '' }}" />
  <meta property="og:image" content="{{ config('variables.ogImage') ? config('variables.ogImage') : '' }}" />
  <meta property="og:description"
    content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
  <meta property="og:site_name"
    content="{{ config('variables.creatorName') ? config('variables.creatorName') : '' }}" />
  <meta name="robots" content="noindex, nofollow" />
  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Canonical SEO -->
  <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}" />
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Include Styles -->
  <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/styles' . $isFront)

  @if (
      $primaryColorCSS &&
          (config('custom.custom.primaryColor') ||
              isset($_COOKIE['admin-primaryColor']) ||
              isset($_COOKIE['front-primaryColor'])))
    <!-- Primary Color Style -->
    <style id="primary-color-style">
      {!! $primaryColorCSS !!}
    </style>
  @endif

  <!-- Include Scripts for customizer, helper, analytics, config -->
  <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scriptsIncludes' . $isFront)


  <style>
    /* تنسيق Modals الحذف */
    .modal-danger .modal-header {
        background-color: #dc3545;
        color: white;
    }

    .modal-danger .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .modal-danger .modal-body {
        background-color: #f8f9fa;
    }

    .modal-danger .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    /* تأثيرات للزر الأحمر */
    .btn-delete {
        background: linear-gradient(45deg, #dc3545, #c82333);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: linear-gradient(45deg, #c82333, #bd2130);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    /* تنسيق رسائل التحذير */
    .alert-warning-delete {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        border-left: 4px solid #fdcb6e;
    }
    </style>
  @livewireStyles
  
</head>

<body>
  <!-- Layout Content -->
  @yield('layoutContent')
  <!--/ Layout Content -->

  <!-- Include Scripts -->
  <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scripts' . $isFront)

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
      document.addEventListener('livewire:initialized', () => {
          // تحديث قائمة الاستمارات
          Livewire.on('refreshForms', () => {
              Livewire.dispatch('refresh');
          });
      });
  </script>

  <script>
      document.addEventListener('livewire:initialized', () => {
          // عرض رسائل النجاح
          Livewire.on('showSuccess', (message) => {
              $('.modal').modal('hide'); // إغلاق أي مودال مفتوح  
              
              Swal.fire({
                  icon: 'success',
                  title: 'نجاح',
                  text: message,
                  timer: 3000,
                  showConfirmButton: false,
                  toast: true,
                  position: 'top-start',
              });
          });

          // عرض رسائل الخطأ
          Livewire.on('showError', (message) => {
              Swal.fire({
                  icon: 'error',
                  title: 'خطأ',
                  text: message,
                  timer: 4000,
                  toast: true,
                  position: 'top-start',
              });
          });
          
          Livewire.on('show-preview', (response) => {
              if (response.type === 'success') {
                  // عرض البيانات
                  let html = '<div dir="rtl" class="text-start">';
                  html += '<div class="alert alert-success">✅ ' + response.message + '</div>';
                  
                  if (response.data) {
                      html += '<h6 class="mt-3">البيانات المدخلة:</h6>';
                      html += '<table class="table table-sm">';
                      
                      Object.entries(response.data).forEach(([field, value]) => {
                          html += `<tr>
                              <td><strong>${field}</strong></td>
                              <td>${value || '<span class="text-muted">(فارغ)</span>'}</td>
                          </tr>`;
                      });
                      
                      html += '</table>';
                  }
                  
                  html += '</div>';
                  
                  Swal.fire({
                      title: 'البيانات صالحة',
                      html: html,
                      icon: 'success',
                      confirmButtonText: 'متابعة',
                  });
                  
              } else if (response.type === 'error') {
                  // عرض الأخطاء
                  let errorHtml = '<div dir="rtl" class="text-start">';
                  errorHtml += '<h6 class="text-danger">يوجد أخطاء:</h6>';
                  errorHtml += '<ul>';
                  
                  Object.entries(response.errors).forEach(([field, messages]) => {
                      messages.forEach(msg => {
                          errorHtml += `<li class="text-danger">${msg}</li>`;
                      });
                  });
                  
                  errorHtml += '</ul></div>';
                  
                  Swal.fire({
                      title: 'تنبيه',
                      html: errorHtml,
                      icon: 'error',
                      confirmButtonText: 'تصحيح',
                  });
              }
          });
      });
  </script>

  @livewireScripts
  
</body>

</html>
