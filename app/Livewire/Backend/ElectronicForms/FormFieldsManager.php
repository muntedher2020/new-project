<?php

namespace App\Livewire\Backend\ElectronicForms;

use App\Models\Form;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\ElectronicForms\FormFields;
use App\Models\Backend\ElectronicForms\ElectronicForms;

class FormFieldsManager extends Component
{
    public $formId;
    public $form; // إضافة خاصية للكائن
    public $fields = [];
    public $showFieldModal = false;
    public $reordering = false; // وضع إعادة الترتيب

    // حقل جديد
    #[Validate('required|string|max:255')]
    public $label = '';
    
    #[Validate('required|string|alpha_dash|max:100')]
    public $name = '';
    
     #[Validate('required|in:text,textarea,email,number,select,checkbox,radio,file,date,time,datetime-local,color,range,tel,url')]
    public $type = 'text'; 
    
    public $options = '';
    public $placeholder = '';
    public $description = '';
    public $required = false;
    public $validation_rules = '';
    public $fieldId = null;
    public $fieldLabel = null;
    public $fieldName = null;
    public $isReordering = true;
    
    // قائمة بالحقول التي تحتاج خيارات
    protected $fieldsWithOptions = ['select', 'radio', 'checkbox'];
    
    // قائمة بالحقول التي تحتاج placeholder
    protected $fieldsWithPlaceholder = ['text', 'email', 'number', 'tel', 'url', 'textarea'];
    
    // الحقول التي تحتاج قواعد تحقق خاصة
    protected $fieldsWithValidation = ['email', 'number', 'file', 'url'];

    protected $listeners = [
        'confirmDeleteField',
    ];

     // التعديل هنا
     public function mount($formId)
    {
        if (is_numeric($formId)) {
            $this->formId = $formId;
            $this->form = ElectronicForms::findOrFail($formId);
        } elseif ($formId instanceof ElectronicForms) {
            $this->form = $formId;
            $this->formId = $formId;
        }
        
        $this->loadFields();
        $this->initializeFieldDefaults();
    }
    
    // تهيئة القيم الافتراضية بناءً على النوع
    public function initializeFieldDefaults()
    {
        if (!$this->fieldId) {
            $this->setDefaultValuesForType($this->type);
        }
    }

    public function render()
    {
        return view('livewire.backend.electronic-forms.form-fields-manager', [
            'fieldTypes' => FormFields::getFieldTypes(),
            'showOptions' => in_array($this->type, $this->fieldsWithOptions),
            'showPlaceholder' => in_array($this->type, $this->fieldsWithPlaceholder),
        ]);
    }

    // تحديث الحقول عند تغيير النوع
    public function updatedType($value)
    {
        $this->setDefaultValuesForType($value);
        
        // إعادة تعيين القيم غير المناسبة للنوع الجديد
        if (!in_array($value, $this->fieldsWithOptions)) {
            $this->options = '';
        }
        
        if (!in_array($value, $this->fieldsWithPlaceholder)) {
            $this->placeholder = '';
        }
        
        // إضافة قواعد تحقق افتراضية بناءً على النوع
        $this->setDefaultValidationRules($value);
    }

