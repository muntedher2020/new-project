<?php

namespace App\Http\Controllers\Backend\ElectronicForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Backend\ElectronicForms;

class ElectronicFormPrintController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:electronicform-export-pdf');
    }

    /**
     * Show print-friendly page for ElectronicForms
     */
    public function printView()
    {
        try {
            $data = ElectronicForms::all();

            return view('exports.electronicforms_print', [
                'data' => $data,
                'title' => 'تقرير الاستمارات الالكترونية',
                'generated_at' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء تحضير صفحة الطباعة: ' . $e->getMessage()], 500);
        }
    }
}