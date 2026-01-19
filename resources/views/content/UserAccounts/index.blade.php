@extends('layouts/layoutMaster')

@section('title', 'حسابات المشرفين')

@section('vendor-style')
    @vite([
        'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
        'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
        'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
        'resources/assets/vendor/libs/select2/select2.scss',
        'resources/assets/vendor/libs/@form-validation/form-validation.scss'
    ])
    @vite(['resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
    @vite(['resources/assets/vendor/libs/notyf/notyf.scss', 'resources/assets/vendor/libs/animate-css/animate.scss'])
@endsection

@section('vendor-script')
    @vite([
        'resources/assets/vendor/libs/moment/moment.js',
        'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
        'resources/assets/vendor/libs/select2/select2.js',
        'resources/assets/vendor/libs/@form-validation/popular.js',
        'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
        'resources/assets/vendor/libs/@form-validation/auto-focus.js',
        'resources/assets/vendor/libs/cleave-zen/cleave-zen.js'
    ])
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
    @vite(['resources/assets/vendor/libs/notyf/notyf.js'])
@endsection

@section('content')

  @livewire('user-accounts.user-accounts')

@endsection

@section('page-script')
    @vite('resources/assets/js/app-user-list.js')
    @vite(['resources/assets/js/extended-ui-sweetalert2.js'])
    @vite(['resources/assets/js/ui-toasts.js'])

    <script>    
      document.addEventListener('livewire:navigated', () => {
        window.addEventListener('showCreateUsersAccountModal', event => {
            $('#name').select();
        });
        window.addEventListener('showEditUsersAccountModal', event => {
            $('#updateUsersAccountModal').modal('show');
        });
        window.addEventListener('showDeleteUsersAccountModal', event => {
            $('#deleteUsersAccountModal').modal('show');
        });

        $(document).ready(function() {
            function initSelect2(selector, eventName, parentModal) {
                $(selector).select2({
                    placeholder: 'اختيار',
                    dropdownParent: $(parentModal),
                });
                $(selector).on('change', function(e) {
                    livewire.emit(eventName, e.target.value);
                });
            }
            initSelect2('#role', 'selectedRole', '#createUsersAccountModal');
            initSelect2('#status', 'selectedStatus', '#createUsersAccountModal');
            livewire.on('select2', () => {
                initSelect2('#role', 'selectedRole', '#createUsersAccountModal');
                initSelect2('#status', 'selectedStatus', '#createUsersAccountModal');
            });
        });

        // إعدادات SweetAlert2
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

        window.addEventListener('success', event => {
            $('#createUsersAccountModal').modal('hide');
            $('#updateUsersAccountModal').modal('hide');
            $('#deleteUsersAccountModal').modal('hide');
            
            Toast.fire({
                icon: 'success',
                title: event.detail.title + '<hr>' + event.detail.message
            })
        });
      });
    </script>
@endsection
