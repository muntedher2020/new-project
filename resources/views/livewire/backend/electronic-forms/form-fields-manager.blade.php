<div>
    @if (session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>إدارة حقول الاستمارة: <span class="text-primary">{{ $form->title }}</span></h4>
        <button class="btn btn-primary" {{-- wire:click="$toggle('showFieldModal')" --}}
            wire:click="createField"
            data-bs-toggle="modal"
            data-bs-target="#showFieldModal">
            <i class="ri ri-add-circle-fill icon-24px me-2"></i> إضافة حقل جديد
        </button>
    </div>

    <!-- قائمة الحقول -->
    {{-- <div class="card">
        <div class="card-body">
            @if(count($fields) > 0)
                <div class="list-group" id="sortable-fields"
                    wire:sortable="updateFieldOrder"
                    wire:sortable.options="{ animation: 150, handle: '.sort-handle' }">
                    @foreach($fields as $field)
                        <div class="list-group-item d-flex justify-content-between align-items-center" 
                            wire:key="field-{{ $field['id'] }}" 
                            wire:sortable.item="{{ $field['id'] }}" >
                            <div class="d-flex align-items-center">
                                <span class="me-3 text-muted" wire:sortable.handle>
                                    <i class="fas fa-grip-vertical"></i>
                                </span>
                                <div>
                                    <h6 class="mb-1">{{ $field['label'] }}</h6>
                                    <small class="text-muted">
                                        النوع: {{ $fieldTypes[$field['type']] ?? $field['type'] }}
                                        | الاسم: <code>{{ $field['name'] }}</code>
                                        @if($field['required'])
                                            | <span class="text-danger">مطلوب</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary py-1" 
                                        wire:click="editField({{ $field['id'] }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#showFieldModal">
                                    <i class="ri ri-pencil-fill icon-20px"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger py-1" 
                                        wire:click="deleteField({{ $field['id'] }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteFieldModal" >
                                    <i class="ri ri-delete-bin-fill icon-20px"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri ri-text-block fs-1 text-muted mb-3"></i>
                    <p class="text-muted">لا توجد حقول مضافة بعد</p>
                </div>
            @endif
        </div>
    </div> --}}

    <!-- قائمة الحقول -->
    <div class="card">
        <div class="card-body">
            @if(count($fields) > 0)
                <div class="list-group" id="fieldsContainer"
                     {{-- id="sortable-fields" --}}
                     wire:sortable="updateFieldOrder"
                     wire:sortable.options="{ animation: 150, handle: '.sort-handle' }">
                     
                    @foreach($fields as $index => $field)
                        <div class="list-group-item d-flex justify-content-between align-items-center" 
                             wire:key="field-{{ $field['id'] }}" 
                             wire:sortable.item="{{ $field['id'] }}">
                             
                            <div class="d-flex align-items-center">
                                <!-- زر السحب -->
                                {{-- <span class="sort-handle me-3 text-muted" 
                                      style="cursor: {{ $reordering ? 'grab' : 'default' }}">
                                    <i class="ri ri-expand-vertical-line"></i>
                                </span> --}}
                                
                                <!-- رقم الترتيب -->
                                <span class="badge bg-primary rounded-circle me-3">
                                    {{ $field['sort_order'] }}
                                </span>
                                
                                <div>
                                    <h6 class="mb-1">{{ $field['label'] }}</h6>
                                    <small class="text-muted">
                                        النوع: {{ $fieldTypes[$field['type']] ?? $field['type'] }}
                                        | الاسم: <code>{{ $field['name'] }}</code>
                                        @if($field['required'])
                                            | <span class="text-danger">مطلوب</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            
                            <div class="btn-group">
                                @if(!$reordering)
                                    <!-- أزرار النقل في الوضع العادي -->
                                    @if($index > 0)
                                        <button class="btn btn-sm btn-outline-primary py-1" 
                                                wire:click="moveUp({{ $field['id'] }})"
                                                title="نقل للأعلى">
                                            <i class="ri ri-arrow-up-s-line icon-24px"></i>
                                        </button>
                                    @endif
                                    
                                    @if($index < count($fields) - 1)
                                        <button class="btn btn-sm btn-outline-primary py-1" 
                                                wire:click="moveDown({{ $field['id'] }})"
                                                title="نقل للأسفل">
                                            <i class="ri ri-arrow-down-s-line icon-24px"></i>
                                        </button>
                                    @endif
                                    
                                    <button class="btn btn-sm btn-label-warning py-1" 
                                            wire:click="editField({{ $field['id'] }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#showFieldModal"
                                            title="تعديل">
                                        <i class="ri ri-pencil-fill icon-24px"></i>
                                    </button>
                                    
                                    <button class="btn btn-sm btn-label-danger py-1" 
                                            wire:click="deleteField({{ $field['id'] }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteFieldModal"
                                            title="حذف">
                                        <i class="ri ri-delete-bin-fill icon-24px"></i>
                                    </button>
                                @else
                                    <!-- عرض في وضع إعادة الترتيب -->
                                    <span class="text-muted">
                                        اسحب وأفلت لإعادة الترتيب
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($reordering)
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        قم بسحب الحقول وإفلاتها لإعادة ترتيبها. سيتم حفظ الترتيب تلقائياً.
                    </div>
                @endif
                
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد حقول مضافة بعد</p>
                    <button class="btn btn-primary" wire:click="$toggle('showFieldModal')">
                        <i class="fas fa-plus"></i> إضافة أول حقل
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal إضافة/تعديل/حذف حقل -->
    @include('livewire.backend.electronic-forms.modals.create-edit-field-form')
    @include('livewire.backend.electronic-forms.modals.delete-field-form')

    {{-- <div class="card shadow-sm">
        <div class="card-body p-0">
            @if(count($fields) > 0)
                <div class="list-group list-group-flush" id="fieldsContainer">
                    @foreach($fields as $index => $field)
                        <div class="list-group-item field-item" 
                             data-id="{{ $field['id'] }}"
                             wire:key="field-{{ $field['id'] }}">
                            
                            <div class="d-flex align-items-center">
                                <!-- مقبض السحب -->
                                <div class="drag-handle me-3" style="cursor: grab;">
                                    <i class="fas fa-grip-vertical text-muted"></i>
                                </div>
                                
                                <!-- رقم الترتيب -->
                                <div class="order-badge me-3">
                                    <span class="badge bg-primary rounded-circle" style="width: 30px; height: 30px; line-height: 30px;">
                                        {{ $field['sort_order'] }}
                                    </span>
                                </div>
                                
                                <!-- معلومات الحقل -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                {{ $field['label'] }}
                                                @if($field['required'])
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </h6>
                                            <div class="text-muted small">
                                                <span class="badge bg-info me-2">
                                                    {{ $field['type'] }}
                                                </span>
                                                <code class="me-2">{{ $field['name'] }}</code>
                                                @if($field['description'])
                                                    <span class="me-2">| {{ $field['description'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- أزرار الإجراءات -->
                                        @if(!$isReordering)
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" 
                                                        wire:click="editField({{ $field['id'] }})"
                                                        title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" 
                                                        wire:click="deleteField({{ $field['id'] }})"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا الحقل؟')"
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($isReordering)
                    <div class="card-footer bg-light">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            قم بسحب الحقول وإفلاتها لإعادة ترتيبها. سيتم حفظ الترتيب تلقائياً عند إنهاء الترتيب.
                        </div>
                    </div>
                @endif
            @else
                <!-- حالة عدم وجود حقول -->
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-inbox fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">لا توجد حقول مضافة</h5>
                    <p class="text-muted mb-4">ابدأ بإضافة حقول جديدة إلى الاستمارة</p>
                    <button class="btn btn-primary" wire:click="$toggle('showFieldModal')">
                        <i class="fas fa-plus me-1"></i> إضافة أول حقل
                    </button>
                </div>
            @endif
        </div>
    </div> --}}

    {{-- <div class="col-sm-12">
      <div class="card">
        <h5 class="card-header">Cloning</h5>
        <div class="card-body">
          <p>Pending Tasks</p>
              <ul class="list-group list-group-flush" id="clone-source-1"
                    id="fieldsContainer"
                    wire:sortable="updateFieldOrder({{ $field['id'] }})"
                    wire:sortable.options="{ animation: 150, handle: '.sort-handle' }">
                @foreach($fields as $index => $field)
                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center"
                  wire:key="field-{{ $field['id'] }}" 
                  wire:sortable.item="{{ $field['id'] }}">
                  <div class="d-flex align-items-center">
                    <!-- رقم الترتيب -->
                                <span class="badge bg-primary rounded-circle me-3">
                                    {{ $field['sort_order'] }}
                                </span>
                                
                                <div>
                                    <h6 class="mb-1">{{ $field['label'] }}</h6>
                                    <small class="text-muted">
                                        النوع: {{ $fieldTypes[$field['type']] ?? $field['type'] }}
                                        | الاسم: <code>{{ $field['name'] }}</code>
                                        @if($field['required'])
                                            | <span class="text-danger">مطلوب</span>
                                        @endif
                                    </small>
                                </div>
                  </div>
                  <div class="btn-group">
                                @if(!$isReordering)
                                    <!-- أزرار النقل في الوضع العادي -->
                                    @if($index > 0)
                                        <button class="btn btn-sm btn-outline-primary py-1" 
                                                wire:click="moveUp({{ $field['id'] }})"
                                                title="نقل للأعلى">
                                            <i class="ri ri-arrow-up-s-line icon-24px"></i>
                                        </button>
                                    @endif
                                    
                                    @if($index < count($fields) - 1)
                                        <button class="btn btn-sm btn-outline-primary py-1" 
                                                wire:click="moveDown({{ $field['id'] }})"
                                                title="نقل للأسفل">
                                            <i class="ri ri-arrow-down-s-line icon-24px"></i>
                                        </button>
                                    @endif
                                    
                                    <button class="btn btn-sm btn-label-warning py-1" 
                                            wire:click="editField({{ $field['id'] }})"
                                            title="تعديل">
                                        <i class="ri ri-pencil-fill icon-24px"></i>
                                    </button>
                                    
                                    <button class="btn btn-sm btn-label-danger py-1" 
                                            wire:click="deleteField({{ $field['id'] }})"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا الحقل؟')"
                                            title="حذف">
                                        <i class="ri ri-delete-bin-fill icon-24px"></i>
                                    </button>
                                @else
                                    <!-- عرض في وضع إعادة الترتيب -->
                                    <span class="text-muted">
                                        اسحب وأفلت لإعادة الترتيب
                                    </span>
                                @endif
                            </div>
                </li>
                @endforeach
              </ul>
        </div>
        @if($isReordering)
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        قم بسحب الحقول وإفلاتها لإعادة ترتيبها. سيتم حفظ الترتيب تلقائياً.
                    </div>
                @endif
      </div>
    </div> --}}


        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        
        <script>
            document.addEventListener('livewire:initialized', () => {
                let sortable = null;
                
                // تهيئة SortableJS
                function initSortable() {
                    const container = document.getElementById('fieldsContainer');
                    if (!container) return;

                    sortable = Sortable.create(container, {
                        animation: 150,
                        handle: '.drag-handle',
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        onEnd: function(evt) {
                            const items = Array.from(container.children);
                            const newOrder = items.map(item => ({
                                value: item.getAttribute('data-id'),
                                order: items.indexOf(item) + 1
                            }));
                            
                            // تحديث الأرقام الظاهرة
                            items.forEach((item, index) => {
                                const badge = item.querySelector('.order-badge .badge');
                                if (badge) {
                                    badge.textContent = index + 1;
                                }
                            });
                            
                            // إرسال الترتيب الجديد لـ Livewire
                            @this.updateOrder(newOrder);
                        }
                    });
                    
                    // تعطيل في البداية
                    sortable.option('disabled', true);
                }
                
                // تفعيل/تعطيل السحب والإفلات
                Livewire.on('reorder-enabled', () => {
                    if (sortable) {
                        sortable.option('disabled', false);
                        document.querySelectorAll('.drag-handle').forEach(handle => {
                            handle.style.cursor = 'grab';
                        });
                        
                        // إضافة تأثيرات بصرية
                        document.querySelectorAll('.field-item').forEach(item => {
                            item.classList.add('border-primary', 'border-start-4');
                        });
                    }
                });
                
                Livewire.on('reorder-disabled', () => {
                    if (sortable) {
                        sortable.option('disabled', true);
                        document.querySelectorAll('.drag-handle').forEach(handle => {
                            handle.style.cursor = 'default';
                        });
                        
                        // إزالة التأثيرات البصرية
                        document.querySelectorAll('.field-item').forEach(item => {
                            item.classList.remove('border-primary', 'border-start-4');
                        });
                    }
                });
                
                // إعادة تهيئة عند تحديث الحقول
                Livewire.on('fields-updated', () => {
                    if (sortable) {
                        sortable.destroy();
                    }
                    setTimeout(initSortable, 100);
                });
                
                // التهيئة الأولية
                initSortable();
            });

            // إضافة أصوات للسحب والإفلات
function playDragSound() {
    const audio = new Audio('path/to/drag-sound.mp3');
    audio.volume = 0.3;
    audio.play();
}

function playDropSound() {
    const audio = new Audio('path/to/drop-sound.mp3');
    audio.volume = 0.3;
    audio.play();
}

// حفظ تلقائي كل 5 ثواني أثناء إعادة الترتيب
let autoSaveInterval;

function startAutoSave() {
    autoSaveInterval = setInterval(() => {
        const items = Array.from(document.querySelectorAll('.field-item'));
        const order = items.map(item => ({
            value: item.getAttribute('data-id'),
            order: items.indexOf(item) + 1
        }));
        
        @this.updateOrder(order);
    }, 5000);
}

function stopAutoSave() {
    clearInterval(autoSaveInterval);
}
        </script>

        <style>
            /* تنسيقات السحب والإفلات */
            .sortable-ghost {
                opacity: 0.4;
                background: #f8f9fa;
            }
            
            .sortable-chosen {
                background-color: #e3f2fd !important;
                box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
            }
            
            .sortable-drag {
                opacity: 1 !important;
                transform: rotate(3deg);
                background: white;
                border: 2px dashed #0d6efd !important;
            }
            
            .drag-handle:hover {
                color: #6c757d;
            }
            
            .field-item {
                transition: all 0.3s ease;
                border-left: 4px solid transparent;
            }
            
            .field-item:hover {
                background-color: #f8f9fa;
            }
            
            .order-badge {
                min-width: 40px;
            }
            
            /* تأثيرات السحب */
            .dragging {
                cursor: grabbing !important;
            }
            
            .drop-zone {
                border-top: 2px solid #0d6efd;
                margin-top: 5px;
            }

            .drag-indicator {
    position: absolute;
    left: -20px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.3s;
}

.field-item:hover .drag-indicator {
    opacity: 1;
}

.drag-line {
    position: absolute;
    height: 2px;
    background: #0d6efd;
    width: 100%;
    top: -1px;
    left: 0;
    display: none;
    z-index: 10;
}

.drop-zone .drag-line {
    display: block;
}
        </style>

</div>
