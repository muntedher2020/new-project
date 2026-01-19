@php
$configData = Helper::appClasses();
@endphp

{{-- @extends('layouts/layoutMaster') --}}

@extends('layouts.guest')

@section('title', 'شكراً لك')
@section('description', 'تم استلام استمارتك بنجاح')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    
                    <h1 class="mb-3">شكراً لك!</h1>
                    <h4 class="text-muted mb-4">تم استلام استمارتك بنجاح</h4>
                    
                    <p class="lead mb-4">
                        {{ $form->title }}
                    </p>
                    
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        تم حفظ إجاباتك في نظامنا وسيتم مراجعتها من قبل المسؤولين.
                    </div>
                    
                    <div class="d-grid gap-2 col-lg-6 mx-auto">
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>العودة للرئيسية
                        </a>
                        <a href="{{ route('forms.public.show', $form->slug) }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>تقديم إجابة أخرى
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection