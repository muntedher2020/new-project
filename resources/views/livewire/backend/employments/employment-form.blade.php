{{-- <div class="container py-5 mt-5" style="margin-top: 70px!important;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 text-center">{{ $form->title }}</h3>
                    @if($form->description)
                    <p class="mb-0 text-center text-white-50">{{ $form->description }}</p>
                    @endif
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(!$form->isOpen())
                    <div class="alert alert-warning text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <h4>هذه الاستمارة مغلقة حالياً</h4>
                        <p class="mb-0">لا يمكن تقديم إجابات على هذه الاستمارة في الوقت الحالي.</p>
                    </div>
                    @else
                    <form id="formResponse" action="{{ route('forms.public.submit', $form->slug) }}"
                          method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        @foreach($form->fields as $field)
                        <div class="mb-4">
                            <label for="{{ $field->name }}" class="form-label">
                                {{ $field->label }}
                                @if($field->required)
                                <span class="text-danger">*</span>
                                @endif
                            </label>

                            @switch($field->type)
                                @case('textarea')
                                    <textarea class="form-control" id="{{ $field->name }}"
                                              name="{{ $field->name }}"
                                              rows="{{ $field->settings['rows'] ?? 3 }}"
                                              placeholder="{{ $field->placeholder }}"
                                              {{ $field->required ? 'required' : '' }}></textarea>
                                    @break

                                @case('select')
                                    <select class="form-select" id="{{ $field->name }}"
                                            name="{{ $field->name }}"
                                            {{ $field->required ? 'required' : '' }}>
                                        <option value="">اختر {{ $field->label }}</option>
                                        @foreach($field->options as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    @break

                                @case('radio')
                                    <div>
                                        @foreach($field->options as $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="{{ $field->name }}"
                                                   id="{{ $field->name }}_{{ $loop->index }}"
                                                   value="{{ $option }}"
                                                   {{ $field->required ? 'required' : '' }}>
                                            <label class="form-check-label" for="{{ $field->name }}_{{ $loop->index }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case('checkbox')
                                    <div>
                                        @foreach($field->options as $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="{{ $field->name }}[]"
                                                   id="{{ $field->name }}_{{ $loop->index }}"
                                                   value="{{ $option }}">
                                            <label class="form-check-label" for="{{ $field->name }}_{{ $loop->index }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case('file')
                                    <input type="file" class="form-control" id="{{ $field->name }}"
                                           name="{{ $field->name }}"
                                           accept="{{ $field->settings['accept'] ?? '*' }}"
                                           {{ $field->required ? 'required' : '' }}>
                                    <div class="form-text">
                                        @if($field->settings && isset($field->settings['max_size']))
                                        الحد الأقصى لحجم الملف: {{ $field->settings['max_size'] }} ميجابايت
                                        @else
                                        الحد الأقصى لحجم الملف: 5 ميجابايت
                                        @endif
                                    </div>
                                    @break

                                @default
                                    <input type="{{ $field->type }}" class="form-control" id="{{ $field->name }}"
                                           name="{{ $field->name }}"
                                           placeholder="{{ $field->placeholder }}"
                                           {{ $field->required ? 'required' : '' }}>
                            @endswitch

                            @if($field->placeholder && $field->type != 'text' && $field->type != 'textarea')
                            <div class="form-text">{{ $field->placeholder }}</div>
                            @endif
                        </div>
                        @endforeach

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>إرسال الاستمارة
                            </button>
                        </div>
                    </form>
                    @endif
                </div>

                <div class="card-footer text-muted text-center">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        جميع البيانات المقدمة محمية ولا يتم مشاركتها مع أطراف ثالثة
                    </small>
                </div>
            </div>
        </div>
    </div>
</div> --}}


{{-- resources/views/livewire/form/form-viewer.blade.php --}}
<div class="container p-5 mt-5" style="margin-top: 70px!important;">
  <div class="row justify-content-center">
    <div class="col-lg-8">
        @if($success)
        <div class="container py-5">
            <div class="alert alert-success text-center">
                <div class="mb-4">
                    <i class="ri ri-checkbox-circle-line icon-base text-success"></i>
                </div>
                <h4 class="mb-3">تم التقديم بنجاح!</h4>
                <p class="mb-4">شكراً لك، تم استلام طلبك بنجاح وسيتم مراجعته قريباً.</p>

                <div class="alert alert-info text-start mb-4">
                    <h6 class="mb-2"><i class="ri ri-information-2-line icon-base me-2"></i>معلومات إضافية:</h6>
                    <ul class="mb-0">
                        <li>رقم الطلب: <strong class="number">#{{ $responseId }}</strong></li>
                        <li>تاريخ التقديم: <strong class="number">{{ now()->format('Y-m-d H:i') }}</strong></li>
                        <li>حالة الطلب: <span class="badge bg-warning">قيد المراجعة</span></li>
                    </ul>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('employments') }}" class="btn btn-primary">
                        <i class="ri ri-file-list-3-line icon-base me-1"></i> العودة إلى الاستمارات
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="ri ri-printer-line icon-base me-1"></i> طباعة الطلب
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="container p-5">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">{{ $form->title }}</h3>
                            @if($form->description)
                                <p class="mb-0">{{ $form->description }}</p>
                            @endif
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $form->isOpen() ? 'success' : 'danger' }} fs-6">
                                {{ $form->isOpen() ? 'مفتوحة' : 'مغلقة' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- معلومات الاستمارة -->
                <div class="card-body border-bottom">
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <i class="ri ri-calendar-line icon-base me-2 text-muted"></i>
                                @if($form->start_date && $form->end_date)
                                    <small>من <span class="number">{{ $form->start_date->format('Y-m-d') }}</span> إلى <span class="number">{{ $form->end_date->format('Y-m-d') }}</span></small>
                                @elseif($form->start_date)
                                    <small>يبدأ في: <span class="number">{{ $form->start_date->format('Y-m-d') }}</span></small>
                                @else
                                    <small>مفتوحة دائمًا</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            @if($form->max_responses)
                                <small class="text-muted">
                                    <i class="ri ri-group-line icon-base me-1"></i>
                                    <span class="number">{{ $form->responses()->count() }}/{{ $form->max_responses }}</span> إجابة
                                </small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- رسائل الأخطاء -->
                @error('form')
                    <div class="alert alert-danger mx-3 mt-3">
                        <i class="ri ri-error-warning-line me-2"></i>
                        {{ $message }}
                    </div>
                @enderror

                <!-- النموذج -->
                <div class="card-body">
                    <form wire:submit.prevent="submitResponse" id="form-response">
                        @foreach($fields as $index => $field)
                            <div class="mb-4">
                                <!-- تسمية الحقل -->
                                <label class="form-label fw-semibold">
                                    {{ $field['label'] }}
                                    @if($field['required'])
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>

                                <!-- الوصف -->
                                @if(!empty($field['description']))
                                    <small class="d-block text-muted mb-2">
                                        <i class="ri ri-information-line me-1"></i>
                                        {{ $field['description'] }}
                                    </small>
                                @endif

                                <!-- عرض الحقل -->
                                <div class="mt-2">
                                    @switch($field['type'])
                                        @case('text')
                                        @case('email')
                                        @case('number')
                                        @case('tel')
                                        @case('url')
                                        @case('date')
                                            <input type="{{ $field['type'] }}"
                                                   id="field_{{ $field['name'] }}"
                                                   class="form-control @error($field['name']) is-invalid @enderror"
                                                   wire:model="responses.{{ $field['name'] }}"
                                                   placeholder="{{ $field['placeholder'] ?? '' }}"
                                                   @if($field['required']) required @endif
                                                   @if(isset($field['settings']['max_length']))
                                                       maxlength="{{ $field['settings']['max_length'] }}"
                                                   @endif>
                                            @break

                                        @case('textarea')
                                            <textarea id="field_{{ $field['name'] }}"
                                                      class="form-control @error($field['name']) is-invalid @enderror"
                                                      wire:model="responses.{{ $field['name'] }}"
                                                      rows="{{ $field['settings']['rows'] ?? 4 }}"
                                                      placeholder="{{ $field['placeholder'] ?? '' }}"
                                                      @if($field['required']) required @endif></textarea>
                                            @break

                                        @case('select')
                                            <select id="field_{{ $field['name'] }}"
                                                    class="form-select @error($field['name']) is-invalid @enderror"
                                                    wire:model="responses.{{ $field['name'] }}"
                                                    @if($field['required']) required @endif>
                                                <option value="">اختر {{ $field['label'] }}...</option>
                                                @if(is_array($field['options']))
                                                    @foreach($field['options'] as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @break

                                        @case('radio')
                                            <div class="mt-2">
                                                @if(is_array($field['options']))
                                                    @foreach($field['options'] as $option)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input"
                                                                   type="radio"
                                                                   id="{{ $field['name'] }}_{{ $loop->index }}"
                                                                   wire:model="responses.{{ $field['name'] }}"
                                                                   value="{{ $option }}"
                                                                   @if($field['required']) required @endif>
                                                            <label class="form-check-label" for="{{ $field['name'] }}_{{ $loop->index }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            @break

                                        @case('checkbox')
                                            @if(is_array($field['options']) && count($field['options']) > 1)
                                                <!-- خيارات متعددة -->
                                                <div class="mt-2">
                                                    @foreach($field['options'] as $option)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input"
                                                                   type="checkbox"
                                                                   id="{{ $field['name'] }}_{{ $loop->index }}"
                                                                   wire:model="responses.{{ $field['name'] }}"
                                                                   value="{{ $option }}">
                                                            <label class="form-check-label" for="{{ $field['name'] }}_{{ $loop->index }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <!-- صندوق اختيار واحد -->
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           id="{{ $field['name'] }}"
                                                           wire:model="responses.{{ $field['name'] }}"
                                                           value="1"
                                                           @if($field['required']) required @endif>
                                                    <label class="form-check-label" for="{{ $field['name'] }}">
                                                        {{ $field['options'][0] ?? $field['label'] }}
                                                    </label>
                                                </div>
                                            @endif
                                            @break

                                        @case('file')
                                            <div>
                                                @if(isset($files[$field['name']]))
                                                    <!-- عرض الملف المرفوع -->
                                                    <div class="alert alert-success d-flex justify-content-between align-items-center mb-2">
                                                        <div>
                                                            <i class="ri ri-file-line icon-base me-2"></i>
                                                            {{ $files[$field['name']]->getClientOriginalName() }}
                                                            <small class="text-muted d-block">
                                                                الحجم: {{ number_format($files[$field['name']]->getSize() / 1024, 2) }} كيلوبايت
                                                            </small>
                                                        </div>
                                                        <button type="button"
                                                                class="btn btn-sm btn-danger"
                                                                wire:click="removeFile('{{ $field['name'] }}')">
                                                            <i class="ri ri-close-line icon-base"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <!-- حقل رفع الملف -->
                                                    <input type="file"
                                                           id="field_{{ $field['name'] }}"
                                                           class="form-control @error($field['name']) is-invalid @enderror"
                                                           wire:model="files.{{ $field['name'] }}"
                                                           @if($field['required']) required @endif
                                                           @if(isset($field['settings']['allowed_types']))
                                                               accept="{{ implode(',', array_map(function($type) { return '.' . $type; }, $field['settings']['allowed_types'])) }}"
                                                           @endif>
                                                @endif

                                                <!-- معلومات الملف -->
                                                @if(isset($field['settings']['max_size']) || isset($field['settings']['allowed_types']))
                                                    <small class="text-muted d-block mt-1">
                                                        @if(isset($field['settings']['max_size']))
                                                            <i class="ri ri-hard-drive-line icon-base me-1"></i> الحد الأقصى: {{ $field['settings']['max_size'] / 1024 }} ميجابايت
                                                        @endif
                                                        @if(isset($field['settings']['allowed_types']))
                                                            <span class="ms-2">
                                                                <i class="ri ri-file-line icon-base me-1"></i> الأنواع المسموحة: {{ implode(', ', $field['settings']['allowed_types']) }}
                                                            </span>
                                                        @endif
                                                    </small>
                                                @endif
                                            </div>
                                            @break
                                    @endswitch

                                    <!-- رسالة الخطأ -->
                                    @error($field['name'])
                                        <div class="invalid-feedback d-block">
                                            <i class="ri ri-error-warning-line icon-base me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        <!-- أزرار الإجراءات -->
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div>
                                <small class="text-muted">
                                    <i class="ri ri-shield-check-line icon-base me-1"></i>
                                    بياناتك محمية ولا يتم مشاركتها مع طرف ثالث
                                </small>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary" wire:click="preview">
                                    <i class="ri ri-eye-line icon-base me-1"></i> معاينة
                                </button>
                                <button type="submit"
                                        class="btn btn-primary btn-lg px-5"
                                        wire:loading.attr="disabled"
                                        {{ !$form->isOpen() ? 'disabled' : '' }}>
                                    <span wire:loading.remove>
                                        <i class="ri ri-send-plane-2-line icon-base me-1"></i>
                                        @if ($responseId)
                                            <span>تعديل الطلب</span>
                                        @else
                                            <span>تقديم الطلب</span>
                                        @endif
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        جاري الإرسال...
                                    </span>
                                </button>
                            </div>
                        </div>
                        <!-- رسائل الأخطاء -->
                        @error('form')
                            <div class="alert alert-danger mx-3 mt-3">
                                <i class="ri ri-error-warning-line me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    @endif
    </div>
  </div>
    

    <!-- معاينة البيانات -->

    <script>
        $wire.on('show-preview', (data) => {
            // يمكنك عرض معاينة البيانات هنا
            console.log('Preview Data:', data);
            alert('البيانات صالحة، يمكنك إرسال النموذج');
        });
    </script>

</div>
