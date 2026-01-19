<?php

namespace App\Http\Controllers\Backend\ElectronicForms;

use Illuminate\Http\Request;
use App\Exports\FormResponsesExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Backend\ElectronicForms\ElectronicForms;
use App\Models\Backend\ElectronicForms\FormResponses as FormResponsesModel;

class FormResponsesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index($formId)
  {
    $form = ElectronicForms::findOrFail($formId);
    return view('content.Backend.ElectronicForms.responses.index', compact('form'));
  }

  public function show($formId, $responseId)
  {
    $form = ElectronicForms::findOrFail($formId);
    $response = FormResponsesModel::where('electronic_forms_id', $formId)->findOrFail($responseId);
    return view('content.Backend.ElectronicForms.responses.show', compact('form', 'response'));
  }

  public function update(Request $request, $formId, $responseId)
  {
    $form = ElectronicForms::findOrFail($formId);
    $response = FormResponsesModel::where('electronic_forms_id', $formId)->findOrFail($responseId);

    $validated = $request->validate([
      'status' => 'required|in:pending,approved,rejected,under_review',
      'notes' => 'nullable|string|max:1000',
    ]);

    $response->update($validated);

    return redirect()->route('form-responses.index', $form->id)
      ->with('success', 'تم تحديث الإجابة بنجاح');
  }

  public function destroy($formId, $responseId)
  {
    $response = FormResponsesModel::where('electronic_forms_id', $formId)->findOrFail($responseId);
    $response->delete();

    return redirect()->route('form-responses.index', $formId)
      ->with('success', 'تم حذف الإجابة بنجاح');
  }

  public function bulkUpdate(Request $request, $formId)
  {
    $validated = $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'required|integer|exists:form_responses,id',
      'status' => 'required|in:pending,approved,rejected,under_review',
    ]);

    FormResponsesModel::whereIn('id', $validated['ids'])
      ->where('electronic_forms_id', $formId)
      ->update(['status' => $validated['status']]);

    return back()->with('success', 'تم تحديث الإجابات المحددة بنجاح');
  }

  public function bulkDelete(Request $request, $formId)
  {
    $validated = $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'required|integer|exists:form_responses,id',
    ]);

    FormResponsesModel::whereIn('id', $validated['ids'])
      ->where('electronic_forms_id', $formId)
      ->delete();

    return back()->with('success', 'تم حذف الإجابات المحددة بنجاح');
  }

  /**
   * تصدير البيانات إلى إكسل بالأسماء العربية
   */
  public function exportExcel(Request $request, $formId)
  {
    $query = FormResponsesModel::where('electronic_forms_id', $formId);

    if ($request->status) $query->where('status', $request->status);
    if ($request->selected) $query->whereIn('id', explode(',', $request->selected));

    $responses = $query->orderBy('created_at', 'desc')->get();

    // جلب الأسماء العربية من جدول الحقول بناءً على الصورة المرفقة
    $fieldLabels = DB::table('form_fields')
      ->where('electronic_forms_id', $formId)
      ->pluck('label', 'name') // 'label' هو العربي و 'name' هو الإنجليزي في الصورة
      ->toArray();

    $processedData = [];
    $allArabicLabels = [];

    // تحديد كافة الأعمدة العربية الفريدة الموجودة في الردود
    foreach ($responses as $resp) {
      if (is_array($resp->response_data)) {
        foreach ($resp->response_data as $key => $value) {
          $label = $fieldLabels[$key] ?? $key;
          if (!in_array($label, $allArabicLabels)) {
            $allArabicLabels[] = $label;
          }
        }
      }
    }

    foreach ($responses as $resp) {
      // تحويل الحالة للعربية
      $statusAr = [
        'pending' => 'قيد الانتظار',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'under_review' => 'تحت المراجعة'
      ];

      $row = [
        'id' => $resp->id,
        'status' => $statusAr[$resp->status] ?? $resp->status,
        'created_at' => $resp->created_at->format('Y-m-d H:i'),
      ];

      // تعبئة البيانات لكل عمود عربي
      foreach ($allArabicLabels as $label) {
        // البحث عن المفتاح الإنجليزي الذي يقابل هذا العنوان العربي
        $englishKey = array_search($label, $fieldLabels);
        $keyToUse = ($englishKey !== false) ? $englishKey : $label;

        $answer = $resp->response_data[$keyToUse] ?? '-';
        $row[$label] = is_array($answer) ? implode(', ', $answer) : $answer;
      }

      $processedData[] = $row;
    }

    // تمرير المصفوفة المعالجة (Processed Data)
    return Excel::download(
      new FormResponsesExport(collect($processedData), $allArabicLabels),
      "responses-form-{$formId}.xlsx"
    );
  }

  /**
   * دالة مساعدة لترجمة الحالات
   */
  private function translateStatus($status)
  {
    $statuses = [
      'pending' => 'قيد الانتظار',
      'approved' => 'موافق عليه',
      'rejected' => 'مرفوض',
      'under_review' => 'تحت المراجعة',
    ];
    return $statuses[$status] ?? $status;
  }
}
