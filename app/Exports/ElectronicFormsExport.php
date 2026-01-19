<?php

namespace App\Exports;

use App\Models\Backend\ElectronicForms;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ElectronicFormsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $selectedRows;
    
    public function __construct($selectedRows = [])
    {
        $this->selectedRows = is_array($selectedRows) ? $selectedRows : [];
    }
    
    public function collection()
    {
        $query = ElectronicForms::with('user');
        
        if (!empty($this->selectedRows)) {
            $query->whereIn('id', $this->selectedRows);
        }
        
        return $query->orderBy('id', 'desc')->get();
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'العنوان',
            'الوصف',
            'الحالة',
            'اسم المستخدم',
            'البريد الإلكتروني',
            'تاريخ الإنشاء',
            'تاريخ التحديث'
        ];
    }
    
    public function map($form): array
    {
        // تنظيف البيانات من أحرف خاصة
        $title = $this->cleanData($form->title ?? '');
        $description = $this->cleanData($form->description ?? '');
        $userName = $this->cleanData($form->user->name ?? 'غير معروف');
        $userEmail = $this->cleanData($form->user->email ?? '');
        
        return [
            $form->id ?? '',
            $title,
            $description,
            $form->active ? 'مفعل' : 'غير مفعل',
            $userName,
            $userEmail,
            $form->created_at ? $form->created_at->format('Y-m-d H:i:s') : '',
            $form->updated_at ? $form->updated_at->format('Y-m-d H:i:s') : ''
        ];
    }
    
    protected function cleanData($value)
    {
        if (is_null($value) || $value === '') {
            return '';
        }
        
        // إزالة الأحرف غير القابلة للطباعة
        $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
        
        // إزالة BOM (Byte Order Mark)
        $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
        
        // تحويل HTML entities
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // إزالة علامات HTML
        $value = strip_tags($value);
        
        // إزالة المسافات الزائدة
        $value = trim($value);
        
        // تحويل إلى UTF-8
        if (!mb_check_encoding($value, 'UTF-8')) {
            $value = mb_convert_encoding($value, 'UTF-8', 'auto');
        }
        
        // إضافة علامة اقتباس في البداية لمنع تفسير الصيغ
        if (in_array(substr($value, 0, 1), ['=', '+', '-', '@'])) {
            $value = "'" . $value;
        }
        
        return $value;
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // العنوان
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3498DB']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
            
            // البيانات
            'A2:H' . ($sheet->getHighestRow()) => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'DDDDDD']
                    ]
                ]
            ],
            
            // جعل الأعمدة بعرض مناسب
            'B' => ['width' => 30],
            'C' => ['width' => 40],
            'E' => ['width' => 20],
            'F' => ['width' => 25],
            'G' => ['width' => 20],
            'H' => ['width' => 20],
        ];
    }
}