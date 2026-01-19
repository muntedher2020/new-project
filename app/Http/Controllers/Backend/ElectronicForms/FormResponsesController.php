<?php

namespace App\Http\Controllers\Backend\ElectronicForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
    $form = ElectronicForms::findOrFail($formId);
    $response = FormResponsesModel::where('electronic_forms_id', $formId)->findOrFail($responseId);

    $response->delete();

    return redirect()->route('form-responses.index', $form->id)
      ->with('success', 'تم حذف الإجابة بنجاح');
  }

  public function bulkUpdate(Request $request, $formId)
  {
    $form = ElectronicForms::findOrFail($formId);

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
    $form = ElectronicForms::findOrFail($formId);

    $validated = $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'required|integer|exists:form_responses,id',
    ]);

    FormResponsesModel::whereIn('id', $validated['ids'])
      ->where('electronic_forms_id', $formId)
      ->delete();

    return back()->with('success', 'تم حذف الإجابات المحددة بنجاح');
  }
}
