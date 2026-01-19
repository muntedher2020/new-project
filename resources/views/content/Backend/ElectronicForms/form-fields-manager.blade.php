@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'الاستمارات الإلكترونية')
@section('page-title', 'إدارة حقول الاستمارات الإلكترونية')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/nouislider/nouislider.scss', 'resources/assets/vendor/libs/swiper/swiper.scss'])
  @vite(['resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Page Styles -->
@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/front-page-landing.scss'])
@endsection

@section('content')

    @livewire('backend.electronic-forms.form-fields-manager', ['formId' => $formId])

@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/nouislider/nouislider.js', 'resources/assets/vendor/libs/swiper/swiper.js'])
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
    @vite(['resources/assets/vendor/libs/sortablejs/sortable.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/front-page-landing.js'])
    @vite(['resources/assets/js/extended-ui-sweetalert2.js'])
    @vite(['resources/assets/js/extended-ui-drag-and-drop.js'])

    <script>
        function closeForm() {
            $('.modal-backdrop.fade.show').remove();
        }
    </script>
@endsection
{{-- 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 --}}