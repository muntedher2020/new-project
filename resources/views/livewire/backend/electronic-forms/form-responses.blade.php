<div>
  <!-- البحث والفلتر -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="input-group">
        <span class="input-group-text"><i class="ri ri-search-line"></i></span>
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
          placeholder="ابحث بـ IP أو بصمة المتصفح...">
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
    <div class="col-md-3">
      <select wire:model.live="perPage" class="form-select">
        <option value="10">10 لكل صفحة</option>
        <option value="20">20 لكل صفحة</option>
        <option value="50">50 لكل صفحة</option>
        <option value="100">100 لكل صفحة</option>
      </select>
    </div>
    <div class="col-md-2">
      <button wire:click="$set('search', '')" wire:loading.attr="disabled" class="btn btn-outline-secondary w-100">
        <i class="ri ri-refresh-line"></i> إعادة تعيين
      </button>
    </div>
  </div>

  <!-- جدول النتائج -->
  <div class="table-responsive">
    <table class="table table-hover table-borderless">
      <thead class="border-top">
        <tr>
          <th width="50">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" wire:model="selectAll">
            </div>
          </th>
          <th wire:click="sortBy('id')" style="cursor: pointer;" class="text-nowrap">
            رقم التقديم
            @if($sortField === 'id')
            <i class="ri ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill ms-1"></i>
            @endif
          </th>
          <th>المستخدم</th>
          <th>الإجابات</th>
          <th wire:click="sortBy('status')" style="cursor: pointer;" class="text-nowrap">
            الحالة
            @if($sortField === 'status')
            <i class="ri ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill ms-1"></i>
            @endif
          </th>
          <th wire:click="sortBy('created_at')" style="cursor: pointer;" class="text-nowrap">
            تاريخ التقديم
            @if($sortField === 'created_at')
            <i class="ri ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill ms-1"></i>
            @endif
          </th>
          <th>الإجراءات</th>
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
          <td>
            <strong class="text-primary">#{{ $response->id }}</strong>
          </td>
          <td>
            @if($response->user)
            <div class="d-flex align-items-center">
              <div class="avatar avatar-sm me-2">
                <span class="avatar-initial rounded-circle bg-label-primary">
                  <i class="ri ri-user-line icon-16px"></i>
                </span>
              </div>
              <span class="fw-medium">{{ $response->user->name }}</span>
            </div>
            @else
            <span class="badge bg-light text-muted">غير مسجل</span>
            @endif
          </td>
          <td>
            <div class="response-data-preview">
              @php
              $responseData = $response->response_data;
              if(is_array($responseData)) {
              $items = array_slice($responseData, 0, 2);
              $count = count($responseData);
              @endphp
              <div class="list-group list-group-sm">
                @foreach($items as $question => $answer)
                <div class="list-group-item px-2 py-1 border-0">
                  <small class="text-muted d-block">{{ \Illuminate\Support\Str::limit($question, 40) }}</small>
                  <small class="fw-medium text-dark d-block">{{ \Illuminate\Support\Str::limit(is_array($answer) ? implode(', ', $answer) :
                    $answer, 50) }}</small>
                </div>
                @endforeach
              </div>
              @if($count > 2)
              <small class="badge bg-light text-primary mt-2">+{{ $count - 2 }} إجابة أخرى</small>
              @endif
              @php
              }
              @endphp
            </div>
          </td>
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
            <small class="text-muted">
              <i class="ri ri-calendar-line me-1"></i>
              {{ $response->created_at->format('Y-m-d H:i') }}
            </small>
          </td>
          <td>
            <div class="btn-group btn-group-sm" role="group">
              <a href="{{ route('form-responses.show', [$form->id, $response->id]) }}"
                class="btn btn-icon btn-text-primary" title="عرض التفاصيل">
                <i class="ri ri-eye-line icon-20px"></i>
              </a>
              <button wire:click="$dispatch('confirmDelete', { id: {{ $response->id }}, type: 'response' })"
                class="btn btn-icon btn-text-danger" title="حذف">
                <i class="ri ri-delete-bin-6-line icon-20px"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-5">
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
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="ri ri-pencil-line me-1"></i>
            تغيير الحالة
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            @foreach($statuses as $key => $label)
            <li>
              <button wire:click="changeSelectedStatus('{{ $key }}')" class="dropdown-item">
                {{ $label }}
              </button>
            </li>
            @endforeach
          </ul>
        </div>
        <button wire:click="deleteSelected" wire:confirm="هل أنت متأكد من حذف العناصر المحددة؟"
          class="btn btn-sm btn-danger">
          <i class="ri ri-delete-bin-line me-1"></i>
          حذف المحدد
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