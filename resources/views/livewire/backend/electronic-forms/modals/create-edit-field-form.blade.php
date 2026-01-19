{{-- @if($showFieldModal) --}}
  <div class="modal fade" wire:ignore.self id="showFieldModal" tabindex="-1" aria-hidden="true">
  {{-- <div class="modal fade show d-block" tabindex="-1"> --}}
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">
                      {{ $fieldId ? 'تعديل الحقل' : 'إضافة حقل جديد' }}
                  </h5>
                  <button type="button" class="btn-close" wire:click="$toggle('showFieldModal')"></button>
              </div>
              <div class="modal-body">
                  <form wire:submit.prevent="saveField">
                      <div class="row">
                          <div class="col-md-6 mb-3">
                              <label class="form-label">نص التسمية *</label>
                              <input type="text" class="form-control" wire:model.live="label">
                              @error('label') <span class="text-danger">{{ $message }}</span> @enderror
                          </div>

                          <div class="col-md-6 mb-3">
                              <label class="form-label">اسم الحقل *</label>
                              <input type="text" class="form-control" wire:model.live="name">
                              <small class="text-muted">يستخدم للإشارة للحقل في الكود</small>
                              @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                          </div>

                          <div class="col-md-6 mb-3">
                              <label class="form-label">نوع الحقل *</label>
                              <select class="form-select" wire:model.live="type">
                                  @foreach($fieldTypes as $value => $fieldType)
                                  <option value="{{ $value }}">{{ $fieldType }}</option>
                                  @endforeach
                              </select>
                              @error('type') <span class="text-danger">{{ $message }}</span> @enderror

                              <!-- عرض وصف النوع -->
                              <small class="text-muted">
                                @php
                                  $descriptions = [
                                    'text' => 'حقل نصي عادي',
                                    'email' => 'حقل للبريد الإلكتروني مع تحقق من الصيغة',
                                    'number' => 'حقل للأرقام فقط',
                                    'textarea' => 'مربع نص كبير متعدد الأسطر',
                                    'select' => 'قائمة منسدلة للاختيار من متعدد',
                                    'checkbox' => 'مربع اختيار (يمكن اختيار أكثر من واحد)',
                                    'radio' => 'زر اختيار (اختيار واحد فقط)',
                                    'file' => 'حقل لرفع الملفات',
                                    'date' => 'حقل للتاريخ مع منتقي تواريخ',
                                    'tel' => 'حقل لأرقام الهواتف',
                                    'url' => 'حقل للروابط الإلكترونية',
                                  ];
                                @endphp
                                {{ $descriptions[$type] ?? 'اختر نوع الحقل لرؤية الوصف' }}
                              </small>
                          
                              @if(in_array($type, ['select', 'radio', 'checkbox']))
                                <label class="form-label">الخيارات (سطر لكل خيار)</label>
                                <textarea class="form-control" rows="4" wire:model="options"></textarea>
                              @endif
                          </div>

                          <div class="col-md-6 mb-3">
                              <div class="form-check custom-option custom-option-label custom-option-basic {{ $required == 1 ? 'checked' : '' }}">
                                  <label class="form-check-label custom-option-content" for="customCheckTemp5">
                                      <input wire:model.live="required" class="form-check-input" type="checkbox" value="" id="customCheckTemp5">
                                      <span class="custom-option-header">
                                        <span class="h6 mb-0"> حقل مطلوب</span>
                                        {{-- <small>20%</small> --}}
                                      </span>
                                      <span class="custom-option-body">
                                        <small class="option-text">عند تحديد هذا الخيار يصبح الحقل مطلوب</small>
                                      </span>
                                  </label>
                              </div>
                          </div>

                          <div class="col-md-6 mb-3">
                              <label class="form-label">النص التوضيحي</label>
                              <input type="text" class="form-control" wire:model.live="placeholder">
                          </div>

                          <div class="col-md-6 mb-3">
                              <label class="form-label">وصف إضافي</label>
                              <input type="text" class="form-control" wire:model.live="description">
                          </div>

                          <div class="col-12 mb-3">
                              <label class="form-label">قواعد التحقق الإضافية</label>
                              <input type="text" class="form-control" wire:model.live="validation_rules">
                              <small class="text-muted">مثال: email|max:255</small>
                          </div>

                          <!-- معاينة الحقل -->
                          <div class="col-12 mb-3">
                            <div class="card shadow-none bg-transparent border border-primary text-primary">
                              <div class="card-header">
                                  <h6 class="mb-0">معاينة الحقل</h6>
                              </div>
                              <div class="card-body">
                                <div class="mb-3">
                                  <label class="form-label">
                                      {{ $label ?: 'تسمية الحقل' }}
                                      @if($required) <span class="text-danger">*</span> @endif
                                  </label>

                                  @if($description)
                                      <small class="d-block text-muted mb-2">{{ $description }}</small>
                                  @endif

                                  @switch($type)
                                    @case('text')
                                    @case('email')
                                    @case('number')
                                    @case('date')
                                    @case('tel')
                                    @case('url')
                                        <input type="{{ $type }}" class="form-control" placeholder="{{ $placeholder ?: '...' }}" {{-- {{ $required ? 'required' : '' }} --}}>
                                    @break
                                    @case('textarea')
                                      <textarea class="form-control" rows="3" placeholder="{{ $placeholder ?: '...' }}" {{-- {{ $required ? 'required' : '' }} --}}></textarea>
                                    @break
                                    @case('select')
                                      <select class="form-select" {{-- {{ $required ? 'required' : '' }} --}}>
                                        <option value="">اختر...</option>
                                        @if($options)
                                          @foreach(explode("\n", $options) as $option)
                                            <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                          @endforeach
                                        @else
                                          <option value="خيار 1">خيار 1</option>
                                          <option value="خيار 2">خيار 2</option>
                                        @endif
                                      </select>
                                    @break
                                    @case('radio')
                                      @if($options)
                                        @foreach(explode("\n", $options) as $index => $option)
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="preview_radio" id="preview_radio_{{ $index }}">
                                            <label class="form-check-label" for="preview_radio_{{ $index }}">
                                              {{ trim($option) }}
                                            </label>
                                          </div>
                                        @endforeach
                                      @else
                                        <div class="form-check">
                                          <input class="form-check-input" type="radio" name="preview_radio">
                                          <label class="form-check-label">خيار 1</label>
                                        </div>
                                      @endif
                                    @break
                                    @case('checkbox')
                                      @if($options)
                                        @foreach(explode("\n", $options) as $index => $option)
                                          <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="preview_checkbox_{{ $index }}">
                                            <label class="form-check-label" for="preview_checkbox_{{ $index }}">
                                              {{ trim($option) }}
                                            </label>
                                          </div>
                                        @endforeach
                                      @else
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox">
                                          <label class="form-check-label">موافق على الشروط</label>
                                        </div>
                                      @endif
                                    @break
                                    @case('file')
                                      <input type="file" class="form-control">
                                      @if($description)
                                        <small class="text-muted">{{ $description }}</small>
                                      @endif
                                    @break
                                    @default
                                    <input type="text" class="form-control" placeholder="نوع الحقل غير معروف">
                                  @endswitch
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>

                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" type="reset" data-bs-dismiss="modal"
							              aria-label="Close" wire:loading.attr="disabled">
                            إلغاء
                          </button>
                          <button type="submit" class="btn btn-primary">
                              <span wire:loading.remove wire:target="saveField">{{ $fieldId ? 'تحديث' : 'إضافة' }}</span>
                              <span wire:loading wire:target="saveField">
                                  <div class="d-flex items-center">
                                      <i class="ri ri-loader-2-fill icon-20px icon-spin "></i> جاري {{ $fieldId ?
                                      'التحديث' : 'الاضافة' }}...
                                  </div>
                              </span>
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>

  <script type="module">
    // Check selected custom option
    window.Helpers.initCustomOptionCheck();
  </script>
{{-- @endif --}}
