<div>
    @can('electronicform-view')
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="w-50">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-custom-icon">
                                <li class="breadcrumb-item">
                                    <a href="javascript:void(0);" class="fs-5">لوحة المتابعة</a>
                                    <i class="breadcrumb-icon icon-base ri ri-arrow-right-s-line align-middle"></i>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="javascript:void(0);" class="text-primary fs-5">الاستمارات الالكترونية</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <div class="d-flex gap-2">
                            <!-- Unified Dropdown for Export/Print options -->
                            @if(auth()->user()->can('electronicform-export-excel') || auth()->user()->can('electronicform-export-pdf'))
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-download me-1"></i>
                                        تصدير / طباعة
                                    </button>
                                    <ul class="dropdown-menu">
                                        @can('electronicform-export-excel')
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   wire:click="exportSelected"
                                                   style="{{ count($selectedRows) > 0 ? '' : 'opacity: 0.5; cursor: not-allowed;' }}">
                                                    <i class="mdi mdi-file-excel me-2 text-success"></i>
                                                    تصدير Excel
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                        @endcan
                                        @can('electronicform-export-pdf')
                                            <li>
                                                <a class="dropdown-item" href="{{ route('ElectronicForms.export.pdf.tcpdf') }}">
                                                    <i class="mdi mdi-file-pdf-box me-2 text-danger"></i>
                                                    تصدير PDF (TCPDF)
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('ElectronicForms.print.view') }}" target="_blank">
                                                    <i class="mdi mdi-printer me-2 text-info"></i>
                                                    طباعة مباشرة
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            @endif
                            
                            @if(count($selectedRows) > 0)
                                <button wire:click="deleteSelected" 
                                        wire:confirm="هل أنت متأكد من حذف الاستمارات المحددة؟"
                                        class="btn btn-danger">
                                    <i class="mdi mdi-delete me-1"></i>
                                    حذف المحدد ({{ count($selectedRows) }})
                                </button>
                            @endif
                            
                            @can('electronicform-create')
                                <button {{-- wire:click="$dispatch('openModal', { component: 'electronic-forms.create-electronic-form' })"  --}}
                                        data-bs-toggle="modal"
                                        data-bs-target="#createFormModal"
                                        class="mb-3 add-new btn btn-primary mb-md-0">
                                    <i class="mdi mdi-plus me-1"></i>أضــافــة
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            
            @can('electronicform-list')
                <!-- إحصائيات -->
                <div class="row p-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1 text-white">الاستمارات النشطة</h6>
                                        <h3 class="mb-0 text-white">{{ $totalActive }}</h3>
                                    </div>
                                    <i class="ri ri-toggle-line fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1 text-white">إجمالي التقديمات</h6>
                                        <h3 class="mb-0 text-white">{{ $totalResponses }}</h3>
                                    </div>
                                    <i class="ri ri-inbox-fill fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1 text-white">الاستمارات غير النشطة</h6>
                                        <h3 class="mb-0 text-white">{{ $totalInactive }}</h3>
                                    </div>
                                    <i class="ri ri-toggle-fill fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1 text-white">إجمالي الاستمارات</h6>
                                        <h3 class="mb-0 text-white">{{ $totalForms }}</h3>
                                    </div>
                                    <i class="ri ri-file-text-fill fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- البحث والفلتر -->
                <div class="px-4 pb-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" 
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control" 
                                   placeholder="ابحث بالعنوان أو الوصف...">
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="1">نشطة</option>
                                <option value="0">غير نشطة</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="perPage" class="form-select">
                                <option value="10">10 لكل صفحة</option>
                                <option value="20">20 لكل صفحة</option>
                                <option value="50">50 لكل صفحة</option>
                                <option value="100">100 لكل صفحة</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button wire:click="$set('search', '')" 
                                    wire:loading.attr="disabled"
                                    class="btn btn-outline-secondary w-100">
                                <i class="mdi mdi-refresh"></i> إعادة تعيين
                            </button>
                        </div>
                    </div>
                </div>

                <!-- جدول الاستمارات -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               wire:model="selectAll">
                                    </div>
                                </th>
                                <th wire:click="sortBy('title')" style="cursor: pointer;">
                                    العنوان
                                    @if($sortField === 'title')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>النوع</th>
                                <th>الحقول</th>
                                <th>الإجابات</th>
                                <th wire:click="sortBy('active')" style="cursor: pointer;">
                                    الحالة
                                    @if($sortField === 'active')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                    تاريخ البداية <br> تاريخ النهاية
                                    @if($sortField === 'created_at')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($forms as $form)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   class="form-check-input" 
                                                   wire:model="selectedRows"
                                                   value="{{ $form->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $form->title }}</strong>
                                            @if($form->description)
                                                <p class="text-muted mb-0 small">{{ str()->limit($form->description, 100) }}</p>
                                            @endif
                                            <small class="text-muted">
                                                <i class="mdi mdi-link"></i>
                                                <a href="{{ route('forms.public.show', $form->slug) }}" 
                                                   target="_blank" class="text-decoration-none">
                                                    عرض الاستمارة
                                                </a>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-black">
                                            {{ $formTypes[$form->form_type] ?? 'مخصص' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <i class="mdi mdi-list-box"></i> {{ $form->fields_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <i class="mdi mdi-inbox"></i> {{ $form->responses_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch mt-3">
                                            <input type="checkbox" class="form-check-input" 
                                                   wire:change="toggleStatus({{ $form->id }})"
                                                   @checked($form->active)>
                                            <label class="form-check-label">
                                                @if($form->active)
                                                    <span class="text-success">نشطة</span>
                                                @else
                                                    <span class="text-danger">غير نشطة</span>
                                                @endif
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-nowrap">
                                        <span class="text-muted">
                                            <i class="ri ri-eye-line icon-base"></i> {{ $form->start_date->diffForHumans() }}
                                        </span>
                                        <br>
                                        <span class="text-muted">
                                            <i class="ri ri-eye-off-line icon-base"></i> {{ $form->end_date->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @can('electronicform-edit')
                                                <button wire:click="openModal({{ $form->id }})" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editFormModal"
                                                    class="btn btn-label-primary py-1 px-2">
                                                    <i class="ri ri-pencil-fill icon-20px"></i>
                                                </button>
                                                {{-- <button wire:click="$dispatch('openModal', { 
                                                      component: 'electronic-forms.edit-electronic-form', 
                                                      arguments: { form: {{ $form->id }} }
                                                    })" 
                                                    data-bs-toggle="modal" data-bs-target="#editFormModal"
                                                        class="btn btn-label-primary">
                                                    <i class="ri ri-pencil-fill icon-20px"></i>
                                                </button> --}}
                                            @endcan
                                            
                                            @can('form-field-manage')
                                                <a href="{{ route('forms.fields.manage', $form->id) }}" 
                                                   class="btn btn-label-info py-1 px-2">
                                                    <i class="ri ri-text-block icon-20px"></i>
                                                </a>
                                            @endcan
                                            
                                            @can('electronicform-delete')
                                                <button wire:click="confirmDelete({{ $form->id }}, '{{ addslashes($form->title) }}')" 
                                                        {{-- wire:confirm="هل أنت متأكد من حذف الاستمارة '{{ $form->title }}'؟" --}}
                                                        class="btn btn-label-danger py-1 px-2">
                                                    <i class="ri ri-delete-bin-fill icon-20px"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri ri-inbox-fill fs-1"></i>
                                            <p class="mt-2">لا توجد استمارات لعرضها</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- التقسيم -->
                @if($forms->hasPages())
                    <div class="px-4 pb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                عرض {{ $forms->firstItem() }} إلى {{ $forms->lastItem() }} من إجمالي {{ $forms->total() }}
                            </div>
                            <div>
                                {{ $forms->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @endcan
        </div>
    @else
        <div class="container-xxl">
            <div class="misc-wrapper">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="mdi mdi-shield-lock-outline text-primary fs-1" style="opacity: 0.9;"></i>
                        </div>
                        <h2 class="mb-3 fw-semibold">عذراً! ليس لديك صلاحيات الوصول</h2>
                        <p class="mb-4 mx-auto text-muted" style="max-width: 500px;">
                            لا تملك الصلاحيات الكافية للوصول إلى هذه الصفحة. يرجى التواصل مع مدير النظام للحصول على المساعدة.
                        </p>
                        <a href="{{ route('dashboard') }}"
                            class="btn btn-primary btn-lg rounded-pill px-5 waves-effect waves-light">
                            <i class="mdi mdi-home-outline me-1"></i>
                            العودة إلى الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <!-- Modals سيتم تحميلها ديناميكياً -->
    @include('livewire.backend.electronic-forms.modals.create-electronic-form')
    @include('livewire.backend.electronic-forms.modals.edit-electronic-form')
    {{-- @livewire('backend.electronic-forms.create-electronic-form')
    @livewire('backend.electronic-forms.edit-electronic-form') --}}

    <!-- Modal حذف استمارة واحدة -->
    {{-- <div class="modal fade" wire:ignore.self id="deleteFormModal" tabindex="-1" 
         aria-labelledby="deleteFormModalLabel" aria-hidden="true"
         wire:model="showDeleteModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteFormModalLabel">
                        <i class="mdi mdi-alert-circle-outline me-2"></i>
                        تأكيد الحذف
                    </h5>
                    <button type="button" class="btn-close" 
                            wire:click="closeDeleteModal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="mdi mdi-trash-can-outline text-danger fs-1"></i>
                    </div>
                    <h4 class="mb-3">هل أنت متأكد من حذف الاستمارة؟</h4>
                    <p class="text-muted mb-4">
                        سيتم حذف الاستمارة "<strong>{{ $deleteFormTitle }}</strong>" بشكل دائم.
                    </p>
                    <div class="alert alert-warning mb-0">
                        <i class="mdi mdi-alert me-2"></i>
                        <small>هذا الإجراء لا يمكن التراجع عنه. سيتم حذف جميع الحقول والإجابات المرتبطة بهذه الاستمارة.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" 
                            wire:click="closeDeleteModal"
                            wire:loading.attr="disabled">
                        إلغاء
                    </button>
                    <button type="button" class="btn btn-danger" 
                            wire:click="deleteForm"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>نعم، احذف</span>
                        <span wire:loading>
                            <i class="mdi mdi-loading mdi-spin me-1"></i> جاري الحذف...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Modal حذف جماعي -->
    {{-- <div class="modal fade" wire:ignore.self id="bulkDeleteModal" tabindex="-1" 
         aria-labelledby="bulkDeleteModalLabel" aria-hidden="true"
         wire:model="showBulkDeleteModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="bulkDeleteModalLabel">
                        <i class="mdi mdi-alert-circle-outline me-2"></i>
                        تأكيد الحذف الجماعي
                    </h5>
                    <button type="button" class="btn-close" 
                            wire:click="closeBulkDeleteModal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="mdi mdi-trash-can-multiple text-danger fs-1"></i>
                    </div>
                    <h4 class="mb-3">هل أنت متأكد من حذف الاستمارات المحددة؟</h4>
                    <p class="text-muted mb-4">
                        سيتم حذف <strong>{{ count($selectedRows) }}</strong> استمارة بشكل دائم.
                    </p>
                    <div class="alert alert-warning mb-0">
                        <i class="mdi mdi-alert me-2"></i>
                        <small>هذا الإجراء لا يمكن التراجع عنه. سيتم حذف جميع الحقول والإجابات المرتبطة بهذه الاستمارات.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" 
                            wire:click="closeBulkDeleteModal"
                            wire:loading.attr="disabled">
                        إلغاء
                    </button>
                    <button type="button" class="btn btn-danger" 
                            wire:click="deleteSelected"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>نعم، احذف الكل</span>
                        <span wire:loading>
                            <i class="mdi mdi-loading mdi-spin me-1"></i> جاري الحذف...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

    
</div>
