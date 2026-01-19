<div wire:ignore.self class="modal fade" id="createUsersAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="p-3 modal-content p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-md-0 mt-n4">
                <div class="mb-4 text-center">
                    <h3 class="pb-1 mb-2">اضافة مستخدم جديد</h3>
                    <p>اضافة بيانات المستخدم .</p>
                </div>

                <hr class="mt-n2">

                <form id="addAdministratorForm" class="pt-2 row" onsubmit="return false" autocomplete="off">
                    <div class="row">
                        <div class="mb-3 col-6">
                            <div class="form-floating form-floating-outline">
                                <input wire:model.defer='name' type="text" class="form-control" id="name"
                                    placeholder="أسم المستخدم">
                                <label for="modalName">اسم المستخدم</label>
                            </div>
                            @error('name')
                                <small class='text-danger inputerror'> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <div class="form-floating form-floating-outline">
                                <input wire:model.defer='email' type="text" class="form-control" id="modalEmail"
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
                        <div class="col-6">
                            <div class="form-floating form-floating-outline">
                                <select wire:model.defer="role" class="form-select" id="role">
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
                        <div class="col-6">
                            <div class="form-floating form-floating-outline">
                                <select wire:model.defer="active" class="form-select" id="status">
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

                    <hr class="mt-n2">

                    <div class="text-center col-12 mb-n4">
                        <button wire:click='store' type="submit" class="btn btn-primary me-sm-3 me-1">اضافة
                            مستخدم</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">تجاهل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
