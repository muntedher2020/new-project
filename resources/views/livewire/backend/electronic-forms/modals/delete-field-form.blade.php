  <div class="modal fade" wire:ignore.self id="deleteFieldModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-md">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">
                     <span class="text-danger"> حذف حقل :</span> <span>{{ $fieldLabel }} ({{ $fieldName }})</span>
                  </h5>
                  <button type="button" class="btn-close" wire:click="$toggle('showFieldModal')"></button>
              </div>
              <div class="modal-body d-flex justify-content-center align-items-center">
                  <h4>هل تريد حذف هذا الحقل ؟</h4>
              </div>
              <div class="modal-footer d-flex justify-content-center align-items-center">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                  <button type="button" class="btn btn-danger" wire:click="confirmDeleteField({{ $fieldId }})">حذف</button>
              </div>
          </div>
      </div>
  </div>