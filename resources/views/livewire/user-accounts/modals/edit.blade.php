<!-- Edit Administrator Modal -->
<div wire:ignore.self class="modal fade" id="updateUsersAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="p-3 modal-content p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-md-0 mt-n4">
                <div class="mb-4 text-center">
                    <h3 class="pb-1 mb-2">تحرير حساب المستخدم</h3>
                    <p>تحرير بيانات حساب المستخدم .</p>
                </div>
                <hr class="text-primary mt-n2">

                <h5 wire:loading wire:target="editUser" wire:loading.class="d-flex justify-content-center">جار معالجة البيانات...</h5>
                <h5 wire:loading wire:target="update" wire:loading.class="d-flex justify-content-center">جار حفظ البيانات...</h5>

                <div wire:loading.remove wire:target="editUser, update">
                    <form id="editUserAccountForm" class="pt-2 row" onsubmit="return false">
                        <div class="row">
                            <div class="mb-3 col-6">
                                <div class="form-floating form-floating-outline">
                                    <input wire:model.defer='name' type="text" id="editName" class="form-control" placeholder="اسم المستخدم">
                                    <label for="modalName">اسم المستخدم</label>
                                </div>
                                @error('name')
                                <small class='text-danger inputerror'> {{ $message }} </small>
                                @enderror
                            </div>
                            <div class="mb-3 col-6">
                                <div class="form-floating form-floating-outline text-primary">
                                    <input wire:model.defer='email' type="text" id="modalEmail" class="form-control"
                                        placeholder="البريد الالكتروني">
                                    <label for="modalEmail">البريد الالكتروني</label>
                                </div>
                                @error('email')
                                <small class='text-danger inputerror'> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline">
                                    <input wire:model.defer='password' type="text" id="Password" class="form-control"
                                        placeholder="كلمة المرور" />
                                    <label for="Password">كلمة المرور</label>
                                </div>
                                @error('password')
                                <small class='text-danger inputerror'> {{ $message }} </small>
                                @enderror
                            </div>
                            <div class="col-6">
                                <div class="form-floating form-floating-outline">
                                    <input wire:model.defer='ConfirmPassword' type="text" id="Confirm-Password"
                                        class="form-control" placeholder="تأكيد كلمة المرور" />
                                    <label for="Confirm-Password">تأكيد كلمة المرور</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <select wire:model.defer="role" class="form-select" id="editRole">
                                        <option value=""></option>
                                        @foreach ($allRoles as $Role)
                                            <option value="{{ $Role->id }}">{{ $Role->name }}</option>
                                        @endforeach
                                    </select>
                                    <label id="AdministratorStatus">الدور</label>
                                    @error('role')
                                    <small class='text-danger inputerror'> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-2 form-floating form-floating-outline">
                                    <select wire:model.defer="active" class="form-select" id="AdministratorStatus">
                                        <option value=""></option>
                                        <option value="1">مفعل</option>
                                        <option value="0">غير مفعل</option>
                                    </select>
                                    <label id="AdministratorStatus">حالة المستخدم</label>
                                    @error('active')
                                    <small class='text-danger inputerror'> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="text-primary mt-n2">

                        <div class="text-center col-12 mb-n4">
                            <button wire:click='update' type="submit"
                                class="btn btn-primary me-sm-3 me-1">تعديل</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">تجاهل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Edit Administrator Modal -->
