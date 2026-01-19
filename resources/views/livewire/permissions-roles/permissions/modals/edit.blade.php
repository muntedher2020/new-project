<!-- Edit Permission Modal -->
<div wire:ignore.self class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content px-4 py-3">
			<button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
			<div class="modal-body p-md-0">
				<div class="mb-4 text-center">
					<h3 class="pb-1 mb-2">تحرير التصريح</h3>
					<p>تعديل التصريح وفقا لمتطلباتك.</p>
				</div>
				<div class="alert alert-warning" role="alert">
					<h6 class="mb-2 alert-heading">تحذير</h6>
					<p class="mb-0">من خلال تحرير اسم التصريح ، قد تكسر وظائف تصاريح النظام. يرجى التأكد من أنك متأكد تمامًا قبل المتابعة.</p>
				</div>

                <hr class="mt-n2">

				<form id="editPermissionForm" class="pt-2 row" onsubmit="return false">
					<div class="mb-3 col-6">
						<div class="form-floating form-floating-outline">
							<input wire:model='name' type="text" id="editPermissionName" name="editPermissionName" class="form-control @if(strlen($name) > 0) is-filled @endif @error('name') is-invalid is-filled @enderror""
								placeholder="اسم التصريح" tabindex="-1" />
							<label for="editPermissionName">اسم التصريح</label>
						</div>
						@error('name')
                            <small class='text-danger inputerror'> {{ $message }} </small>
                        @enderror
					</div>
                    <div class="mb-2 col-6">
						<div class="form-floating form-floating-outline">
							<input wire:model='explain_name' type="text" id="explain_name" placeholder="شرح التصريح" autofocus
								class="form-control @if(strlen($explain_name) > 0) is-filled @endif @error('explain_name') is-invalid is-filled @enderror"/>
							<label for="explain_name">شرح التصريح</label>
						</div>
						@error('explain_name')
                            <small class='text-danger inputerror'> {{ $message }} </small>
                        @enderror
					</div>

                    <hr class="mb-n2">

					<div class="text-center col-12 demo-vertical-spacing">
						<button wire:click='update' type="submit" class="btn btn-primary me-sm-3 me-1">تعديل التصريح</button>
						<button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
							aria-label="Close">تجاهل</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/ Edit Permission Modal -->
