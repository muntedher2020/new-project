<?php

namespace App\Livewire\Backend\ElectronicForms;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Backend\ElectronicForms\FormResponses;
use App\Models\Backend\ElectronicForms\ElectronicForms;

class ElectronicForm extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $formId;
    public $search = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedRows = [];
    public $selectAll = false;
    public $perPage = 20;
    
    // متغيرات للـ Modals
    public $title = '';
    public $description = '';
    public $form_type = 'custom';
    public $active = false;
    public $require_login = false;
    public $allow_multiple = false;
    public $max_responses;
    public $start_date;
    public $end_date;

    public $showDeleteModal = false;
    public $deleteFormId = null;
    public $deleteFormTitle = '';
    public $showBulkDeleteModal = false;
    public $editFormId, $showEditModal;
    public $modalType = ''; // 'edit', 'delete', 'bulkDelete'
    public $modalData = [];

    // خصائص الاستمارة للعرض فقط
    public $formSlug = '';
    public $fieldsCount = 0;
    public $responsesCount = 0;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'form_type' => 'nullable|string',
        'active' => 'boolean',
        'require_login' => 'boolean',
        'allow_multiple' => 'boolean',
        'max_responses' => 'nullable|integer|min:1',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 20],
    ];

    // إضافة listeners للأحداث
    protected $listeners = [
        'refresh' => '$refresh',
        'closeModal' => 'closeDeleteModal',
    ];

    public function render()
    {
        $formTypes  = [
            'custom' => 'مخصص',
            'job_application' => 'استمارة توظيف',
            'contact_form' => 'استمارة تواصل',
            'survey' => 'استبيان',
            'registration' => 'تسجيل',
            'complaint' => 'شكوى',
            'suggestion' => 'اقتراح'
        ];

        return view('livewire.backend.electronic-forms.electronic-form', [
            'forms' => $this->forms,
            'formTypes' => $formTypes,
            'totalActive' => ElectronicForms::where('active', true)->count(),
            'totalInactive' => ElectronicForms::where('active', false)->count(),
            'totalResponses' => FormResponses::count(),
            'totalForms' => ElectronicForms::count(),
        ]);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->forms->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedRows = [];
        }
    }

    public function updatedSelectedRows()
    {
        $this->selectAll = false;
    }

     public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function resetForm()
    {
        $this->reset([
            'title', 'description', 'form_type', 'active',
            'require_login', 'allow_multiple', 'max_responses',
            'start_date', 'end_date'
        ]);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();
      
        try {
            $form = ElectronicForms::create([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'slug' => $this->generateSlug($this->title),
                'description' => $this->description,
                'form_type' => $this->form_type,
                'active' => $this->active,
                'require_login' => $this->require_login,
                'allow_multiple' => $this->allow_multiple,
                'max_responses' => $this->max_responses,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'settings' => [],
            ]);

            // إذا كان هناك نوع محدد، ننشئ حقول افتراضية
            if ($this->form_type && $this->form_type !== 'custom') {
                $this->createDefaultFields($form, $this->form_type);
            }

            $this->dispatch('showSuccess', 'تم إنشاء الاستمارة بنجاح');
            $this->dispatch('refreshForms');
            $this->close();

            // إعادة التوجيه إلى صفحة إدارة الحقول
            return redirect()->route('forms.fields.manage', $form->id);

        } catch (\Exception $e) {
            $this->dispatch('showError', 'حدث خطأ أثناء إنشاء الاستمارة: ' . $e->getMessage());
        }
    }

    public function openModal($formid)
    {
        $this->modalType = 'edit';
        $this->modalData = ['id' => $formid];
        $this->formId = $formid;
        $this->loadFormData($this->modalData['id']);
        $this->dispatch('showModal');
    }

    public function loadFormData()
    {
        if (!$this->formId) {
            return;
        }

        try {
            $form = ElectronicForms::withCount(['fields', 'responses'])->findOrFail($this->formId);
            
            $this->title = $form->title;
            $this->description = $form->description;
            $this->active = $form->active;
            $this->require_login = $form->require_login;
            $this->allow_multiple = $form->allow_multiple;
            $this->max_responses = $form->max_responses;
            $this->start_date = $form->start_date ? $form->start_date->format('Y-m-d\TH:i') : null;
            $this->end_date = $form->end_date ? $form->end_date->format('Y-m-d\TH:i') : null;

            // خصائص للعرض فقط
            $this->formSlug = $form->slug;
            $this->fieldsCount = $form->fields_count;
            $this->responsesCount = $form->responses_count;
            
        } catch (\Exception $e) {
            $this->dispatch('showError', 'تعذر تحميل بيانات الاستمارة: ' . $e->getMessage());
            $this->close();
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $form = ElectronicForms::findOrFail($this->formId);
            
            $form->update([
                'title' => $this->title,
                'description' => $this->description,
                'active' => $this->active,
                'require_login' => $this->require_login,
                'allow_multiple' => $this->allow_multiple,
                'max_responses' => $this->max_responses,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            $this->dispatch('showSuccess', 'تم تحديث الاستمارة بنجاح');
            $this->dispatch('formUpdated');

        } catch (\Exception $e) {
            $this->dispatch('showError', 'حدث خطأ أثناء تحديث الاستمارة: ' . $e->getMessage());
        }
    }

    private function generateSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (ElectronicForms::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function createDefaultFields($form, $formType)
    {
        $defaultFields = $this->getDefaultFieldsByType($formType);

        foreach ($defaultFields as $index => $fieldData) {
            $form->fields()->create(array_merge($fieldData, [
                'sort_order' => $index
            ]));
        }
    }

    private function getDefaultFieldsByType($type)
    {
        $fields = [
            'job_application' => [
                [
                    'label' => 'الاسم الكامل',
                    'name' => 'full_name',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'أدخل الاسم الكامل'
                ],
                [
                    'label' => 'البريد الإلكتروني',
                    'name' => 'email',
                    'type' => 'email',
                    'required' => true,
                    'placeholder' => 'example@email.com'
                ],
                [
                    'label' => 'رقم الهاتف',
                    'name' => 'phone',
                    'type' => 'tel',
                    'required' => true,
                    'placeholder' => '05XXXXXXXX'
                ],
                [
                    'label' => 'المؤهل العلمي',
                    'name' => 'education',
                    'type' => 'select',
                    'required' => true,
                    'options' => ['ثانوية عامة', 'دبلوم', 'بكالوريوس', 'ماجستير', 'دكتوراه'],
                    'placeholder' => 'اختر المؤهل العلمي'
                ],
                [
                    'label' => 'الخبرات السابقة',
                    'name' => 'experience',
                    'type' => 'textarea',
                    'required' => false,
                    'placeholder' => 'اذكر خبراتك السابقة'
                ],
                [
                    'label' => 'السيرة الذاتية',
                    'name' => 'cv',
                    'type' => 'file',
                    'required' => true,
                    'placeholder' => 'ارفع ملف السيرة الذاتية'
                ]
            ],
            
            'contact_form' => [
                [
                    'label' => 'الاسم',
                    'name' => 'name',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'أدخل اسمك'
                ],
                [
                    'label' => 'البريد الإلكتروني',
                    'name' => 'email',
                    'type' => 'email',
                    'required' => true,
                    'placeholder' => 'example@email.com'
                ],
                [
                    'label' => 'رقم الهاتف',
                    'name' => 'phone',
                    'type' => 'tel',
                    'required' => false,
                    'placeholder' => '05XXXXXXXX'
                ],
                [
                    'label' => 'الموضوع',
                    'name' => 'subject',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'موضوع الرسالة'
                ],
                [
                    'label' => 'الرسالة',
                    'name' => 'message',
                    'type' => 'textarea',
                    'required' => true,
                    'placeholder' => 'اكتب رسالتك هنا...'
                ]
            ],
        ];

        return $fields[$type] ?? [];
    }

    











    /* public function openModal($type, $data = [])
    {dd('vsdvs');
        $this->modalType = $type;
        $this->modalData = $data;
        
        if ($type === 'edit') {
            if (!Gate::allows('electronicform-edit')) {
                $this->dispatch('showError', 'ليس لديك صلاحية لتعديل الاستمارات');
                return;
            }
            $this->loadFormData($data['id']);
        }
        
        $this->dispatch('showModal');
    } */

    // فتح Modal التعديل
    public function openEditModal($id)
    {dd('353454353');
        if (!Gate::allows('electronicform-edit')) {
            $this->dispatch('showError', 'ليس لديك صلاحية لتعديل الاستمارات');
            return;
        }

        // إرسال حدث إلى مكون التعديل لفتحه
        $this->dispatch('openEditModal', formId: $id);
    }

    // إغلاق Modal التعديل
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editFormId = null;
    }

     // تحديث الصفحة بعد التعديل
    public function refreshPage()
    {
        $this->closeEditModal();
        $this->dispatch('refresh');
    }

    // فتح Modal حذف استمارة واحدة
    public function confirmDelete($id, $title)
    {
        if (!Gate::allows('electronicform-delete')) {
            $this->dispatch('showError', 'ليس لديك صلاحية لحذف الاستمارات');
            return;
        }

        $this->deleteFormId = $id;
        $this->deleteFormTitle = $title;
        $this->showDeleteModal = true;
    }

     // تنفيذ حذف استمارة واحدة
    public function deleteForm()
    {
        if (!Gate::allows('electronicform-delete')) {
            $this->dispatch('showError', 'ليس لديك صلاحية لحذف الاستمارات');
            $this->closeDeleteModal();
            return;
        }

        try {
            $form = ElectronicForm::findOrFail($this->deleteFormId);
            $form->delete();

            $this->dispatch('showSuccess', 'تم حذف الاستمارة بنجاح');
            $this->closeDeleteModal();
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            $this->dispatch('showError', 'حدث خطأ أثناء حذف الاستمارة: ' . $e->getMessage());
            $this->closeDeleteModal();
        }
    }

    // فتح Modal حذف جماعي
    public function confirmBulkDelete()
    {
        if (!Gate::allows('electronicform-delete')) {
            $this->dispatch('showError', 'ليس لديك صلاحية لحذف الاستمارات');
            return;
        }

        if (empty($this->selectedRows)) {
            $this->dispatch('showError', 'يرجى تحديد استمارات للحذف');
            return;
        }

        $this->showBulkDeleteModal = true;
    }

    // تنفيذ الحذف الجماعي
    public function deleteSelected()
    {
        if (!Gate::allows('electronicform-delete')) {
            $this->dispatch('showError', 'ليس لديك صلاحية لحذف الاستمارات');
            $this->closeBulkDeleteModal();
            return;
        }

        try {
            ElectronicForm::whereIn('id', $this->selectedRows)->delete();
            
            $this->selectedRows = [];
            $this->selectAll = false;
            
            $this->dispatch('showSuccess', 'تم حذف الاستمارات المحددة بنجاح');
            $this->closeBulkDeleteModal();
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            $this->dispatch('showError', 'حدث خطأ أثناء حذف الاستمارات: ' . $e->getMessage());
            $this->closeBulkDeleteModal();
        }
    }

     // إغلاق Modals
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteFormId = null;
        $this->deleteFormTitle = '';
    }

    public function closeBulkDeleteModal()
    {
        $this->showBulkDeleteModal = false;
    }

    // معالجة أحداث الإغلاق من JavaScript
    public function handleCloseModal($data)
    {
        if (isset($data['modal'])) {
            if ($data['modal'] === 'delete') {
                $this->closeDeleteModal();
            } elseif ($data['modal'] === 'bulkDelete') {
                $this->closeBulkDeleteModal();
            }
        }
    }

    // تغيير حالة الاستمارة
    public function toggleStatus($id)
    {
        if (!Gate::allows('electronicform-edit')) {
            $this->dispatch('showError', 'ليس لديك صلاحية لتغيير حالة الاستمارات');
            return;
        }

        try {
            $form = ElectronicForms::findOrFail($id);
            $form->update(['active' => !$form->active]);

            $this->dispatch('showSuccess', 'تم تغيير حالة الاستمارة بنجاح');
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            $this->dispatch('showError', 'حدث خطأ أثناء تغيير الحالة: ' . $e->getMessage());
        }
    }

    public function getFormsProperty()
    {
        return ElectronicForms::query()
            ->withCount(['fields', 'responses'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== '', function ($query) {
                $query->where('active', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getFormTypesProperty()
    {
        return [
            'job_application' => 'استمارة توظيف',
            'contact_form' => 'استمارة تواصل',
            'survey' => 'استبيان',
            'registration' => 'تسجيل',
            'complaint' => 'شكوى',
            'suggestion' => 'اقتراح',
            'custom' => 'مخصص'
        ];
    }
}