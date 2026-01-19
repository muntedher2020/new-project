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
  <div class="d-flex justify-content-between align-items-center mb-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-custom-icon">
        <li class="breadcrumb-item">
          <a href="{{ route('dashboard') }}">
            <span class="text-muted fw-light fs-5">لوحة التحكم</span>
          </a>
          <i class="breadcrumb-icon icon-base ri ri-arrow-right-s-line icon-22px align-middle"></i>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('ElectronicForms') }}">
            <span class="text-muted fw-light fs-5">الاستمارات الإلكترونية</span>
          </a>
          <i class="breadcrumb-icon icon-base ri ri-arrow-right-s-line icon-22px align-middle"></i>
        </li>
        <li class="breadcrumb-item">
          <a href="javascript:void(0);">نتائج الاستمارة</a>
        </li>
      </ol>
    </nav>
    <div class="d-flex gap-2">
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="ri ri-download-cloud-line me-1"></i>
          تصدير / طباعة
        </button>
        <ul class="dropdown-menu">
          <li>
            <a class="dropdown-item" href="{{ route('form-responses.export.pdf.tcpdf', $form->id) }}">
              <i class="ri ri-file-pdf-line me-2 text-danger"></i>
              تصدير PDF
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('form-responses.print.view', $form->id) }}" target="_blank">
              <i class="ri ri-printer-line me-2"></i>
              طباعة مباشرة
            </a>
          </li>
        </ul>
      </div>

      <a href="{{ route('ElectronicForms') }}" class="btn btn-label-secondary waves-effect">
        <i class="ri ri-arrow-left-line me-1"></i>
        رجوع
      </a>
    </div>
  </div>

  <div class="container-fluid">
    <!-- الإحصائيات -->
    <div class="row mb-4">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-start border-primary border-3 h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted fw-medium mb-1">إجمالي الإجابات</p>
                <h3 class="text-primary mb-0" wire:model="totalResponses">
                  <i class="ri ri-loader-4-line ri-spin" style="display:none;" wire:loading></i>
                  <span wire:loading.remove>{{ $totalResponses ?? 0 }}</span>
                </h3>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="ri ri-file-list-2-line icon-24px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-start border-warning border-3 h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted fw-medium mb-1">قيد المراجعة</p>
                <h3 class="text-warning mb-0" wire:model="totalPending">
                  <i class="ri ri-loader-4-line ri-spin" style="display:none;" wire:loading></i>
                  <span wire:loading.remove>{{ $totalPending ?? 0 }}</span>
                </h3>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="ri ri-time-line icon-24px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-start border-success border-3 h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted fw-medium mb-1">موافق عليها</p>
                <h3 class="text-success mb-0" wire:model="totalApproved">
                  <i class="ri ri-loader-4-line ri-spin" style="display:none;" wire:loading></i>
                  <span wire:loading.remove>{{ $totalApproved ?? 0 }}</span>
                </h3>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-success">
                  <i class="ri ri-check-line icon-24px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-start border-danger border-3 h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted fw-medium mb-1">مرفوضة</p>
                <h3 class="text-danger mb-0" wire:model="totalRejected">
                  <i class="ri ri-loader-4-line ri-spin" style="display:none;" wire:loading></i>
                  <span wire:loading.remove>{{ $totalRejected ?? 0 }}</span>
                </h3>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-danger">
                  <i class="ri ri-close-line icon-24px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- الجدول الرئيسي -->
    <div class="card shadow-sm">
      <div class="card-body">
        <!-- Livewire Component -->
        @livewire('backend.electronic-forms.form-responses', ['formId' => $form->id], key('form-responses-' .
        $form->id))
      </div>
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