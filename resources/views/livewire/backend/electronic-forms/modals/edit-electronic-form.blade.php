<!-- Modal التعديل -->
<div class="modal fade" wire:ignore.self id="editFormModal" tabindex="-1" aria-hidden="true" wire:model="isOpen">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل الاستمارة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    wire:click="close"></button>
            </div>

            @if($formId)
              <form {{-- wire:submit.prevent="update" --}}>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-md-12 mb-3">
                              <label for="edit_title" class="form-label">عنوان الاستمارة *</label>
                              <input type="text" class="form-control @error('title') is-invalid @enderror" id="edit_title"
                                  wire:model="title">
                              @error('title')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>

                          <div class="col-md-12 mb-3">
                              <label for="edit_description" class="form-label">وصف الاستمارة</label>
                              <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="edit_description" wire:model="description" rows="3"></textarea>
                              @error('description')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>

                          <div class="col-md-6 mb-3">
                              <label for="edit_max_responses" class="form-label">الحد الأقصى للإجابات</label>
                              <input type="number" class="form-control @error('max_responses') is-invalid @enderror"
                                  id="edit_max_responses" wire:model="max_responses" min="1">
                              @error('max_responses')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>

                          <div class="col-md-6 mb-3">
                              <div class="row">
                                  <div class="col-6">
                                      <label for="edit_start_date" class="form-label">تاريخ البدء</label>
                                      <input type="datetime-local"
                                          class="form-control @error('start_date') is-invalid @enderror"
                                          id="edit_start_date" wire:model="start_date">
                                  </div>
                                  <div class="col-6">
                                      <label for="edit_end_date" class="form-label">تاريخ الانتهاء</label>
                                      <input type="datetime-local"
                                          class="form-control @error('end_date') is-invalid @enderror" id="edit_end_date"
                                          wire:model="end_date">
                                  </div>
                              </div>
                              @error('start_date')
                              <div class="invalid-feedback d-block">{{ $message }}</div>
                              @enderror
                              @error('end_date')
                              <div class="invalid-feedback d-block">{{ $message }}</div>
                              @enderror
                          </div>

                          <div class="col-md-4 mb-3">
                              <div class="form-check form-switch">
                                  <input class="form-check-input" type="checkbox" id="edit_active" wire:model="active">
                                  <label class="form-check-label" for="edit_active">تفعيل</label>
                              </div>
                          </div>

                          <div class="col-md-4 mb-3">
                              <div class="form-check form-switch">
                                  <input class="form-check-input" type="checkbox" id="edit_require_login"
                                      wire:model="require_login">
                                  <label class="form-check-label" for="edit_require_login">يتطلب تسجيل دخول</label>
                              </div>
                          </div>

                          <div class="col-md-4 mb-3">
                              <div class="form-check form-switch">
                                  <input class="form-check-input" type="checkbox" id="edit_allow_multiple"
                                      wire:model="allow_multiple">
                                  <label class="form-check-label" for="edit_allow_multiple">السماح بتقديمات متعددة</label>
                              </div>
                          </div>

                          <!-- معلومات الاستمارة -->
                          @if($formSlug)
                          <div class="col-md-12 mt-3">
                              <div class="alert alert-info">
                                  <div class="row">
                                      <div class="col-md-6">
                                          <p class="mb-1"><strong>الرابط العام:</strong></p>
                                          <a href="{{ route('forms.public.show', $formSlug) }}" target="_blank"
                                              class="text-decoration-none">
                                              {{ route('forms.public.show', $formSlug) }}
                                          </a>
                                      </div>
                                      <div class="col-md-6">
                                          <p class="mb-1"><strong>عدد الحقول:</strong> {{ $fieldsCount }}</p>
                                          <p class="mb-0"><strong>عدد الإجابات:</strong> {{ $responsesCount }}</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          @endif
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="reset" class="btn btn-secondary"  data-bs-dismiss="modal"
							              aria-label="Close" wire:loading.attr="disabled">
                            إلغاء
                        </button>
                      <button wire:click="update" type="button" class="btn btn-primary" wire:loading.attr="disabled">
                          <span wire:loading.remove>تحديث</span>
                          <span wire:loading wire:target='update'>
                            <div class="d-flex items-center">
                                <i class="ri ri-loader-2-fill icon-20px icon-spin "></i> جاري التحديث... 
                              </div>
                          </span>
                      </button>
                  </div>
              </form>
            @else
              <div class="modal-body text-center py-5">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">جاري التحميل...</span>
                  </div>
                  <p class="mt-3">جاري تحميل بيانات الاستمارة...</p>
              </div>
            @endif
        </div>
    </div>
</div>