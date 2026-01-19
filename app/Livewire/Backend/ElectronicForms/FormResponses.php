<?php

namespace App\Livewire\Backend\ElectronicForms;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Backend\ElectronicForms\ElectronicForms;
use App\Models\Backend\ElectronicForms\FormResponses as FormResponsesModel;

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

  private function baseQuery()
  {
    $query = FormResponsesModel::where('electronic_forms_id', $this->formId);

    if ($this->search) {
      $search = trim($this->search);

      $query->where(function ($q) use ($search) {
        // 1. البحث في حقل الحالة (status)
        $q->where('status', 'like', "%{$search}%")

          // 2. البحث في البيانات النصية (الإنجليزية والأرقام)
          ->orWhere('response_data', 'like', "%{$search}%");

        // 3. البحث في البيانات العربية (معالجة الـ Unicode)
        if (preg_match('/[أ-ي]/ui', $search)) {
          $unicodeSearch = trim(json_encode($search), '"');
          // الهروب المزدوج للمائل العكسي لضمان المطابقة في SQL
          $escapedSearch = str_replace('\\', '\\\\', $unicodeSearch);

          $q->orWhere('response_data', 'like', "%{$escapedSearch}%");
        }
      });
    }

    if ($this->status) {
      $query->where('status', $this->status);
    }

    return $query;
  }

  public function getResponsesProperty()
  {
    return $this->baseQuery()
      ->orderBy($this->sortField, $this->sortDirection)
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
      // تحديد العناصر الموجودة في الصفحة الحالية فقط
      $this->selectedRows = $this->responses->pluck('id')->map(fn($id) => (string) $id)->toArray();
    } else {
      $this->selectedRows = [];
    }
  }

  // 2. تصفير التحديد عند تغيير الصفحة أو البحث لضمان عدم حدوث أخطاء
  public function updatedSearch()
  {
    $this->resetPage();
    $this->selectedRows = [];
    $this->selectAll = false;
  }
  public function updatedStatus()
  {
    $this->resetPage();
    $this->selectedRows = [];
    $this->selectAll = false;
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

  public function getExportUrl($type)
  {
    $params = [
      'formId' => $this->formId,
      'search' => $this->search,
      'status' => $this->status,
      'selected' => implode(',', $this->selectedRows)
    ];

    if ($type === 'pdf') {
      return route('form-responses.export.pdf.tcpdf', $params);
    } elseif ($type === 'excel') {
      return route('form-responses.export.excel', $params); // تأكد من تعريف المسار في web.php
    }
  }

  public function getFieldLabelsProperty()
  {
    return DB::table('form_fields')
      ->where('electronic_forms_id', $this->formId)
      ->pluck('label', 'name')
      ->toArray();
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

    // حساب الإحصائيات الكلية (مستقلة عن البحث والفلاترات)
    $totalResponses = FormResponsesModel::where('electronic_forms_id', $this->formId)->count();
    $totalPending = FormResponsesModel::where('electronic_forms_id', $this->formId)
      ->where('status', 'pending')->count();
    $totalApproved = FormResponsesModel::where('electronic_forms_id', $this->formId)
      ->where('status', 'approved')->count();
    $totalRejected = FormResponsesModel::where('electronic_forms_id', $this->formId)
      ->where('status', 'rejected')->count();
    $totalUnderReview = FormResponsesModel::where('electronic_forms_id', $this->formId)
      ->where('status', 'under_review')->count();

    return view('livewire.backend.electronic-forms.form-responses', [
      'form' => $form,
      'responses' => $this->responses,
      'fieldLabels' => $this->fieldLabels,
      'statuses' => $statuses,
      'totalPending' => $totalPending,
      'totalApproved' => $totalApproved,
      'totalRejected' => $totalRejected,
      'totalUnderReview' => $totalUnderReview,
      'totalResponses' => $totalResponses,
    ]);
  }
}