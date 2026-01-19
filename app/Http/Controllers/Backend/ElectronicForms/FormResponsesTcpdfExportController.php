<?php

namespace App\Http\Controllers\Backend\ElectronicForms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\ElectronicForms\ElectronicForms;
use App\Models\Backend\ElectronicForms\FormResponses as FormResponsesModel;
use Illuminate\Support\Facades\DB;
use TCPDF;

class FormResponsesTcpdfExportController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function exportPdf(Request $request, $formId)
  {
    try {
      $form = ElectronicForms::findOrFail($formId);

      // 1. جلب خريطة الأسماء العربية للحقول
      // نفترض أن الجدول هو form_fields والحقول هي field_name و field_label
      $fieldLabels = DB::table('form_fields')
        ->where('electronic_forms_id', $formId)
        ->pluck('label', 'name')
        ->toArray();

      $query = FormResponsesModel::where('electronic_forms_id', $formId);

      // تطبيق الفلاتر
      if ($request->filled('status')) {
        $query->where('status', $request->status);
      }
      if ($request->filled('selected')) {
        $query->whereIn('id', explode(',', $request->selected));
      }

      $responses = $query->orderBy('created_at', 'desc')->get();

      // إنشاء PDF
      $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

      $pdf->SetCreator('Laravel System');
      $pdf->SetTitle('تقرير نتائج: ' . $form->title);

      // إعدادات اللغة والخط
      $pdf->setLanguageArray([
        'a_meta_charset' => 'UTF-8',
        'a_meta_dir' => 'rtl',
        'a_meta_language' => 'ar',
        'w_page' => 'صفحة'
      ]);

      $pdf->SetFont('freeserif', '', 12);
      $pdf->SetMargins(10, 10, 10);
      $pdf->AddPage();

      // العنوان
      $pdf->SetFont('freeserif', 'B', 16);
      $pdf->Cell(0, 10, 'نتائج استمارة: ' . $form->title, 0, 1, 'C');
      $pdf->Ln(5);

      // رأس الجدول
      $pdf->SetFont('freeserif', 'B', 10);
      $pdf->SetFillColor(220, 220, 220);
      $pdf->Cell(20, 10, 'ID', 1, 0, 'C', 1);
      $pdf->Cell(30, 10, 'الحالة', 1, 0, 'C', 1);
      $pdf->Cell(35, 10, 'التاريخ', 1, 0, 'C', 1);
      $pdf->Cell(192, 10, 'بيانات الاستمارة (بالأسماء العربية)', 1, 1, 'C', 1);

      // محتوى الجدول
      $pdf->SetFont('freeserif', '', 9);
      $fill = false;

      // المتغير المطلوب للتخزين التراكمي حسب تعليماتك السابقة
      $totalStorage = 0;

      foreach ($responses as $row) {
        $pdf->SetFillColor($fill ? 245 : 255);

        $pdf->Cell(20, 8, $row->id, 1, 0, 'C', 1);

        // ترجمة الحالة للعربية للعرض
        $statusAr = [
          'pending' => 'قيد الانتظار',
          'approved' => 'موافق عليه',
          'rejected' => 'مرفوض',
          'under_review' => 'تحت المراجعة'
        ];
        $pdf->Cell(30, 8, $statusAr[$row->status] ?? $row->status, 1, 0, 'C', 1);

        $pdf->Cell(35, 8, $row->created_at->format('Y-m-d'), 1, 0, 'C', 1);

        // 2. تجميع البيانات مع تحويل المفاتيح لأسماء عربية
        $dataText = "";
        if (is_array($row->response_data)) {
          foreach ($row->response_data as $key => $val) {
            // جلب الاسم العربي من الخريطة، إذا لم يوجد نستخدم المفتاح الأصلي
            $label = $fieldLabels[$key] ?? $key;

            $valText = is_array($val) ? implode(', ', $val) : $val;
            $dataText .= "• " . $label . ": " . $valText . " \n";

            // مثال لإضافة التخزين التراكمي إذا كان الحقل يخص التخزين
            if (str_contains($key, 'storage') || str_contains($key, 'capacity')) {
              $totalStorage += (float)$val;
            }
          }
        }

        // استخدام MultiCell للسماح بتعدد الأسطر داخل الخلية لبيانات الاستمارة
        $pdf->MultiCell(192, 8, $dataText, 1, 'R', $fill, 1);
        $fill = !$fill;
      }

      // إضافة سطر التخزين التراكمي في نهاية التقرير إذا رغبت
      if ($totalStorage > 0) {
        $pdf->Ln(2);
        $pdf->SetFont('freeserif', 'B', 11);
        $pdf->Cell(0, 10, 'إجمالي التخزين السنوي التراكمي للموانئ المحددة: ' . $totalStorage, 0, 1, 'L');
      }

      return $pdf->Output("responses-{$formId}.pdf", 'I');
    } catch (\Exception $e) {
      return back()->with('error', 'خطأ في إنشاء PDF: ' . $e->getMessage());
    }
  }
}
