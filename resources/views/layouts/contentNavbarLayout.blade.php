@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Str;

  $configData = Helper::appClasses();
@endphp

@extends('layouts/commonMaster')

@php
  /* Display elements */
  $contentNavbar = $contentNavbar ?? true;
  $containerNav = $containerNav ?? 'container-xxl';
  $isNavbar = $isNavbar ?? true;
  $isMenu = $isMenu ?? true;
  $isFlex = $isFlex ?? false;
  $isFooter = $isFooter ?? true;
  $customizerHidden = $customizerHidden ?? '';

  /* HTML Classes */
  $navbarDetached = 'navbar-detached';
  $menuFixed = isset($configData['menuFixed']) ? $configData['menuFixed'] : '';
  if (isset($navbarType)) {
  $configData['navbarType'] = $navbarType;
  }
  $navbarType = isset($configData['navbarType']) ? $configData['navbarType'] : '';
  $footerFixed = isset($configData['footerFixed']) ? $configData['footerFixed'] : '';
  $menuCollapsed = isset($configData['menuCollapsed']) ? $configData['menuCollapsed'] : '';
  $isFront = ($isFront ?? '') == true ? 'Front' : '';
  /* Content classes */
  $container =
  isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact'
  ? 'container-xxl'
  : 'container-fluid';
@endphp

@section('layoutContent')
<div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
    <div class="layout-container">

        @if ($isMenu)
            <aside id="layout-menu" class="layout-menu menu-vertical menu"
                @foreach ($configData['menuAttributes'] as $attribute => $value)
                {{ $attribute }}="{{ $value }}" @endforeach>

                <!-- ! Hide app brand if navbar-full -->
                @if (!isset($navbarFull))
                    <div class="app-brand demo">
                        <a href="{{ url('/') }}" class="app-brand-link gap-xl-0 gap-2">
                          <span class="app-brand-logo demo">@include('_partials.macros')</span>
                          <span class="app-brand-text demo menu-text fw-semibold ms-2">{{ config('variables.templateName') }}</span>
                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path
                                  d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z"
                                  fill-opacity="0.9" />
                                <path
                                  d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z"
                                  fill-opacity="0.4" />
                            </svg>
                        </a>
                    </div>
                @endif

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    {{-- Dashboard --}}
                    <li class="menu-item {{ request()->is('Backend/Dashboard') ? 'active' : '' }}">
                        <a href="{{ Route('dashboard') }}" class="menu-link">
                            <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                            <div class="fw-normal">لوحة المتابعة</div>
                        </a>
                    </li>

                    {{-- الاستمارات الالكترونية --}}
                    <li class="menu-item {{ request()->is('Backend/Electronic-Forms') ? 'active' : '' }}">
                        <a href="{{ Route('ElectronicForms') }}" class="menu-link">
                            <i class="menu-icon icon-base ri ri-news-line"></i>
                            <div class="fw-normal">الاستمارات الالكترونية</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('Backend/Electronic-Forms/*/fields') ? 'active' : '' }}">
                        <a href="{{ Route('ElectronicForms') }}" class="menu-link {{ request()->is('Backend/Electronic-Forms/*/fields') ? '' : 'd-none' }}">
                            <i class="menu-icon icon-base ri ri-text-block"></i>
                            <div class="fw-normal">إدارة حقول الاستمارة</div>
                        </a>
                    </li>
                    {{-- الاستمارات الالكترونية --}}

                    {{-- المستخدمين --}}
                    @can('users')
                        <li class="menu-item {{ request()->is('User-Accounts') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class='menu-icon icon-base ri ri-user-line'></i>
                                <span class="menu-title fw-normal">حسابات المستخدمين</span>
                            </a>
                            <ul class="menu-sub">
                                @role(['OWNER', 'Admin'])
                                  <li class="menu-item {{ request()->is('User-Accounts') ? 'active' : '' }}">
                                      <a href="{{ Route('User-Accounts.index') }}" class="menu-link">
                                          <i class=""></i>
                                          <div class="fw-normal">المستخدمين</div>
                                      </a>
                                  </li>
                                @endrole
                            </ul>
                        </li>
                    @endcan

                    {{-- التصاريح والأدوار --}}
                    @if (Auth::check())
                        {{-- @can ('roles-permissions') --}}
                            <li class="menu-item {{ request()->is('Permissions&Roles/Permissions', 'Permissions&Roles/Roles') ? 'active open' : '' }}">
                                <a href="javascript:void(0);" class="menu-link menu-toggle">
                                    <i class='menu-icon icon-base ri ri-shield-user-line'></i>
                                    <span class="menu-title fw-normal">التصاريح والأدوار</span>
                                </a>
                                <ul class="menu-sub">
                                    {{-- @can ('permissions') --}}
                                        <li class="menu-item {{ request()->is('Permissions&Roles/Permissions') ? 'active' : '' }}">
                                            <a href="{{ Route('Permissions.index') }}" class="menu-link">
                                                <i class=""></i>
                                                <div class="fw-normal">التصاريح</div>
                                            </a>
                                        </li>
                                    {{-- @endcan --}}
                                    {{-- @can ('roles') --}}
                                        <li class="menu-item {{ request()->is('Permissions&Roles/Roles') ? 'active' : '' }}">
                                            <a href="{{ Route('Roles.index') }}" class="menu-link">
                                                <i class=""></i>
                                                <div class="fw-normal">الأدوار</div>
                                            </a>
                                        </li>
                                    {{-- @endcan --}}
                                </ul>
                            </li>
                        {{-- @endcan --}}
                    @endif
                </ul>
            </aside>
            {{-- @include('layouts/sections/menu/verticalMenu') --}}
        @endif


        <!-- Layout page -->
        <div class="layout-page">

            {{-- Below commented code read by artisan command while installing jetstream. !! Do not remove if you want
            to use jetstream. --}}
            {{--
            <x-banner /> --}}

            <!-- BEGIN: Navbar-->
            @if ($isNavbar)
            @include('layouts/sections/navbar/navbar')
            @endif
            <!-- END: Navbar-->


            <!-- Content wrapper -->
            <div class="content-wrapper">

                <!-- Content -->
                @if ($isFlex)
                <div class="{{ $container }} d-flex align-items-stretch flex-grow-1 p-0">
                    @else
                    <div class="{{ $container }} flex-grow-1 container-p-y">
                        @endif

                        @yield('content')

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @if ($isFooter)
                      @include('layouts/sections/footer/footer' . $isFront)
                    @endif
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!--/ Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        @if ($isMenu)
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        @endif
        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    @endsection
