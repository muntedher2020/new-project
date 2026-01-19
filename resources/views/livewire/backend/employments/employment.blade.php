<style>
    .card {
        transition: transform 0.3s ease;
        border-radius: 10px;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-title {
        min-height: 60px;
    }
    
    .disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

<div class="container py-5" style="margin-top: 70px!important">
    <!-- شريط البحث -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <div class="input-group">
                <input type="text" 
                       class="form-control" 
                       placeholder="ابحث عن استمارة..." 
                       wire:model.live.debounce.300ms="search">
                <span class="input-group-text">
                    <i class="ri ri-search-line"></i>
                </span>
            </div>
        </div>
    </div>
    
    <!-- البطاقات -->
    <div class="row g-4">
        @forelse($forms as $form)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border border-primary">
                    <div class="card-body">
                        <!-- حالة الاستمارة -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-{{ $form->isOpen() ? 'success' : 'secondary' }}">
                                {{ $form->isOpen() ? 'مفتوحة' : 'مغلقة' }}
                            </span>
                            @if($form->form_type)
                                <span class="badge bg-info">
                                    {{ $form->form_type }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- العنوان -->
                        <h5 class="card-title text-primary mb-3">
                            {{ $form->title }}
                        </h5>
                        
                        <!-- الوصف -->
                        <p class="card-text text-muted small mb-3">
                            {{ str()->limit($form->description, 100) }}
                        </p>
                    </div>
                    
                    <!-- تذييل البطاقة -->
                    <div class="card-footer bg-transparent border-top-0 text-muted small">
                        <!-- المعلومات -->
                        <div class="small text-muted mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-calendar me-2"></i>
                                @if($form->start_date && $form->end_date)
                                    <span class="number">{{ $form->start_date->format('Y-m-d') }}</span>  <span class="number"> - {{ $form->end_date->format('Y-m-d') }}</span>
                                @elseif($form->start_date)
                                    يبدأ: {{ $form->start_date->format('Y-m-d') }}
                                @else
                                    مفتوحة دائمًا
                                @endif
                            </div>
                            
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-users me-2"></i>
                                <span class="number me-2">{{ $form->responses()->count() }}</span> إجابة
                                @if($form->max_responses)
                                    / <span class="number me-2">{{ $form->max_responses }}</span> حد أقصى
                                @endif
                            </div>
                            
                            @if($form->require_login)
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    يتطلب تسجيل دخول
                                </div>
                            @endif
                        </div>

                        <!-- زر التقديم -->
                        <div class="d-grid mb-3">
                            <a href="{{ route('forms.public.show', $form->slug) }}" 
                               class="btn btn-primary {{ !$form->isOpen() ? 'disabled' : '' }}">
                                <i class="fas fa-pen me-1"></i>
                                {{ $form->isOpen() ? 'تقديم الآن' : 'مغلقة' }}
                            </a>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>
                                {{ $form->created_at->diffForHumans() }}
                            </span>
                            <span>
                                <i class="ri ri-eye-line me-1 icon-base"></i>
                                عرض
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد استمارات متاحة</h5>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- الترقيم -->
    <div class="mt-4">
        {{ $forms->links() }}
    </div>
</div>
