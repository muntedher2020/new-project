<!-- Edite ElectronicForm Modal -->
<div wire:ignore.self class="modal fade" id="editelectronicformModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="p-4 modal-content p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-md-0">
                <div class="mb-4 text-center mt-n4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-2">
                            <span class="text-warning">تعديل</span> بيانات الاستمارات الالكترونية
                        </h3>
                        <p class="text-muted">
                            <i class="mdi mdi-cog me-1"></i>
                            قم بتعديل تفاصيل الاستمارات الالكترونية في النموذج أدناه
                        </p>
                    </div>
                </div>
                <hr class="mt-n2">
                <div wire:loading.remove wire:target="update, GetElectronicForm">
                    <form id="editElectronicFormModalForm" autocomplete="off">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input wire:model.defer='title' type="text"
                                                id="modalEditElectronicFormtitle" placeholder="العنوان"
                                                class="form-control @error('title') is-invalid is-filled @enderror" />
                                            <label for="modalEditElectronicFormtitle">العنوان</label>
                                        </div>
                                        @error('title')
                                            <small class='text-danger inputerror'> {{ $message }} </small>
                                        @enderror
                                    </div>
                            <div class="mb-3 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input wire:model.defer='description' type="text"
                                                id="modalEditElectronicFormdescription" placeholder="الوصف"
                                                class="form-control @error('description') is-invalid is-filled @enderror" />
                                            <label for="modalEditElectronicFormdescription">الوصف</label>
                                        </div>
                                        @error('description')
                                            <small class='text-danger inputerror'> {{ $message }} </small>
                                        @enderror
                                    </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col">
                                    <div class="form-check form-switch">
                                        <input wire:model='active' type="checkbox"
                                            id="modalEditElectronicFormactive" value="1"
                                            class="form-check-input @error('active') is-invalid @enderror" />
                                        <label class="form-check-label" for="modalEditElectronicFormactive">مفعل</label>
                                    </div>
                                    @error('active')
                                        <small class='text-danger inputerror'> {{ $message }} </small>
                                    @enderror
                                </div>
                        </div>
                        <hr class="my-0">
                        <div class="text-center col-12 demo-vertical-spacing mb-n4">
                            <button wire:click='update' wire:loading.attr="disabled" type="button"
                                class="btn btn-warning me-sm-3 me-1">تعديل</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">تجاهل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Edite ElectronicForm Modal -->
