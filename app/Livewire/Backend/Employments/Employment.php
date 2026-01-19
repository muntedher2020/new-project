<?php

namespace App\Livewire\Backend\Employments;

use Livewire\Component;
use App\Models\Backend\ElectronicForms\ElectronicForms;

class Employment extends Component
{
    public $ElectronicForms;
    public $search = '';
    public $form_type = '';

    public function mount()
    {
        $this->ElectronicForms = ElectronicForms::all();
    }
    public function render()
    {
        $forms = ElectronicForms::query()
            ->where('active', true)
            //->where('form_type', 'employment') // إذا كان لديك نوع توظيف
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->form_type, function ($query) {
                $query->where('form_type', $this->form_type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('livewire.backend.employments.employment', [
            'forms' => $forms
        ]);
    }
}
