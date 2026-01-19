@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'نتائج الاستمارة - ' . $form->title)
@section('page-title', 'نتائج: ' . $form->title)

@section('vendor-style')
@vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('content')

<div class="mt-n3">

  <!-- الجدول الرئيسي -->
  <div class="card shadow-sm">
    <div class="card-body">
      @livewire('backend.electronic-forms.form-responses', ['formId' => $form->id], key('form-responses-' .
      $form->id))
    </div>
  </div>
</div>

@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/extended-ui-sweetalert2.js'])
@endsection
