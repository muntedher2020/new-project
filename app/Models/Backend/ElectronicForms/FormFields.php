<?php

namespace App\Models\Backend\ElectronicForms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormFields extends Model
{
    protected $guarded = [];
    protected $table = "form_fields";

    protected $attributes = [
        'type' => 'text',
        'required' => false,
        'sort_order' => 0,
    ];

    protected $casts = [
        'required' => 'boolean',
        'options' => 'array',
        'validation_rules' => 'array',
        'settings' => 'array',
    ];

    // دالة لتعيين القيم الافتراضية عند الإنشاء
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // إنشاء name تلقائيًا من label إذا لم يكن موجودًا
            if (empty($model->name) && !empty($model->label)) {
                $model->name = \Illuminate\Support\Str::slug($model->label, '_');
            }
            
            // تعيين order إذا لم يكن موجودًا
            if (empty($model->sort_order)) {
                $model->sort_order = static::where('electronic_forms_id', $model->electronic_forms_id)->max('sort_order') + 1;
            }
            
            // تعيين قواعد تحقق افتراضية بناءً على النوع
            if (empty($model->validation_rules)) {
                $model->validation_rules = $model->getDefaultValidationRules();
            }
        });
    }

    // الحصول على قواعد التحقق الافتراضية
    public function getDefaultValidationRules(): string
    {
        $rules = [$this->required ? 'required' : 'nullable'];
        
        switch ($this->type) {
            case 'email':
                $rules[] = 'email';
                $rules[] = 'max:255';
                break;
            case 'number':
                $rules[] = 'numeric';
                break;
            case 'file':
                $rules[] = 'file';
                $rules[] = 'max:5120';
                break;
            case 'url':
                $rules[] = 'url';
                $rules[] = 'max:255';
                break;
            case 'date':
                $rules[] = 'date';
                break;
            case 'textarea':
                $rules[] = 'string';
                $rules[] = 'max:1000';
                break;
            default:
                $rules[] = 'string';
                $rules[] = 'max:255';
        }
        
        return implode('|', array_unique($rules));
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(ElectronicForms::class);
    }

    public function getValidationRules(): array
    {
        $rules = [];

        if ($this->required) {
            $rules[] = 'required';
        }

        switch ($this->type) {
            case 'email':
                $rules[] = 'email';
                break;
            case 'number':
                $rules[] = 'numeric';
                break;
            case 'url':
                $rules[] = 'url';
                break;
            case 'date':
                $rules[] = 'date';
                break;
        }

        if ($this->validation_rules) {
            $rules = array_merge($rules, $this->validation_rules);
        }

        return $rules;
    }

    // أنواع الحقول المدعومة
    public static function getFieldTypes(): array
    {
        return [
            'text' => 'نص',
            'textarea' => 'مربع نص كبير',
            'email' => 'بريد إلكتروني',
            'number' => 'رقم',
            'select' => 'قائمة منسدلة',
            'checkbox' => 'مربع اختيار',
            'radio' => 'زر اختيار',
            'file' => 'ملف',
            'date' => 'تاريخ',
            'time' => 'وقت',
            'datetime-local' => 'تاريخ ووقت',
            'color' => 'لون',
            'range' => 'شريط تمرير',
            'tel' => 'هاتف',
            'url' => 'رابط',
        ];
    }
}
