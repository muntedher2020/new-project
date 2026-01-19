<!-- Add ElectronicForm Modal -->
{{-- <div wire:ignore.self class="modal fade" id="addelectronicformModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="p-4 modal-content p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-md-0">
                <div class="mb-4 text-center mt-n4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-2">
                            <span class="text-primary">اضافة</span> الاستمارات الالكترونية جديد
                        </h3>
                        <p class="text-muted">
                            <i class="mdi mdi-cog me-1"></i>
                            قم بإدخال تفاصيل الاستمارات الالكترونية في النموذج أدناه
                        </p>
                    </div>
                </div>
                <hr class="mt-n2">
                <div wire:loading.remove wire:target="store, GetElectronicForm">
                    <form id="addelectronicformModalForm" autocomplete="off">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input wire:model.defer='title' type="text"
                                                id="modalElectronicFormtitle" placeholder="العنوان"
                                                class="form-control @error('title') is-invalid is-filled @enderror"/>
                                            <label for="modalElectronicFormtitle">العنوان</label>
                                        </div>
                                        @error('title')
                                            <small class='text-danger inputerror'> {{ $message }} </small>
                                        @enderror
                                    </div>
                            <div class="mb-3 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input wire:model.defer='description' type="text"
                                                id="modalElectronicFormdescription" placeholder="الوصف"
                                                class="form-control @error('description') is-invalid is-filled @enderror"/>
                                            <label for="modalElectronicFormdescription">الوصف</label>
                                        </div>
                                        @error('description')
                                            <small class='text-danger inputerror'> {{ $message }} </small>
                                        @enderror
                                    </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col">
                                    <div class="form-check form-switch">
                                        <input wire:model.defer='active' type="checkbox"
                                            id="modalElectronicFormactive" value="1"
                                            class="form-check-input @error('active') is-invalid @enderror"/>
                                        <label class="form-check-label" for="modalElectronicFormactive">مفعل</label>
                                    </div>
                                    @error('active')
                                        <small class='text-danger inputerror'> {{ $message }} </small>
                                    @enderror
                                </div>
                        </div>
                        <hr class="my-0">
                        <div class="text-center col-12 demo-vertical-spacing mb-n4">
                            <button wire:click='store' wire:loading.attr="disabled" type="button"
                                class="btn btn-primary me-sm-3 me-1">اضافة</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">تجاهل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Modal -->
<div class="modal fade" id="addelectronicformModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة استمارة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="addTitle" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="addTitle" placeholder="أدخل عنوان الاستمارة">
                        <div class="invalid-feedback" id="addTitleError"></div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="addDescription" class="form-label">الوصف</label>
                        <textarea class="form-control" id="addDescription" rows="3" placeholder="أدخل وصف الاستمارة"></textarea>
                        <div class="invalid-feedback" id="addDescriptionError"></div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="addActive" checked>
                            <label class="form-check-label" for="addActive">تفعيل الاستمارة</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" onclick="closeForm()" data-bs-dismiss="modal" aria-label="Close">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="saveForm()">
                    <span id="saveButtonText">حفظ</span>
                    <span id="saveButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--/ Add ElectronicForm Modal -->
