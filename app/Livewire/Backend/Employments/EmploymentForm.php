<?php

namespace App\Livewire\Backend\Employments;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Backend\ElectronicForms\FormResponses;
use Illuminate\Support\Str;

class EmploymentForm extends Component
{
  use WithFileUploads;

  public $form;
  public $fields = [];
  public $responses = [];
  public $files = [];
  public $success = false;
  public $responseId = null;
  public $errorMessages = [];
  public $submissionToken;

  /*  public function mount($form)
    {
        $this->form = $form;
        $this->fields = $form->fields()->orderBy('sort_order')->get();

        // التحقق من التقديم السابق
        $this->checkPreviousResponse();
    } */

  public function mount($form)
  {
    $this->form = $form;
    $this->fields = $form->fields()->orderBy('sort_order')->get();

    foreach ($this->fields as $field) {
      $this->responses[$field['name']] = '';
      // تهيئة مصفوفة الملفات إذا كان الحقل من نوع ملف
      if ($field['type'] === 'file') {
        $this->files[$field['name']] = null;
      }
    }

    $this->submissionToken = Str::random(40);
    session(['form_submission_token_' . $this->form->id => $this->submissionToken]);

    $this->checkPreviousResponse();
  }

  public function render()
  {
    return view('livewire.backend.employments.employment-form');
  }

  protected function checkPreviousResponse()
  {
    // التحقق بواسطة متعدد الطرق
    $previousResponse = $this->findPreviousResponse();

    if ($previousResponse) {
      $this->responseId = $previousResponse->id;
      $this->loadPreviousData($previousResponse);
    }
  }

  protected function findPreviousResponse()
  {
    // 1. التحقق للمستخدمين المسجلين
    if (Auth::check()) {
      return FormResponses::where('electronic_forms_id', $this->form->id)
        ->where('user_id', Auth::id())
        ->first();
    }

    // 2. التحقق بواسطة الـ IP و User Agent معاً
    $response = FormResponses::where('electronic_forms_id', $this->form->id)
      ->whereNull('user_id')
      ->where('ip_address', request()->ip())
      ->where('user_agent', request()->userAgent())
      ->orderBy('created_at', 'desc')
      ->first();

    if ($response) {
      // التحقق من فترة التبريد
      $cooldown = $this->getCooldownPeriod();
      if ($response->created_at->addDays($cooldown)->gt(now())) {
        return $response;
      }
    }

    // 3. التحقق بواسطة Browser Fingerprint
    $fingerprint = $this->generateFingerprint();
    return FormResponses::where('electronic_forms_id', $this->form->id)
      ->where('browser_fingerprint', $fingerprint)
      ->first();
  }

  protected function generateFingerprint(): string
  {
    $data = [
      'ip' => request()->ip(),
      'user_agent' => request()->userAgent(),
      'accept_language' => request()->header('Accept-Language'),
      'accept_encoding' => request()->header('Accept-Encoding'),
      'screen_resolution' => request()->header('Sec-CH-UA-Platform') ?? 'unknown',
    ];

    return md5(serialize($data));
  }

  protected function getCooldownPeriod(): int
  {
    return $this->form->settings['cooldown_days'] ?? 30; // 30 يوم افتراضياً
  }

  protected function loadPreviousData($response)
  {
    if ($response->response_data) {
      foreach ($response->response_data as $key => $value) {
        if (isset($this->responses[$key])) {
          $this->responses[$key] = $value;
        }
      }
    }
  }

  public function submitResponse()
  {
    // التحقق من التوكن لمنع التقديم المتكرر
    if (!$this->validateSubmissionToken()) {
      $this->addError('form', 'تم اكتشاف محاولة تقديم متكررة');
      return;
    }

    // التحقق من rate limiting
    if ($this->isRateLimited()) {
      return;
    }

    // التحقق الأساسي
    if (!$this->validateFormAccess()) {
      return;
    }

    // التحقق من الصحة
    if (!$this->validateFormData()) {
      return;
    }

    // معالجة الملفات
    $this->processFiles();

    // حفظ الإجابة
    try {
      DB::beginTransaction();

      $responseData = $this->prepareResponseData();

      $formResponse = FormResponses::updateOrCreate(
        [
          'id' => $this->responseId,
          'electronic_forms_id' => $this->form->id,
        ],
        [
          'user_id' => Auth::id(),
          'response_data' => $responseData,
          'status' => 'pending',
          'ip_address' => request()->ip(),
          'user_agent' => request()->userAgent(),
          'browser_fingerprint' => $this->generateFingerprint(),
          'submission_hash' => $this->generateSubmissionHash($responseData),
        ]
      );

      DB::commit();

      // إزالة التوكن لمنع إعادة الاستخدام
      session()->forget('form_submission_token_' . $this->form->id);

      $this->success = true;
      $this->responseId = $formResponse->id;

      // زيادة عداد rate limiting
      RateLimiter::hit($this->getRateLimitKey(), 3600);
    } catch (\Exception $e) {
      DB::rollBack();
      $this->addError('form', 'حدث خطأ أثناء حفظ البيانات');
    }
  }

