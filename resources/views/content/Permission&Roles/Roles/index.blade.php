@extends('layouts/layoutMaster')

@section('title', 'ادوار المستخدمين')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
  @vite(['resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('content')

	@livewire('permissions-roles.roles.roles')

@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
  @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/app-access-roles.js', 'resources/assets/js/modal-add-role.js'])
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
          window.addEventListener('RoleModalShow', event =>
                  {
                      $("#RoleNameModal").focus();
                  }
              );

          window.addEventListener('RoleAddSuccess',
                  event => {
              Toast.fire({
                icon: 'success',
                title: 'تم اضافة الدور بنجاح'
              })

              $("#RoleNameModal").focus();
                  }
              );
          window.addEventListener('RoleAddError',
                  event => {
              Toast.fire({
                icon: 'error',
                title: 'لم يتم تحديد صلاحيات للدور'
              })

              $("#RoleNameModal").focus();
                  }
              );
          window.addEventListener('RoleUpdateSuccess',
                  event => {
                      $('#editRoleModal').modal('hide');

              Toast.fire({
                icon: 'success',
                title: 'تم تعديل الدور بنجاح'
              })
                  }
              );
          window.addEventListener('RoleDestroySuccess',
                  event => {
                      $('#removeRoleModal').modal('hide');

              Toast.fire({
                icon: 'success',
                title: 'تم حذف الدور بنجاح'
              })
                  }
              );
      });
	</script>
@endsection
