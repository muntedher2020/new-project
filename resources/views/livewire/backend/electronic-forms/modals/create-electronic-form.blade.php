<div>
    <!-- Modal -->
    <div class="modal fade" wire:ignore.self id="createFormModal" tabindex="-1" aria-hidden="true"
         wire:model="isOpen">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إنشاء استمارة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" 
                            aria-label="Close" wire:click="close"></button>
                </div>
                <form {{-- wire:submit.prevent="save" --}} class="row">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">عنوان الاستمارة *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" wire:model="title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">وصف الاستمارة</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" wire:model="description" rows="3"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="form_type" class="form-label">نوع الاستمارة</label>
                                <select class="form-select @error('form_type') is-invalid @enderror" 
                                    id="form_type" wire:model.live="form_type">
                                    @foreach($formTypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">اختيار نوع محدد سيضيف حقولاً افتراضية</small>
                                @error('form_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="max_responses" class="form-label">الحد الأقصى للإجابات</label>
                                <input type="number" class="form-control @error('max_responses') is-invalid @enderror" 
                                       id="max_responses" wire:model="max_responses" min="1">
                                @error('max_responses')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">تاريخ البدء</label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" wire:model="start_date">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">تاريخ الانتهاء</label>
                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" wire:model="end_date">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="active" wire:model="active">
                                    <label class="form-check-label" for="active">تفعيل فوري</label>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="require_login" wire:model="require_login">
                                    <label class="form-check-label" for="require_login">يتطلب تسجيل دخول</label>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="allow_multiple" wire:model="allow_multiple">
                                    <label class="form-check-label" for="allow_multiple">السماح بتقديمات متعددة</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- معلومات حول الأنواع -->
                        @if($form_type !== 'custom')
                            <div class="alert alert-info mt-3">
                                <i class="mdi mdi-information-outline"></i>
                                <strong>حقول افتراضية:</strong> 
                                @if($form_type === 'job_application')
                                    ستتم إضافة حقول: الاسم الكامل، البريد الإلكتروني، رقم الهاتف، المؤهل العلمي، الخبرات، السيرة الذاتية
                                @elseif($form_type === 'contact_form')
                                    ستتم إضافة حقول: الاسم، البريد الإلكتروني، رقم الهاتف، الموضوع، الرسالة
                                @elseif($form_type === 'survey')
                                    ستتم إضافة حقول: العمر، الجنس، المستوى التعليمي، مستوى الرضا، ملاحظات
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" type="reset" data-bs-dismiss="modal"
							              aria-label="Close" wire:loading.attr="disabled">
                            إلغاء
                        </button>
                        <button wire:click="save" class="btn btn-primary"
                                wire:loading.attr="disabled" type="button">
                            <span wire:loading.remove wire:target="save">حفظ</span>
                            <span wire:loading wire:target="save">
                              <div class="d-flex items-center">
                                <i class="ri ri-loader-2-fill icon-20px icon-spin "></i> جاري الحفظ... 
                              </div>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>











<!-- Add ElectronicForm Modal -->
{{-- <div wire:ignore.self class="modal fade" id="addelectronicformModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="p-4 modal-content p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="mb-4 text-center mt-n5">
                    <div class="text-center">
                        <h3 class="fw-bold mb-2">
                            <span class="text-primary">اضافة</span> الاستمارات الالكترونية جديد
                        </h3>
                        <p class="text-muted">
                            <i class="mdi mdi-cog me-1"></i>
                            قم بإدخال تفاصيل الاستمارات الالكترونية في النموذج أدناه
                        </p>
                    </div>
                </div>
                <hr class="my-3">
                <div wire:loading.remove wire:target="store, GetElectronicForm">
                    <form id="addelectronicformModalForm" autocomplete="off">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input wire:model.defer='title' type="text"
                                                id="modalElectronicFormtitle" placeholder="العنوان"
                                                class="form-control @error('title') is-invalid is-filled @enderror"/>
                                            <label for="modalElectronicFormtitle">العنوان</label>
                                        </div>
                                        @error('title')
                                            <small class='text-danger inputerror'> {{ $message }} </small>
                                        @enderror
                                    </div>
                            <div class="mb-3 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input wire:model.defer='description' type="text"
                                                id="modalElectronicFormdescription" placeholder="الوصف"
                                                class="form-control @error('description') is-invalid is-filled @enderror"/>
                                            <label for="modalElectronicFormdescription">الوصف</label>
                                        </div>
                                        @error('description')
                                            <small class='text-danger inputerror'> {{ $message }} </small>
                                        @enderror
                                    </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col">
                                    <div class="form-check form-switch">
                                        <input wire:model.defer='active' type="checkbox"
                                            id="modalElectronicFormactive" value="1"
                                            class="form-check-input @error('active') is-invalid @enderror"/>
                                        <label class="form-check-label" for="modalElectronicFormactive">مفعل</label>
                                    </div>
                                    @error('active')
                                        <small class='text-danger inputerror'> {{ $message }} </small>
                                    @enderror
                                </div>
                        </div>
                        <hr class="my-0">
                        <div class="text-center col-12 demo-vertical-spacing mb-n4">
                            <button wire:click='store' wire:loading.attr="disabled" type="button"
                                class="btn btn-primary me-sm-3 me-1">اضافة</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">تجاهل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!--/ Add ElectronicForm Modal -->