  protected function validateSubmissionToken(): bool
  {
    $storedToken = session('form_submission_token_' . $this->form->id);
    return $storedToken === $this->submissionToken;
  }

  protected function generateSubmissionHash(array $data): string
  {
    // إنشاء hash فريد للتقديم لمنع التكرار
    $uniqueData = [
      'electronic_forms_id' => $this->form->id,
      'user_id' => Auth::id(),
      'ip' => request()->ip(),
      'data' => $data,
      'timestamp' => now()->timestamp,
    ];

    return md5(serialize($uniqueData));
  }

  protected function isRateLimited(): bool
  {
    $key = $this->getRateLimitKey();

    if (RateLimiter::tooManyAttempts($key, 3)) { // 3 محاولات كحد أقصى
      $seconds = RateLimiter::availableIn($key);
      $minutes = ceil($seconds / 60);
      $this->addError('form', "لقد تجاوزت الحد المسموح من المحاولات. حاول مرة أخرى بعد {$minutes} دقيقة");
      return true;
    }

    return false;
  }

  protected function getRateLimitKey(): string
  {
    if (Auth::check()) {
      return 'form_submission_user_' . Auth::id();
    }

    return 'form_submission_ip_' . request()->ip();
  }

  /* protected function checkPreviousResponse()
    {
        if (!$this->form->allow_multiple && Auth::check()) {
            $previousResponse = FormResponses::where('electronic_forms_id', $this->form->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($previousResponse) {
                $this->responseId = $previousResponse->id;

                // تحميل البيانات السابقة
                if ($previousResponse->response_data) {
                    foreach ($previousResponse->response_data as $key => $value) {
                        if (isset($this->responses[$key])) {
                            $this->responses[$key] = $value;
                        }
                    }
                }
            }
        }
    } */

  /* public function submitResponse()
    {
        // التحقق الأساسي
        if (!$this->validateFormAccess()) {
            return;
        }

        // التحقق من الصحة
        if (!$this->validateFormData()) {
            return;
        }

        // معالجة الملفات
        $this->processFiles();

        if (!$this->form->allow_multiple && $this->responseId) {
            $this->addError('form', 'لقد قمت بتقديم هذه الاستمارة من قبل');
            return;
        }

        // أو التحقق بالـ IP إذا لم يكن مسجلاً
        if (!$this->form->allow_multiple && !Auth::check()) {
            $recentResponse = FormResponses::where('electronic_forms_id', $this->form->id)
                ->where('ip_address', request()->ip())
                ->where('created_at', '>', now()->subDay())
                ->exists();

            if ($recentResponse) {
                $this->addError('form', 'يمكنك تقديم هذه الاستمارة مرة واحدة فقط يومياً');
                return;
            }
        }

        // حفظ الإجابة
        try {
            DB::beginTransaction();

            $responseData = $this->prepareResponseData();

            $formResponse = FormResponses::updateOrCreate(
                [
                    'id' => $this->responseId,
                    'electronic_forms_id' => $this->form->id,
                    'user_id' => Auth::id()
                ],
                [
                    'response_data' => $responseData,
                    'status' => 'pending',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]
            );

            // حفظ cookie لمدة 30 يوم
            $cookie = Cookie::make(
                'form_submitted_' . $this->form->id,
                $response->id,
                60 * 24 * 30 // 30 يوم
            );

            Cookie::queue($cookie);

            DB::commit();

            $this->success = true;
            $this->responseId = $formResponse->id;

            // إرسال إشعارات إذا لزم
            $this->sendNotifications($formResponse);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('form', 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage());
        }
    } */

  protected function validateFormAccess(): bool
  {
    // التحقق من أن الاستمارة مفتوحة
    if (!$this->form->isOpen()) {
      $this->addError('form', 'هذه الاستمارة مغلقة حالياً');
      return false;
    }

    // التحقق من تسجيل الدخول إذا مطلوب
    if ($this->form->require_login && !Auth::check()) {
      $this->addError('form', 'يجب تسجيل الدخول لتقديم هذه الاستمارة');
      return false;
    }

    // التحقق من الحد الأقصى للإجابات
    if ($this->form->max_responses) {
      $currentResponses = FormResponses::where('electronic_forms_id', $this->form->id)->count();
      if ($currentResponses >= $this->form->max_responses) {
        $this->addError('form', 'تم الوصول إلى الحد الأقصى لعدد الإجابات');
        return false;
      }
    }

    return true;
  }

