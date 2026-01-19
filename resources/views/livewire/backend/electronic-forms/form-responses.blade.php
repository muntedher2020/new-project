<div>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-custom-icon">
        <li class="breadcrumb-item">
          <a href="{{ route('dashboard') }}">
            <span class="text-muted fw-light fs-5">لوحة التحكم</span>
          </a>
          <i class="breadcrumb-icon icon-base ri ri-arrow-right-s-line icon-22px align-middle"></i>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('ElectronicForms') }}">
            <span class="text-muted fw-light fs-5">الاستمارات الإلكترونية</span>
          </a>
          <i class="breadcrumb-icon icon-base ri ri-arrow-right-s-line icon-22px align-middle"></i>
        </li>
        <li class="breadcrumb-item">
          <a href="javascript:void(0);">نتائج الاستمارة</a>
        </li>
      </ol>
    </nav>
    <div class="d-flex gap-2">
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="ri ri-download-cloud-line me-1"></i>
          تصدير / طباعة
        </button>
        <ul class="dropdown-menu">
          <li>
            <a class="dropdown-item"
              href="{{ route('form-responses.export.pdf.tcpdf', ['formId' => $form->id, 'status' => $status, 'search' => $search]) }}">
              <i class="ri ri-file-pdf-line me-2 text-danger"></i>
              تصدير PDF (النتائج الحالية)
            </a>
          </li>
          <li>
            <a class="dropdown-item"
              href="{{ route('form-responses.export.excel', ['formId' => $form->id, 'status' => $status, 'search' => $search]) }}">
              <i class="ri ri-file-excel-line me-2 text-success"></i>
              تصدير Excel
            </a>
          </li>
        </ul>
      </div>

      <a href="{{ route('ElectronicForms') }}" class="btn btn-label-secondary waves-effect">
        <i class="ri ri-arrow-left-line me-1"></i>
        رجوع
      </a>
    </div>
  </div>

  <div class="container-fluid">
    <!-- الإحصائيات -->
    <div class="row mb-4" wire:key="statistics-{{ time() }}">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-start border-primary border-3 h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted fw-medium mb-1">إجمالي الإجابات</p>
                <h3 class="text-primary mb-0" wire:poll.2000ms="$refresh">
                  {{ $totalResponses ?? 0 }}
                </h3>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="ri ri-file-list-2-line icon-24px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-start border-warning border-3 h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted fw-medium mb-1">قيد المراجعة</p>
                <h3 class="text-warning mb-0" wire:poll.2000ms="$refresh">
                  {{ $totalPending ?? 0 }}
                </h3>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="ri ri-time-line icon-24px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-start border-success border-3 h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted fw-medium mb-1">موافق عليها</p>
                <h3 class="text-success mb-0" wire:poll.2000ms="$refresh">
                  {{ $totalApproved ?? 0 }}
                </h3>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-success">
                  <i class="ri ri-check-line icon-24px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-start border-danger border-3 h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted fw-medium mb-1">مرفوضة</p>
                <h3 class="text-danger mb-0" wire:poll.2000ms="$refresh">
                  {{ $totalRejected ?? 0 }}
                </h3>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-danger">
                  <i class="ri ri-close-line icon-24px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- البحث والفلتر -->
    <div class="row g-3 mb-4">
      <div class="col-md-5">
        <div class="input-group">
          <span class="input-group-text"><i class="ri ri-search-line"></i></span>
          <input type="text" wire:model.live.debounce.500ms="search" class="form-control"
            placeholder="ابحث عن أي بيانات في الإجابات...">
          <span class="input-group-text" wire:loading wire:target="search">
            <i class="ri ri-loader-4-line ri-spin"></i>
          </span>
        </div>
      </div>
      <div class="col-md-3">
        <select wire:model.live="status" class="form-select">
          <option value="">جميع الحالات</option>
          @foreach($statuses as $key => $label)
          <option value="{{ $key }}">{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select wire:model.live="perPage" class="form-select">
          <option value="10">10 لكل صفحة</option>
          <option value="20">20 لكل صفحة</option>
          <option value="50">50 لكل صفحة</option>
          <option value="100">100 لكل صفحة</option>
        </select>
      </div>
      <div class="col-md-2">
        <button wire:click="$set('search', ''); $set('status', '')" wire:loading.attr="disabled"
          class="btn btn-outline-secondary w-100">
          <i class="ri ri-refresh-line"></i> إعادة تعيين
        </button>
      </div>
    </div>

    <!-- جدول النتائج -->
    <div class="table-responsive">
      <table class="table table-hover table-borderless table-sm">
        <thead class="border-top">
          <tr>
            <th width="50">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model.live="selectAll">
              </div>
            </th>
            <th wire:click="sortBy('id')" style="cursor: pointer;" class="text-nowrap">
              رقم التقديم
              @if($sortField === 'id')
              <i class="ri ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill ms-1"></i>
              @endif
            </th>
            @php
            // الحصول على أسماء الأسئلة الفريدة من جميع الإجابات
            $allQuestions = [];
            foreach($responses as $resp) {
            if(is_array($resp->response_data)) {
            foreach($resp->response_data as $question => $answer) {
            if(!in_array($question, $allQuestions)) {
            $allQuestions[] = $question;
            }
            }
            }
            }
            @endphp
            @foreach($allQuestions as $question)
            <th class="text-nowrap" style="max-width: 150px;">
              <small>{{ \Illuminate\Support\Str::limit($question, 30) }}</small>
            </th>
            @endforeach
            <th wire:click="sortBy('status')" style="cursor: pointer;" class="text-nowrap">
              الحالة
              @if($sortField === 'status')
              <i class="ri ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill ms-1"></i>
              @endif
            </th>
            <th wire:click="sortBy('created_at')" style="cursor: pointer;" class="text-nowrap">
              التاريخ
              @if($sortField === 'created_at')
              <i class="ri ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill ms-1"></i>
              @endif
            </th>
            <th width="100">الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($responses as $response)
          <tr>
            <td>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" wire:model="selectedRows" value="{{ $response->id }}">
              </div>
            </td>
            <td class="fw-bold text-primary">
              #{{ $response->id }}
            </td>
            @foreach($allQuestions as $question)
            <td style="max-width: 150px; font-size: 0.875rem;">
              @php
              $responseData = $response->response_data;
              $answer = $responseData[$question] ?? null;
              @endphp
              @if($answer)
              @if(is_array($answer))
              <span class="badge bg-light text-dark">{{ implode(', ', $answer) }}</span>
              @else
              <span class="text-dark">{{ \Illuminate\Support\Str::limit($answer, 40) }}</span>
              @endif
              @else
              <span class="badge bg-light text-muted">-</span>
              @endif
            </td>
            @endforeach
            <td>
              <select wire:change="toggleStatus({{ $response->id }})" class="form-select form-select-sm"
                style="width: auto; display: inline-block; padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected($response->status === $key)>
                  {{ $label }}
                </option>
                @endforeach
              </select>
            </td>
            <td>
              <small class="text-muted text-nowrap">
                {{ $response->created_at->format('Y-m-d H:i') }}
              </small>
            </td>
            <td>
              <button wire:click="$dispatch('confirmDelete', { id: {{ $response->id }}, type: 'response' })"
                class="btn btn-sm btn-outline-danger" title="حذف">
                <i class="ri ri-delete-bin-6-line me-1"></i>
                حذف
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="100%" class="text-center py-5">
              <div class="text-muted">
                <i class="ri ri-inbox-fill ri-3x mb-3 d-block"></i>
                <p class="mb-0">لا توجد نتائج لعرضها</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- أزرار الإجراءات الجماعية -->
    @if(count($selectedRows) > 0)
    <div class="py-3 px-3 bg-light border-top">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <span class="text-muted">تم تحديد <strong class="text-primary">{{ count($selectedRows) }}</strong> عنصر</span>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('form-responses.export.excel', ['formId' => $formId, 'selected' => implode(',', $selectedRows)]) }}"
            class="btn btn-sm btn-outline-success">
            <i class="ri ri-file-excel-line me-1"></i> إكسل للمحدد
          </a>

          <div class="btn-group" role="group">
          </div>

          <button wire:click="deleteSelected" wire:confirm="هل أنت متأكد؟" class="btn btn-sm btn-danger">
            <i class="ri ri-delete-bin-line me-1"></i> حذف المحدد
          </button>
        </div>
      </div>
    </div>
    @endif

    <!-- التقسيم -->
    @if($responses->hasPages())
    <div class="px-3 py-3 border-top">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted">
          عرض <strong>{{ $responses->firstItem() }}</strong> إلى <strong>{{ $responses->lastItem() }}</strong> من إجمالي
          <strong>{{ $responses->total() }}</strong>
        </div>
        <div>
          {{ $responses->links('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>
    @endif
  </div>
