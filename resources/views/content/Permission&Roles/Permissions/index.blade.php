@extends('layouts/layoutMaster')

@section('title', 'تصاريح حسابات المستخدمين')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
  @vite(['resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('content')

	@livewire('permissions-roles.permissions.permissions')

@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
  @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/app-access-permission.js', 'resources/assets/js/modal-add-permission.js', 'resources/assets/js/modal-edit-permission.js'])
  @vite(['resources/assets/js/extended-ui-sweetalert2.js'])

	<script>
    document.addEventListener('livewire:navigated', () => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-start',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        $('.page-item').click(function () {
            window.scrollTo(0, 0);
        });

        window.addEventListener('PermissionModalShow', event =>
        {
            $("#modalPermissionName").focus();
        });
        window.addEventListener('PermissionAddSuccess',
                event => {
            Toast.fire({
              icon: 'success',
              title: 'تم اضافة التصريح بنجاح'
            })

            $("#modalPermissionName").focus();
                }
            );
        window.addEventListener('PermissionUpdateSuccess', event => {
            $('#editPermissionModal').modal('hide');

            Toast.fire({
              icon: 'success',
              title: 'تم تعديل التصريح بنجاح'
            })

            $("#modalPermissionName").focus();
        });
        window.addEventListener('PermissionDestroySuccess',
                event => {
                    $('#removePermissionModal').modal('hide');

            Toast.fire({
              icon: 'success',
              title: 'تم حذف التصريح بنجاح'
            })
                }
            );
        window.addEventListener('PermissionNotFond',
                event => {
                    Toast.fire({
              icon: 'error',
              title: 'لم يتم العثور على تصريح'
            })
                }
            );
      });
	</script>
@endsection
