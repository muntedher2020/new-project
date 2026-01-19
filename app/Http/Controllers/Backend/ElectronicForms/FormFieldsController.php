<?php

namespace App\Http\Controllers\Backend\ElectronicForms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Backend\ElectronicForms\FormFields;
use App\Models\Backend\ElectronicForms\ElectronicForms;

class FormFieldsController extends Controller
{
    /**
     * عرض جميع حقول استمارة معينة
     */
    public function index(ElectronicForms $form)
    {
        try {
            $fields = $form->fields()
                ->orderBy('sort_order', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'fields' => $fields,
                'form' => [
                    'id' => $form->id,
                    'title' => $form->title,
                    'slug' => $form->slug
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching form fields: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الحقول'
            ], 500);
        }
    }

    /**
     * عرض صفحة إدارة حقول الاستمارة (واجهة المستخدم)
     */
    public function show(ElectronicForms $form)
    {
        // التحقق من الصلاحيات
        if (!auth()->user()->can('form-field-manage')) {
            abort(403, 'ليس لديك صلاحية لإدارة حقول الاستمارة');
        }

        $fields = $form->fields()
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('content.Backend.ElectronicForms.Fields.index', compact('form', 'fields'));
    }

    /**
     * عرض نموذج إضافة حقل جديد
     */
    public function create(ElectronicForms $form)
    {
        return view('electronic-forms.fields.create', compact('form'));
    }

    /**
     * عرض نموذج تعديل حقل
     */
    public function edit(ElectronicForms $form, FormFields $field)
    {
        // التحقق من أن الحقل ينتمي للاستمارة
        if ($field->form_id != $form->id) {
            abort(404, 'الحقل غير موجود في هذه الاستمارة');
        }

        return view('electronic-forms.fields.edit', compact('form', 'field'));
    }