     // تعيين قواعد التحقق الافتراضية
    protected function setDefaultValidationRules($type)
    {
        $rules = [];
        
        if ($this->required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        switch ($type) {
            case 'email':
                $rules[] = 'email';
                $rules[] = 'max:255';
                break;
            case 'number':
                $rules[] = 'numeric';
                break;
            case 'file':
                $rules[] = 'file';
                $rules[] = 'max:5120';
                break;
            case 'url':
                $rules[] = 'url';
                $rules[] = 'max:255';
                break;
            case 'date':
                $rules[] = 'date';
                break;
            default:
                $rules[] = 'string';
                $rules[] = 'max:255';
        }

        $this->validation_rules = implode('|', array_unique($rules));
    }

    // تعيين القيم الافتراضية بناءً على النوع
    protected function setDefaultValuesForType($type)
    {
        $defaults = [
            'text' => [
                'placeholder' => 'أدخل النص هنا',
                'validation_rules' => 'string|max:255'
            ],
            'email' => [
                'placeholder' => 'example@domain.com',
                'validation_rules' => 'email|max:255'
            ],
            'number' => [
                'placeholder' => 'أدخل رقمًا',
                'validation_rules' => 'numeric'
            ],
            'textarea' => [
                'placeholder' => 'أدخل النص هنا...',
                'validation_rules' => 'string|max:1000'
            ],
            'select' => [
                'options' => "الخيار الأول\nالخيار الثاني\nالخيار الثالث",
                'validation_rules' => 'string'
            ],
            'checkbox' => [
                'options' => "موافق على الشروط",
                'validation_rules' => 'sometimes|accepted'
            ],
            'radio' => [
                'options' => "ذكر\nأنثى",
                'validation_rules' => 'required'
            ],
            'file' => [
                'validation_rules' => 'file|max:5120', // 5MB
                'description' => 'الحد الأقصى لحجم الملف: 5 ميجابايت'
            ],
            'date' => [
                'placeholder' => 'YYYY-MM-DD',
                'validation_rules' => 'date'
            ],
            'tel' => [
                'placeholder' => '+966 5X XXX XXXX',
                'validation_rules' => 'string|max:20'
            ],
            'url' => [
                'placeholder' => 'https://example.com',
                'validation_rules' => 'url|max:255'
            ],
        ];

        if (isset($defaults[$type])) {
            foreach ($defaults[$type] as $key => $value) {
                if (empty($this->{$key}) || $this->fieldId === null) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function loadFields()
    {
        $this->fields = $this->form->fields()
            ->orderBy('sort_order', 'asc')
            ->get()
            ->toArray();
    }

    // تفعيل/تعطيل وضع إعادة الترتيب
    public function toggleReorder()
    {
        $this->reordering = !$this->reordering;
    }

     // تفعيل وضع إعادة الترتيب
    public function enableReorder()
    {
        $this->isReordering = true;
        $this->dispatch('reorder-enabled');
    }

    // تعطيل وضع إعادة الترتيب
    public function disableReorder()
    {
        $this->isReordering = false;
        $this->dispatch('reorder-disabled');
    }

    // تحديث الترتيب بعد السحب والإفلات
    public function updateOrder($order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order as $index => $item) {
                FormFields::where('id', $item['value'])->update([
                    'sort_order' => $index + 1
                ]);
            }
        });

        $this->loadFields();
        $this->dispatch('show-toast', type: 'success', message: 'تم حفظ الترتيب بنجاح');
    }

    // إعادة تعيين الترتيب
    public function resetOrder()
    {
        $fields = $this->form->fields()->orderBy('created_at')->get();
        
        foreach ($fields as $index => $field) {
            $field->update(['sort_order' => $index + 1]);
        }
        
        $this->loadFields();
        $this->dispatch('show-toast', type: 'success', message: 'تم إعادة تعيين الترتيب');
    }

    // حفظ الترتيب الجديد
    public function updateFieldOrder($orderedIds)
    {
        try {
            DB::beginTransaction();
            
            foreach ($orderedIds as $order => $id) {
                FormFields::where('id', $id)->update(['sort_order' => $order + 1]);
            }
            
            DB::commit();
            
            $this->loadFields();
            session()->flash('success', 'تم حفظ الترتيب بنجاح');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'حدث خطأ أثناء حفظ الترتيب');
        }
    }

