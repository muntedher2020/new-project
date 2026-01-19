<?php

namespace App\Livewire\Backend\ElectronicForms;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Backend\ElectronicForms\ElectronicForms;

class CreateElectronicForm extends Component
{
    public $isOpen = false;
    public $title = '';
    public $description = '';
    public $form_type = 'custom';
    public $active = false;
    public $require_login = false;
    public $allow_multiple = false;
    public $max_responses;
    public $start_date;
    public $end_date;

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

    protected $listeners = ['openModal' => 'open'];

    public function mount()
    {
        if (!Gate::allows('electronicform-create')) {
            abort(403, 'ليس لديك صلاحية لإنشاء استمارات');
        }
    }

    public function render()
    {
        $formTypes = [
            'custom' => 'مخصص',
            'job_application' => 'استمارة توظيف',
            'contact_form' => 'استمارة تواصل',
            'survey' => 'استبيان',
            'registration' => 'تسجيل',
            'complaint' => 'شكوى',
            'suggestion' => 'اقتراح'
        ];

        return view('livewire.backend.electronic-forms.create-electronic-form', compact('formTypes'));
    }

    public function open()
    {
        $this->isOpen = true;
        $this->resetForm();
    }

    public function close()
    {
        $this->isOpen = false;
        $this->resetForm();
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
}
