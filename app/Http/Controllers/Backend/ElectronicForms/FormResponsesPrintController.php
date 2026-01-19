<?php

namespace App\Http\Controllers\Backend\ElectronicForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Backend\ElectronicForms\ElectronicForms;
use App\Models\Backend\ElectronicForms\FormResponses as FormResponsesModel;

class FormResponsesPrintController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function printView(Request $request, $formId)
  {
    $form = ElectronicForms::findOrFail($formId);

    $query = FormResponsesModel::where('electronic_forms_id', $formId);

    // تطبيق الفلاتر إن وجدت
    if ($request->has('status') && $request->status) {
      $query->where('status', $request->status);
    }

    if ($request->has('search') && $request->search) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('ip_address', 'like', "%{$search}%")
          ->orWhere('browser_fingerprint', 'like', "%{$search}%")
          ->orWhere('submission_hash', 'like', "%{$search}%");
      });
    }

    $responses = $query->orderBy('created_at', 'desc')->get();

    $statuses = [
      'pending' => 'قيد الانتظار',
      'approved' => 'موافق عليه',
      'rejected' => 'مرفوض',
      'under_review' => 'تحت المراجعة',
    ];

    return view(
      'content.Backend.ElectronicForms.responses.print',
      compact('form', 'responses', 'statuses')
    );
  }
}