  protected function validateFormData(): bool
{
    $rules = [];
    $messages = [];
    $customAttributes = [];

    // نجهز مصفوفة بيانات موحدة تحتوي على الإجابات النصية والملفات معاً
    $dataToValidate = $this->responses;

    foreach ($this->fields as $field) {
        $fieldName = $field['name'];
        $fieldRules = [];

        // إذا كان الحقل من نوع ملف، نسحب الملف من مصفوفة $files للتحقق منه
        if ($field['type'] === 'file') {
            $dataToValidate[$fieldName] = $this->files[$fieldName] ?? null;
        }

        // إضافة قاعدة المطلوب
        if ($field['required']) {
            $fieldRules[] = 'required';
            $messages["{$fieldName}.required"] = "حقل {$field['label']} مطلوب";
        } else {
            $fieldRules[] = 'nullable';
        }

        // إضافة قواعد حسب النوع
        switch ($field['type']) {
            case 'email':
                $fieldRules[] = 'email';
                break;
            case 'number':
                $fieldRules[] = 'numeric';
                break;
            case 'file':
                // التحقق من أنه ملف فعلاً
                $fieldRules[] = 'file';
                // إضافة قواعد حجم الملف إذا كانت موجودة (بالكيلوبايت)
                if (isset($field['settings']['max_size'])) {
                    $fieldRules[] = 'max:' . $field['settings']['max_size'];
                }
                // إضافة أنواع الملفات المسموحة
                if (isset($field['settings']['allowed_types'])) {
                    $fieldRules[] = 'mimes:' . implode(',', $field['settings']['allowed_types']);
                }
                break;
            case 'date':
                $fieldRules[] = 'date';
                break;
            case 'url':
                $fieldRules[] = 'url';
                break;
        }

        // إضافة قواعد التحقق المخصصة من قاعدة البيانات إن وجدت
        if (!empty($field['validation_rules'])) {
            $customRules = explode('|', $field['validation_rules']);
            $fieldRules = array_merge($fieldRules, $customRules);
        }

        $rules[$fieldName] = implode('|', array_filter($fieldRules));
        $customAttributes[$fieldName] = $field['label'];
    }

    // نمرر $dataToValidate بدلاً من $this->responses
    $validator = Validator::make($dataToValidate, $rules, $messages, $customAttributes);

    if ($validator->fails()) {
        $this->errorMessages = $validator->errors()->toArray();

        // إضافة الأخطاء إلى حقيبة أخطاء Livewire
        foreach ($validator->errors()->getMessages() as $field => $errors) {
            $this->addError($field, $errors[0]);
        }

        return false;
    }

    return true;
}

  protected function processFiles()
  {
    foreach ($this->fields as $field) {
      if ($field['type'] === 'file' && isset($this->files[$field['name']])) {
        $file = $this->files[$field['name']];

        // إنشاء مسار لحفظ الملف
        $path = $file->store("forms/{$this->form->id}/responses", 'public');

        // حفظ معلومات الملف في الردود
        $this->responses[$field['name']] = [
          'path' => $path,
          'original_name' => $file->getClientOriginalName(),
          'size' => $file->getSize(),
          'mime_type' => $file->getMimeType(),
        ];
      }
    }
  }

  protected function prepareResponseData(): array
  {
    $responseData = [];

    foreach ($this->fields as $field) {
      $value = $this->responses[$field['name']] ?? null;

      // تنظيف البيانات
      if ($value !== null) {
        // تحويل الأرقام
        if ($field['type'] === 'number' && is_numeric($value)) {
          $value = (float) $value;
        }

        // تحويل التواريخ
        if ($field['type'] === 'date' && $value) {
          try {
            $value = \Carbon\Carbon::parse($value)->format('Y-m-d');
          } catch (\Exception $e) {
            // الاحتفاظ بالقيمة كما هي
          }
        }

        // معالجة الخيارات المتعددة
        if (in_array($field['type'], ['checkbox']) && is_array($value)) {
          $value = array_filter($value); // إزالة القيم الفارغة
        }
      }

      $responseData[$field['name']] = $value;
    }

    return $responseData;
  }

  protected function sendNotifications(FormResponses $response)
  {
    // إرسال إشعار للمسؤول
    if (
      isset($this->form->settings['notify_admin']) &&
      $this->form->settings['notify_admin'] === true
    ) {

      $adminEmail = config('mail.admin_email');
      if ($adminEmail) {
        // يمكنك استخدام Mail facade هنا
        // Mail::to($adminEmail)->send(new NewFormResponse($response));
      }
    }

    // إرسال تأكيد للمستخدم إذا كان مسجلاً
    if (
      Auth::check() && isset($this->form->settings['notify_user']) &&
      $this->form->settings['notify_user'] === true
    ) {

      // يمكنك إرسال إشعار للمستخدم
      // Auth::user()->notify(new FormResponseSubmitted($response));
    }
  }

  // دالة لمعاينة الإجابة قبل الإرسال
  public function preview()
  {
    // التحقق من البيانات
    if ($this->validateFormData()) {
      $this->dispatch(
        'show-preview',
        type: 'success',
        message: 'جميع البيانات صالحة',
        data: $this->responses
      );
    } else {
      // جمع الأخطاء
      $errors = [];
      foreach ($this->getErrorBag()->toArray() as $field => $messages) {
        $errors[$field] = $messages;
      }

      $this->dispatch(
        'show-preview',
        type: 'error',
        errors: $errors
      );
    }
  }

  // دالة لحذف الملف المرفوع
  public function removeFile($fieldName)
  {
    if (isset($this->files[$fieldName])) {
      unset($this->files[$fieldName]);
      $this->responses[$fieldName] = '';
    }
  }
}
