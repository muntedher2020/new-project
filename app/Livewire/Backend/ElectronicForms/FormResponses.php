<?php

namespace App\Livewire\Backend\ElectronicForms;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Backend\ElectronicForms\FormResponses as FormResponsesModel;
use App\Models\Backend\ElectronicForms\ElectronicForms;

class FormResponses extends Component
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

  protected $queryString = [
    'search' => ['except' => ''],
    'status' => ['except' => ''],
    'sortField' => ['except' => 'created_at'],
    'sortDirection' => ['except' => 'desc'],
    'perPage' => ['except' => 20],
  ];

  protected $listeners = [
    'refresh' => '$refresh',
  ];

  public function mount($formId)
  {
    $this->formId = $formId;
    // التحقق من وجود الاستمارة
    $form = ElectronicForms::findOrFail($formId);
  }

  public function getResponsesProperty()
  {
    $query = FormResponsesModel::where('electronic_forms_id', $this->formId);

    if ($this->search) {
      $query->where(function ($q) {
        $q->where('ip_address', 'like', "%{$this->search}%")
          ->orWhere('browser_fingerprint', 'like', "%{$this->search}%")
          ->orWhere('submission_hash', 'like', "%{$this->search}%");
      });
    }

    if ($this->status) {
      $query->where('status', $this->status);
    }

    return $query->orderBy($this->sortField, $this->sortDirection)
      ->paginate($this->perPage);
  }

  public function sortBy($field)
  {
    if ($this->sortField === $field) {
      $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
      $this->sortField = $field;
      $this->sortDirection = 'asc';
    }
  }

  public function updatedSelectAll($value)
  {
    if ($value) {
      $this->selectedRows = $this->responses->pluck('id')->map(fn($id) => (string) $id)->toArray();
    } else {
      $this->selectedRows = [];
    }
  }

  public function updatedSelectedRows()
  {
    $this->selectAll = false;
  }

  public function toggleStatus($responseId)
  {
    $response = FormResponsesModel::findOrFail($responseId);
    $statuses = ['pending', 'approved', 'rejected', 'under_review'];
    $currentIndex = array_search($response->status, $statuses);
    $response->status = $statuses[($currentIndex + 1) % count($statuses)];
    $response->save();

    $this->dispatch('refresh');
  }

  public function deleteSelected()
  {
    if (empty($this->selectedRows)) {
      return;
    }

    FormResponsesModel::whereIn('id', $this->selectedRows)->delete();
    $this->selectedRows = [];
    $this->selectAll = false;
    $this->dispatch('refresh');
  }

  public function changeSelectedStatus($newStatus)
  {
    if (empty($this->selectedRows)) {
      return;
    }

    FormResponsesModel::whereIn('id', $this->selectedRows)->update(['status' => $newStatus]);
    $this->selectedRows = [];
    $this->selectAll = false;
    $this->dispatch('refresh');
  }

  public function exportSelected()
  {
    // سيتم التعامل معه في الـ Controller
    $this->dispatch('export-selected', selectedRows: $this->selectedRows);
  }

  public function render()
  {
    $form = ElectronicForms::findOrFail($this->formId);

    $statuses = [
      'pending' => 'قيد الانتظار',
      'approved' => 'موافق عليه',
      'rejected' => 'مرفوض',
      'under_review' => 'تحت المراجعة',
    ];

    $totalPending = FormResponsesModel::where('electronic_forms_id', $this->formId)->where('status', 'pending')->count();
    $totalApproved = FormResponsesModel::where('electronic_forms_id', $this->formId)->where('status', 'approved')->count();
    $totalRejected = FormResponsesModel::where('electronic_forms_id', $this->formId)->where('status', 'rejected')->count();
    $totalResponses = FormResponsesModel::where('electronic_forms_id', $this->formId)->count();

    return view('livewire.backend.electronic-forms.form-responses', [
      'form' => $form,
      'responses' => $this->responses,
      'statuses' => $statuses,
      'totalPending' => $totalPending,
      'totalApproved' => $totalApproved,
      'totalRejected' => $totalRejected,
      'totalResponses' => $totalResponses,
    ]);
  }
}
