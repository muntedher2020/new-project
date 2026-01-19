@section('title', 'عرض الإجابة - ' . $form->title)

<div class="mt-n4">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
              <li class="breadcrumb-item fs-4">
                <a href="{{ route('dashboard') }}">لوحة التحكم</a>
              </li>
              <li class="breadcrumb-item fs-4">
                <a href="{{ route('ElectronicForms') }}">الاستمارات</a>
              </li>
              <li class="breadcrumb-item fs-4">
                <a href="{{ route('form-responses.index', $form->id) }}">النتائج</a>
              </li>
              <li class="breadcrumb-item active fs-4">
                <span class="fw-bold text-primary">الإجابة #{{ $response->id }}</span>
              </li>
            </ol>
          </nav>
        </div>
        <div>
          <a href="{{ route('form-responses.index', $form->id) }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i>
            رجوع
          </a>
        </div>
      </div>
    </div>

    <!-- معلومات الإجابة -->
    <div class="card-body">
      <div class="row mb-4">
        <div class="col-md-6">
          <h5 class="card-title mb-3">معلومات الإجابة</h5>
          <table class="table table-sm">
            <tr>
              <td><strong>رقم الإجابة:</strong></td>
              <td>#{{ $response->id }}</td>
            </tr>
            <tr>
              <td><strong>الاستمارة:</strong></td>
              <td>{{ $form->title }}</td>
            </tr>
            <tr>
              <td><strong>المستخدم:</strong></td>
              <td>
                @if($response->user)
                <span class="badge bg-info">{{ $response->user->name }}</span>
                @else
                <span class="text-muted">غير مسجل</span>
                @endif
              </td>
            </tr>
            <tr>
              <td><strong>تاريخ التقديم:</strong></td>
              <td>{{ $response->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
              <td><strong>آخر تحديث:</strong></td>
              <td>{{ $response->updated_at->format('Y-m-d H:i:s') }}</td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <h5 class="card-title mb-3">معلومات تقنية</h5>
          <table class="table table-sm">
            <tr>
              <td><strong>عنوان IP:</strong></td>
              <td><small class="text-monospace">{{ $response->ip_address }}</small></td>
            </tr>
            <tr>
              <td><strong>بصمة المتصفح:</strong></td>
              <td><small class="text-monospace">{{ $response->browser_fingerprint ?? '-' }}</small></td>
            </tr>
            <tr>
              <td><strong>بصمة التقديم:</strong></td>
              <td><small class="text-monospace">{{ $response->submission_hash ?? '-' }}</small></td>
            </tr>
            <tr>
              <td><strong>نظام التشغيل/المتصفح:</strong></td>
              <td>
                <small class="text-truncate d-block">
                  {{ substr($response->user_agent, 0, 50) ?? '-' }}
                </small>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <!-- الحالة والملاحظات -->
      <div class="row mb-4">
        <div class="col-md-6">
          <h5 class="card-title mb-3">الحالة</h5>
          <form action="{{ route('form-responses.update', [$form->id, $response->id]) }}" method="POST"
            class="form-inline gap-2">
            @csrf
            @method('PUT')
            <select name="status" class="form-select w-auto">
              @foreach(['pending' => 'قيد الانتظار', 'approved' => 'موافق عليه', 'rejected' => 'مرفوض', 'under_review'
              => 'تحت المراجعة'] as $key => $label)
              <option value="{{ $key }}" @selected($response->status === $key)>{{ $label }}</option>
              @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm">تحديث</button>
          </form>
        </div>
      </div>

      <!-- ملاحظات إدارية -->
      <div class="row mb-4">
        <div class="col-12">
          <h5 class="card-title mb-3">الملاحظات الإدارية</h5>
          <form action="{{ route('form-responses.update', [$form->id, $response->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <textarea name="notes" class="form-control" rows="4">{{ $response->notes }}</textarea>
            <button type="submit" class="btn btn-primary btn-sm mt-2">حفظ الملاحظات</button>
          </form>
        </div>
      </div>

      <!-- بيانات الإجابة -->
      <div class="row">
        <div class="col-12">
          <h5 class="card-title mb-3">بيانات الإجابة</h5>
          @if($response->response_data && count($response->response_data) > 0)
          <div class="table-responsive">
            <table class="table table-striped">
              <thead class="table-light">
                <tr>
                  <th style="width: 30%;">اسم الحقل</th>
                  <th>القيمة</th>
                </tr>
              </thead>
              <tbody>
                @foreach($response->response_data as $fieldName => $fieldValue)
                <tr>
                  <td class="fw-bold">{{ $fieldName }}</td>
                  <td>
                    @if(is_array($fieldValue))
                    <ul class="mb-0">
                      @foreach($fieldValue as $item)
                      <li>{{ $item }}</li>
                      @endforeach
                    </ul>
                    @else
                    {{ $fieldValue }}
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <div class="alert alert-info mb-0">
            لا توجد بيانات متاحة لهذه الإجابة
          </div>
          @endif
        </div>
      </div>

      <!-- أزرار الإجراءات -->
      <div class="mt-4 pt-3 border-top">
        <div class="d-flex gap-2">
          <form action="{{ route('form-responses.destroy', [$form->id, $response->id]) }}" method="POST"
            style="display:inline;" onsubmit="return confirm('هل تريد حذف هذه الإجابة؟');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
              <i class="mdi mdi-delete me-1"></i>
              حذف الإجابة
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>