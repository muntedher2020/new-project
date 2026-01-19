<!-- Remove Permission Modal -->
<div wire:ignore.self class="modal fade" id="removePermissionModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content px-4 py-3">
			<button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
			<div class="modal-body p-md-0">
				<div class="mb-4 text-center">
					<h3 class="pb-1 mb-2"><span class="text-danger">حذف التصريح</span></h3>
					<p>حذف التصريح وفقا لمتطلباتك.</p>
				</div>
				<div class="alert alert-danger" role="alert">
					<h6 class="mb-2 alert-heading">تحذير</h6>
					<p class="mb-0">من خلال حذف التصريح ، قد تكسر وظائف تصاريح النظام. يرجى التأكد من أنك متأكد تمامًا قبل المتابعة.</p>
				</div>

                <form id="editPermissionForm" class="pt-2" onsubmit="return false">
                    <div class="d-flex justify-content-around">
                        <div class="mb-3 text-center">
                            <label for="editPermissionName">اسم التصريح</label>
                            <div class="text-danger">{{ $name }}</div>
                        </div>
                        <div class="mb-3 text-center">
                            <label for="editPermissionName">شرح التصريح</label>
                            <div class="text-danger">{{ $explain_name }}</div>
                        </div>
                    </div>

                    <hr class="my-n2">

                    <div class="text-center col-12 demo-vertical-spacing">
						<button wire:click='destroy' type="submit" class="btn btn-danger me-sm-3 me-1">حذف التصريح</button>
						<button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
							aria-label="Close">تجاهل</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/ Edit Permission Modal -->