    // نقل حقل للأعلى
    public function moveUp($fieldId)
    {
        $currentField = FormFields::findOrFail($fieldId);
        $previousField = FormFields::where('electronic_forms_id', $this->formId)
            ->where('sort_order', '<', $currentField->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();
            
        if ($previousField) {
            $currentOrder = $currentField->sort_order;
            $currentField->sort_order = $previousField->sort_order;
            $previousField->sort_order = $currentOrder;
            
            $currentField->save();
            $previousField->save();
            
            $this->loadFields();
            session()->flash('success', 'تم نقل الحقل للأعلى');
        }
    }

    // نقل حقل للأسفل
    public function moveDown($fieldId)
    {
        $currentField = FormFields::findOrFail($fieldId);
        $nextField = FormFields::where('electronic_forms_id', $this->formId)
            ->where('sort_order', '>', $currentField->sort_order)
            ->orderBy('sort_order')
            ->first();
            
        if ($nextField) {
            $currentOrder = $currentField->sort_order;
            $currentField->sort_order = $nextField->sort_order;
            $nextField->sort_order = $currentOrder;
            
            $currentField->save();
            $nextField->save();
            
            $this->loadFields();
            session()->flash('success', 'تم نقل الحقل للأسفل');
        }
    }

    // في FormFieldsManager.php
    public function swapFields($fieldId1, $fieldId2)
    {
        try {
            $field1 = FormFields::findOrFail($fieldId1);
            $field2 = FormFields::findOrFail($fieldId2);
            
            $tempOrder = $field1->sort_order;
            $field1->sort_order = $field2->sort_order;
            $field2->sort_order = $tempOrder;
            
            $field1->save();
            $field2->save();
            
            $this->loadFields();
            
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء تبديل الحقول');
        }
    }

    // حفظ/تعديل حقل
    public function saveField()
    {
        $this->validate();

        // إنشاء الاسم تلقائيًا إذا لم يتم توفيره
        if (empty($this->name)) {
            $this->name = Str::slug($this->label, '_');
        }

        DB::transaction(function () {
            $data = [
                'label' => $this->label,
                'name' => $this->name,
                'type' => $this->type,
                'options' => $this->options ? explode("\n", $this->options) : null,
                'placeholder' => $this->placeholder,
                'description' => $this->description,
                'required' => $this->required,
                'validation_rules' => $this->validation_rules,
                'sort_order' => $this->form->fields()->count(),
            ];

            if ($this->fieldId) {
                FormFields::find($this->fieldId)->update($data);
                $this->dispatch('showSuccess', 'تم تحديث الحقل بنجاح');
                $this->dispatch('formUpdated');
                //session()->flash('message', 'تم تحديث الحقل بنجاح');
            } else {
                $this->form->fields()->create($data);
                $this->dispatch('showSuccess', 'تم إضافة الحقل بنجاح');
                $this->dispatch('formUpdated');
                //session()->flash('message', 'تم إضافة الحقل بنجاح');
            }

            $this->resetField();
            $this->showFieldModal = false;
            $this->loadFields();
        });
    }

    public function createField()
    {
        $this->resetField();
        $this->showFieldModal = true;
    }

    // تعديل حقل
    public function editField($fieldId)
    {
        $field = FormFields::findOrFail($fieldId);
        
        $this->fieldId = $field->id;
        $this->label = $field->label;
        $this->name = $field->name;
        $this->type = $field->type;
        $this->options = $field->options ? implode("\n", $field->options) : '';
        $this->placeholder = $field->placeholder;
        $this->description = $field->description;
        $this->required = $field->required;
        $this->validation_rules = $field->validation_rules;

        $this->showFieldModal = true;
    }

    // حذف حقل
    public function deleteField($fieldId)
    {
        $FormFields = FormFields::findOrFail($fieldId);
        $this->fieldLabel = $FormFields->label;
        $this->fieldName = $FormFields->name;
        $this->dispatch('confirmDelete', [
            'fieldId' => $fieldId,
            'message' => 'هل أنت متأكد من حذف هذا الحقل؟',
        ]);
    }

    // حذف حقل مع إعادة ترتيب الباقي
    public function confirmDeleteField($fieldId)
    {
        $field = FormFields::findOrFail($fieldId);
        $deletedOrder = $field->sort_order;
        
        $field->delete();
        
        // إعادة ترتيب الحقول المتبقية
        FormFields::where('electronic_forms_id', $this->formId)
            ->where('sort_order', '>', $deletedOrder)
            ->decrement('sort_order');
        
        $this->loadFields();
        session()->flash('success', 'تم حذف الحقل بنجاح');
    }

    /* public function confirmDeleteField($fieldId)
    {
        FormFields::findOrFail($fieldId)->delete();
        $this->dispatch('showSuccess', 'تم حذف الحقل بنجاح');
        //session()->flash('message', 'تم حذف الحقل بنجاح');
        $this->loadFields();
    } */

    // ترتيب الحقول
    /* public function updateFieldOrder($orderedIds)
    {
        foreach ($orderedIds as $order => $id) {
            FormFields::where('id', $id)->update(['sort_order' => $order]);
        }
        
        $this->loadFields();
        $this->dispatch('showSuccess', 'تم تحديث الترتيب بنجاح');
        //session()->flash('message', 'تم تحديث الترتيب بنجاح');
    } */

    public function resetField()
    {
        $this->reset([
            'fieldId', 'label', 'name', 'type', 
            'options', 'placeholder', 'description',
            'required', 'validation_rules'
        ]);
    }
}
