<?php

namespace App\Http\Controllers\Backend\ElectronicForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Backend\ElectronicForms\ElectronicForms;
use App\Models\Backend\ElectronicForms\FormResponses as FormResponsesModel;
use TCPDF;

class FormResponsesTcpdfExportController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function exportPdf(Request $request, $formId)
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

    // إنشاء PDF باستخدام TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // تعيين معلومات الوثيقة
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('News Website');
    $pdf->SetTitle('نتائج - ' . $form->title);
    $pdf->SetSubject('نتائج الاستمارة');

    // تعيين الهوامش
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // إضافة صفحة
    $pdf->AddPage();

    // تعيين الخط
    $pdf->SetFont('dejavusans', '', 12);

    // العنوان
    $pdf->SetFont('dejavusans-bold', '', 16);
    $pdf->Cell(0, 10, 'نتائج الاستمارة: ' . $form->title, 0, 1, 'R');

    $pdf->SetFont('dejavusans', '', 10);
    $pdf->Cell(0, 5, 'التاريخ: ' . date('Y-m-d H:i:s'), 0, 1, 'R');
    $pdf->Ln(5);

    // الجدول
    $pdf->SetFont('dejavusans-bold', '', 11);
    $pdf->SetFillColor(200, 220, 255);

    // رؤوس الجدول
    $pdf->Cell(15, 7, '#', 1, 0, 'C', true);
    $pdf->Cell(40, 7, 'المستخدم', 1, 0, 'R', true);
    $pdf->Cell(60, 7, 'الإجابات', 1, 0, 'R', true);
    $pdf->Cell(30, 7, 'الحالة', 1, 0, 'R', true);
    $pdf->Cell(35, 7, 'التاريخ', 1, 1, 'R', true);

    // البيانات
    $pdf->SetFont('dejavusans', '', 9);
    $pdf->SetFillColor(240, 245, 250);

    $count = 0;
    foreach ($responses as $response) {
      $count++;
      $fill = ($count % 2 == 0) ? true : false;

      // رقم التقديم
      $pdf->Cell(15, 6, $response->id, 1, 0, 'C', $fill);

      // المستخدم
      $userName = $response->user ? $response->user->name : 'غير مسجل';
      $pdf->Cell(40, 6, $userName, 1, 0, 'R', $fill);

      // الإجابات (معاينة)
      $responseDataPreview = '';
      if ($response->response_data && is_array($response->response_data)) {
        $items = array_slice($response->response_data, 0, 1);
        foreach ($items as $question => $answer) {
          $answerText = is_array($answer) ? implode(', ', $answer) : $answer;
          $responseDataPreview .= substr($question, 0, 20) . ': ' . substr($answerText, 0, 20);
        }
      }
      $pdf->Cell(60, 6, substr($responseDataPreview, 0, 40), 1, 0, 'R', $fill);

      // الحالة
      $statusLabel = $statuses[$response->status] ?? $response->status;
      $pdf->Cell(30, 6, $statusLabel, 1, 0, 'R', $fill);

      // التاريخ
      $pdf->Cell(35, 6, $response->created_at->format('Y-m-d H:i'), 1, 1, 'R', $fill);
    }

    $fileName = 'نتائج_' . $form->slug . '_' . date('Y-m-d_H-i-s') . '.pdf';

    return $pdf->Output($fileName, 'D');
  }
}
