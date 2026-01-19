<?php

namespace App\Livewire\Backend\ElectronicForms;

use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Models\Backend\ElectronicForms\ElectronicForms;

class EditElectronicForm extends Component
{
    public $isOpen = false;
    public $formId;
    
    // خصائص النموذج
    public $title = '';
    public $description = '';
    public $active = false;
    public $require_login = false;
    public $allow_multiple = false;
    public $max_responses;
    public $start_date;
    public $end_date;
    
    // خصائص الاستمارة للعرض فقط
    public $formSlug = '';
    public $fieldsCount = 0;
    public $responsesCount = 0;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'active' => 'boolean',
        'require_login' => 'boolean',
        'allow_multiple' => 'boolean',
        'max_responses' => 'nullable|integer|min:1',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
    ];

    protected $listeners = ['openEditModal' => 'open'];

    public function mount()
    {
        if (!Gate::allows('electronicform-edit')) {
            abort(403, 'ليس لديك صلاحية لتعديل الاستمارات');
        }
    }

    public function render()
    {
        $form = null;
        if ($this->formId) {
            $form = ElectronicForms::find($this->formId);
        }

        return view('livewire.backend.electronic-forms.edit-electronic-form', compact('form'));
    }

    public function open($formId)
    {
        $this->formId = $formId;
        $this->loadFormData();
        $this->isOpen = true;
        
        // إرسال حدث لإظهار المودال
        $this->dispatch('showEditModal');
    }

    public function close()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'formId', 'title', 'description', 'active',
            'require_login', 'allow_multiple', 'max_responses',
            'start_date', 'end_date', 'formSlug',
            'fieldsCount', 'responsesCount'
        ]);
        $this->resetErrorBag();
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
            $this->close();

        } catch (\Exception $e) {
            $this->dispatch('showError', 'حدث خطأ أثناء تحديث الاستمارة: ' . $e->getMessage());
        }
    }
}
