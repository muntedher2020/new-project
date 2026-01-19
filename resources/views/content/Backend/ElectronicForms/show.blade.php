@php
$configData = Helper::appClasses();
@endphp

@extends('layouts.layoutFront')

@section('title', $form->title)

@section('content')

  @livewire('backend.employments.employment-form', ['form' => $form])

@endsection


<script>
$(document).ready(function() {
    // تحقق من النموذج
    $('#formResponse').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الإرسال...');
        submitBtn.prop('disabled', true);
        
        // استخدام FormData لدعم رفع الملفات
        const formData = new FormData(form[0]);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        window.location.reload();
                    }
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors);
                } else {
                    showError('حدث خطأ أثناء إرسال الاستمارة');
                }
            },
            complete: function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    // دوال مساعدة
    function showError(message) {
        const alert = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('.card-body').prepend(alert);
    }
    
    function showValidationErrors(errors) {
        let errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        errorHtml += '<i class="fas fa-exclamation-circle me-2"></i>';
        errorHtml += '<h6>يرجى تصحيح الأخطاء التالية:</h6><ul class="mb-0">';
        
        for (const field in errors) {
            errorHtml += `<li>${errors[field][0]}</li>`;
        }
        
        errorHtml += '</ul>';
        errorHtml += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        errorHtml += '</div>';
        
        $('.card-body').prepend(errorHtml);
    }
});
</script>