    /**
     * تخزين حقل جديد
     */
    public function store(Request $request, ElectronicForms $form)
    {
        // التحقق من الصلاحيات
        if (!auth()->user()->can('form-field-create')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لإضافة حقول'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,email,number,tel,date,textarea,select,radio,checkbox,file,url,password',
            'required' => 'boolean',
            'placeholder' => 'nullable|string|max:500',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'validation_rules' => 'nullable|array',
            'sort_order' => 'nullable|integer|min:0',
            'settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // إنشاء اسم فني للحقل
            $name = $this->generateFieldName($request->label, $form->id);

            // الحصول على آخر ترتيب
            $lastOrder = $form->fields()->max('sort_order') ?? 0;

            $field = $form->fields()->create([
                'label' => $request->label,
                'name' => $name,
                'type' => $request->type,
                'required' => $request->boolean('required', false),
                'placeholder' => $request->placeholder,
                'options' => $request->options,
                'validation_rules' => $request->validation_rules,
                'sort_order' => $request->sort_order ?? ($lastOrder + 1),
                'settings' => $request->settings ?? [],
            ]);

            // تحديث حقول الاستمارة في الجدول الرئيسي
            $this->updateFormFieldsJson($form);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الحقل بنجاح',
                'field' => $field,
                'redirect' => route('forms.fields.show', $form->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating form field: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الحقل: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث حقل موجود
     */
    public function update(Request $request, ElectronicForms $form, FormFields $field)
    {
        // التحقق من أن الحقل ينتمي للاستمارة
        if ($field->form_id != $form->id) {
            return response()->json([
                'success' => false,
                'message' => 'الحقل غير موجود في هذه الاستمارة'
            ], 404);
        }

        // التحقق من الصلاحيات
        if (!auth()->user()->can('form-field-edit')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لتعديل الحقول'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'required' => 'boolean',
            'placeholder' => 'nullable|string|max:500',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'validation_rules' => 'nullable|array',
            'sort_order' => 'nullable|integer|min:0',
            'settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // إذا تغير الاسم، نحتاج لإنشاء اسم جديد
            if ($field->label != $request->label) {
                $name = $this->generateFieldName($request->label, $form->id, $field->id);
            } else {
                $name = $field->name;
            }

            $field->update([
                'label' => $request->label,
                'name' => $name,
                'required' => $request->boolean('required', false),
                'placeholder' => $request->placeholder,
                'options' => $request->options,
                'validation_rules' => $request->validation_rules,
                'sort_order' => $request->sort_order ?? $field->sort_order,
                'settings' => $request->settings ?? $field->settings,
            ]);

            // تحديث حقول الاستمارة في الجدول الرئيسي
            $this->updateFormFieldsJson($form);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحقل بنجاح',
                'field' => $field
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating form field: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الحقل'
            ], 500);
        }
    }

    /**
     * حذف حقل
     */
    public function destroy(ElectronicForms $form, FormFields $field)
    {
        // التحقق من أن الحقل ينتمي للاستمارة
        if ($field->form_id != $form->id) {
            return response()->json([
                'success' => false,
                'message' => 'الحقل غير موجود في هذه الاستمارة'
            ], 404);
        }

        // التحقق من الصلاحيات
        if (!auth()->user()->can('form-field-delete')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لحذف الحقول'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $field->delete();

            // إعادة ترتيب الحقول المتبقية
            $this->reorderFields($form);

            // تحديث حقول الاستمارة في الجدول الرئيسي
            $this->updateFormFieldsJson($form);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الحقل بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting form field: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الحقل'
            ], 500);
        }
    }

    /**
     * إعادة ترتيب الحقول
     */
    public function reorder(Request $request, ElectronicForms $form)
    {
        // التحقق من الصلاحيات
        if (!auth()->user()->can('form-field-manage')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لإدارة الحقول'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'fields' => 'required|array',
            'fields.*.id' => 'required|exists:form_fields,id',
            'fields.*.sort_order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->fields as $item) {
                // التأكد من أن الحقل ينتمي للاستمارة
                $field = FormFields::find($item['id']);
                if ($field && $field->form_id == $form->id) {
                    $field->update(['sort_order' => $item['sort_order']]);
                }
            }

            // تحديث حقول الاستمارة في الجدول الرئيسي
            $this->updateFormFieldsJson($form);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إعادة ترتيب الحقول بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reordering form fields: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعادة الترتيب'
            ], 500);
        }
    }

    /**
     * استعادة الحقول الافتراضية لنوع معين من الاستمارات
     */
    public function loadTemplate(Request $request, ElectronicForms $form)
    {
        $validator = Validator::make($request->all(), [
            'template' => 'required|string|in:job_application,contact_form,registration,feedback,survey'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'نموذج غير صالح'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // حذف الحقول الحالية
            $form->fields()->delete();

            // تحميل الحقول الافتراضية حسب النموذج
            $fields = $this->getTemplateFields($request->template);

            foreach ($fields as $index => $fieldData) {
                $form->fields()->create(array_merge($fieldData, [
                    'sort_order' => $index
                ]));
            }

            // تحديث حقول الاستمارة في الجدول الرئيسي
            $this->updateFormFieldsJson($form);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحميل النموذج بنجاح',
                'fields' => $form->fields()->orderBy('sort_order')->get()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error loading template: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل النموذج'
            ], 500);
        }
    }

    /**
     * دوال مساعدة
     */

    private function generateFieldName($label, $formId, $excludeId = null)
    {
        $baseName = Str::slug($label, '_');
        $name = $baseName;
        $counter = 1;

        $query = FormFields::where('form_id', $formId)
            ->where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $name = $baseName . '_' . $counter;
            $query = FormFields::where('form_id', $formId)
                ->where('name', $name);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            $counter++;
        }

        return $name;
    }

    private function updateFormFieldsJson(ElectronicForms $form)
    {
        $fields = $form->fields()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'label' => $field->label,
                    'type' => $field->type,
                    'required' => $field->required,
                    'placeholder' => $field->placeholder,
                    'options' => $field->options,
                    'validation_rules' => $field->validation_rules,
                    'settings' => $field->settings,
                ];
            })
            ->toArray();

        $form->update(['form_fields' => $fields]);
    }

    private function reorderFields(ElectronicForms $form)
    {
        $fields = $form->fields()
            ->orderBy('sort_order')
            ->get();

        foreach ($fields as $index => $field) {
            $field->update(['sort_order' => $index]);
        }
    }

    private function getTemplateFields($template)
    {
        $templates = [
            'job_application' => [
                [
                    'label' => 'الاسم الكامل',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'أدخل الاسم الكامل'
                ],
                [
                    'label' => 'البريد الإلكتروني',
                    'type' => 'email',
                    'required' => true,
                    'placeholder' => 'example@email.com'
                ],
                [
                    'label' => 'رقم الهاتف',
                    'type' => 'tel',
                    'required' => true,
                    'placeholder' => '05XXXXXXXX'
                ],
                [
                    'label' => 'المؤهل العلمي',
                    'type' => 'select',
                    'required' => true,
                    'options' => ['ثانوية عامة', 'دبلوم', 'بكالوريوس', 'ماجستير', 'دكتوراه'],
                    'placeholder' => 'اختر المؤهل العلمي'
                ],
                [
                    'label' => 'الخبرات السابقة',
                    'type' => 'textarea',
                    'required' => false,
                    'placeholder' => 'اذكر خبراتك السابقة'
                ],
                [
                    'label' => 'السيرة الذاتية',
                    'type' => 'file',
                    'required' => true,
                    'placeholder' => 'ارفع ملف السيرة الذاتية'
                ]
            ],

            'contact_form' => [
                [
                    'label' => 'الاسم',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'أدخل اسمك'
                ],
                [
                    'label' => 'البريد الإلكتروني',
                    'type' => 'email',
                    'required' => true,
                    'placeholder' => 'example@email.com'
                ],
                [
                    'label' => 'الموضوع',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'موضوع الرسالة'
                ],
                [
                    'label' => 'الرسالة',
                    'type' => 'textarea',
                    'required' => true,
                    'placeholder' => 'اكتب رسالتك هنا...'
                ]
            ],

            'registration' => [
                [
                    'label' => 'اسم المستخدم',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'اختر اسم مستخدم'
                ],
                [
                    'label' => 'البريد الإلكتروني',
                    'type' => 'email',
                    'required' => true,
                    'placeholder' => 'example@email.com'
                ],
                [
                    'label' => 'كلمة المرور',
                    'type' => 'password',
                    'required' => true,
                    'placeholder' => 'أدخل كلمة المرور'
                ],
                [
                    'label' => 'تأكيد كلمة المرور',
                    'type' => 'password',
                    'required' => true,
                    'placeholder' => 'أعد إدخال كلمة المرور'
                ]
            ]
        ];

        return $templates[$template] ?? [];
    }
}
