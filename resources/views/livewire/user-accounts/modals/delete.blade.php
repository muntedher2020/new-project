<!-- Remove Administrator Modal -->
<div wire:ignore.self class="modal fade" id="deleteUsersAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="p-3 modal-content p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body p-md-0 mt-n4">
        <div class="mb-4 text-center">
          <h3 class="pb-1 mb-2 text-warning">حذف حساب المستخدم</h3>
          <p>خذف بيانات حساب المستخدم .</p>
        </div>
        <hr class="text-warning mt-n2">

        <h5 wire:loading wire:target="deleteUser" wire:loading.class="d-flex justify-content-center">جار معالجة البيانات...</h5>
        <h5 wire:loading wire:target="confirmDelete" wire:loading.class="d-flex justify-content-center">جار حذف حساب المستخدم...</h5>

        <div wire:loading.remove wire:target="deleteUser, confirmDelete">
          <div class="alert alert-warning {{ $active ? '':'hidden' }} {{--alert-dismissible--}}" role="alert">
            <h4 class="alert-heading d-flex align-items-center">
              <i class="mdi mdi-alert-circle-outline mdi-24px me-2"></i>حساب المستخدم!!
            </h4>
            <hr>
            <p class="mb-0">
              يجب ان يكون حساب المستخدم غير مغعل لاتمام عملية الحذف.
            </p>
          </div>

          <form id="removeAdministratorForm" class="pt-2 row" onsubmit="return false">
            <div class="row">
              <div class="mb-3 col-6">
                <div class="text-warning">
                  <label for="AdministratorName">أسم المستخدم</label>
                  <div class="form-control-plaintext mt-n2">{{ $name }}</div>
                </div>
              </div>
              <div class="mb-3 col-6">
                <div class="text-warning">
                  <label for="AdministratorEmail">البريد الالكتروني</label>
                  <div class="form-control-plaintext mt-n2">{{ $email }}</div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col mb-3">
                <div class="text-warning">
                  <label for="AdministratorEmail">الدور</label>
                  @if($this->user)
                    @foreach ($this->user->roles->pluck('name')->toArray() as $rol)
                      <div class="form-control-plaintext mt-n2">{{ $rol }}</div>
                    @endforeach
                  @endif
                </div>
              </div>
              <div class="col">
                <div class="text-warning">
                  <label for="AdministratorStatus">حالة المستخدم</label>
                  <div class="form-control-plaintext mt-n2">{{ $active ? 'مفعل':'غير مفعل' }}</div>
                </div>
              </div>
            </div>

            <p class="text-warning">هل أنت متأكد من أنك تريد حذف هذا المستخدم؟</p>

            <hr class="text-warning mt-n2">

            <div class="text-center col-12 mb-n4">
              <button wire:click='confirmDelete' {{ $active ? 'disabled' :'' }} type="submit"
                class="btn btn-warning me-sm-3 me-1">تأكيد الحذف</button>
              <button wire:click="cancelDelete" type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                aria-label="Close">تجاهل</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Remove Administrator Modal -->
