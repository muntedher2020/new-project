<!-- Add Permission Modal -->
<div wire:ignore.self class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content px-4 py-3">
			<button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
			<div class="modal-body p-md-0">
				<div class="mb-4 text-center">
					<h3 class="pb-1 mb-2">أضف تصريحاً جديداً</h3>
					<p>التصاريح التي يمكنك استخدامها وتعيينها للمستخدمين.</p>
				</div>

                <hr class="mt-n2">

				<form {{-- method="POST" --}} id="addPermissionForm" class="row">
					<div class="mb-3 col-6">
						<div class="form-floating form-floating-outline">
							<input wire:model='name' type="text" id="modalPermissionName" placeholder="اسم التصريح" autofocus
								class="form-control @if(strlen($name) > 0) is-filled @endif @error('name') is-invalid is-filled @enderror"/>
							<label for="modalPermissionName">اسم التصريح</label>
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
						<button wire:click='store' type="submit" class="btn btn-primary me-sm-3 me-1">إنشاء التصريح</button>
						<button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
							aria-label="Close">تجاهل</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/ Add Permission Modal -->
