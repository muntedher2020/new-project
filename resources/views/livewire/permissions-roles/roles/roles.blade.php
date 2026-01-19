@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
@endphp
<div class="mt-n3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom-icon">
                <li class="breadcrumb-item">
                    <a href="{{ route('Permissions.index') }}">
                        <span class="text-muted fw-light fs-5">الصلاحيات و الادوار</span>
                    </a>
                    <i class="breadcrumb-icon icon-base ri ri-arrow-right-s-line icon-22px align-middle"></i>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">إدارة أدوار المستخدمين</a>
                </li>
            </ol>
        </nav>
        {{-- @can('tickets-create') --}}
        {{-- اضافة تذكرة --}}
        <button {{-- wire:click="$dispatch('openModal', 'ticket.modals.createTicket')" --}}
            class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#roleModal">
            <i class="mdi mdi-plus me-1"></i> إضافة دور جديد
        </button>
        {{-- @endcan --}}
    </div>
    <div class="container-fluid ">
        <div class="row mb-4">
            <div class="col-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri ri-search-line"></i></span>
                    <input type="text" wire:model.live="search" class="form-control" placeholder="ابحث عن دور...">
                </div>
            </div>
        </div>

        @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- بطاقات الأدوار -->
        <div class="row">
            @forelse($roles as $role)
                @if (Auth::user()->hasRole('OWNER') || $role->name != 'OWNER')
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card border border-primary h-100 shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4 mt-n3">
                                    <div class="text-center">
                                        <i class="menu-icon icon-base ri ri-shield-user-fill icon-42px mt-n5 mb-0"></i>
                                        <h5 class="text-primary Tajawal mt-n2">
                                            {{ $role->name }}
                                        </h5>
                                    </div>

                                    <div class="mb-2">
                                        <span class="Tajawal text-primary fs-3">
                                            {{ $role->users_count }}
                                        </span>
                                        مستخدم
                                    </div>

                                    <div class="mb-2">
                                        <span class="Tajawal text-primary fs-3">
                                            {{-- <i class="ri ri-key-fill me-1 icon-24px"></i> --}}
                                            {{ $role->permissions_count }}
                                        </span>
                                        صلاحية
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-n5">
                                    <div class="role-heading">
                                        <p class="card-text text-muted text-center">{{ $role->description }}</p>
                                        <div class="users-list mb-3">
                                            <h6 class="mb-1">
                                                <i class="ri ri-group-fill me-1"></i>
                                                المستخدمين ({{ $role->users_count }})
                                            </h6>
                                            @forelse($role->users->take(5) as $user)
                                            <span class="badge bg-light text-primary rounded mb-1 me-1">
                                                <i class="ri ri-user-line icon-16px me-1"></i>
                                                {{ $user->name }}
                                            </span>
                                            @empty
                                            <p class="text-muted small">لا يوجد مستخدمين في هذا الدور</p>
                                            @endforelse

                                            @if ($role->users_count > 5)
                                            <span class="badge bg-light text-dark">
                                                +{{ $role->users_count - 5 }} أكثر
                                            </span>
                                            @endif
                                        </div>

                                        <!-- الصلاحيات الممنوحة -->
                                        <div class="mb-3">
                                            <h6 class="">
                                                <i class="ri ri-shield-user-line icon-20px me-1"></i>
                                                الصلاحيات
                                            </h6>
                                            <div class="permissions-list">
                                                @forelse($role->permissions->take(3) as $permission)
                                                <span class="badge bg-light text-primary mb-1 me-1 Tajawal">
                                                    <i class="ri ri-checkbox-circle-line me-1"></i>
                                                    {{ $permission->name }}
                                                </span>
                                                @empty
                                                <p class="text-muted small">لا توجد صلاحيات لهذا الدور</p>
                                                @endforelse

                                                @if ($role->permissions_count > 3)
                                                <span class="badge bg-light">
                                                    +{{ $role->permissions_count - 3 }} أكثر
                                                </span>
                                                @endif
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between">
                                    <button wire:click="editRole({{ $role->id }})" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i> تعديل
                                    </button>
                                    <button wire:click="confirmDelete({{ $role->id }})" class="btn btn-sm btn-outline-danger"
                                        @if (in_array($role->name, ['OWNER', 'admin', 'it-staff', 'user',
                                        'department-manager'])) disabled @endif>
                                        <i class="fas fa-trash me-1"></i> حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد أدوار</h5>
                            <p class="text-muted">لم يتم إنشاء أي أدوار بعد</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleModal">
                                <i class="fas fa-plus me-1"></i> إنشاء دور جديد
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- زر إضافة دور جديد -->
        <div class="fixed-bottom mb-4 me-4" style="z-index: 1030;">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn rounded-pill btn-icon btn-primary btn-lg waves-effect waves-light"
                    data-bs-toggle="modal" data-bs-target="#roleModal">
                    <span class="icon-base ri ri-add-large-line  icon-22px"></span>
                </button>
                {{-- <button class="btn btn-primary btn-lg rounded-circle shadow" data-bs-toggle="modal"
                    data-bs-target="#roleModal">
                    <i class="ti tabler-plus"></i>
                </button> --}}
            </div>
        </div>
    </div>

    <!-- Modal إضافة دور -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">إضافة دور جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="createRole">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الدور <span
                                            class="text-danger">*</span></label>
                                    <input type="text" wire:model="name"
                                        class="form-control @error('name') is-invalid @enderror" id="name">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">الوصف <span
                                            class="text-danger">*</span></label>
                                    <input type="text" wire:model="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        id="description">
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-3">الصلاحيات <span class="text-danger">*</span></label>
                            <div class="row">
                                @foreach ($allPermissions as $group => $permissions)
                                <div class="col-12 mb-3">
                                    <div class="card stat-card h-100 bg-transparent border border-success text-success">
                                        <div class="card-header d-flex justify-content-between bg-label-success py-2">
                                            <h5 class="card-title mb-0">{{ $this->getGroupName($group) }}</h5>
                                            <small class="text-body-secondary">
                                                <div class="card-icon">
                                                    <span class="badge bg-label-warning fs-5 rounded fw-bold">
                                                        {{ count($permissions) }}
                                                    </span>
                                                </div>
                                            </small>
                                        </div>
                                        <div class="card-body">
                                            <div class="row gap-2">
                                                @foreach ($permissions as $permission)
                                                <div
                                                    class="col stat-permissions bg-label-success me-2 border border-success text-nowrap py-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="">
                                                            <label class="switch switch-md">
                                                                <input wire:model="permissions"
                                                                    value="{{ $permission->id }}"
                                                                    id="permission{{ $permission->id }}" type="checkbox"
                                                                    class="switch-input">
                                                                <span class="switch-toggle-slider">
                                                                    <span class="switch-on">
                                                                        <i class="icon-base mdi mdi-check"></i>
                                                                    </span>
                                                                    <span class="switch-off">
                                                                        <i class="icon-base mdi mdi-x"></i>
                                                                    </span>
                                                                </span>
                                                                <span class="switch-label fw-normal fs-6">
                                                                    <h6 class="mb-0">{{ $permission->name }}</h6>
                                                                    <small>{{ Str::limit($permission->description, 40)
                                                                        }}</small>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('permissions')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تعديل دور -->
    @if ($editId)
    <div class="modal fade show d-block" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">تعديل الدور</h5>
                    <button type="button" wire:click="cancelEdit" class="btn-close"></button>
                </div>
                <form wire:submit.prevent="updateRole">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editName" class="form-label">اسم الدور <span
                                            class="text-danger">*</span></label>
                                    <input type="text" wire:model="name"
                                        class="form-control @error('name') is-invalid @enderror" id="editName">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editDescription" class="form-label">الوصف <span
                                            class="text-danger">*</span></label>
                                    <input type="text" wire:model="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        id="editDescription">
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الصلاحيات <span class="text-danger">*</span></label>
                            <div class="row">
                                @foreach ($allPermissions as $group => $permissions)
                                <div class="col-12 mb-3">
                                    <div class="card stat-card h-100 bg-transparent border border-success text-success">
                                        <div class="card-header d-flex justify-content-between bg-label-success py-2">
                                            <h5 class="card-title mb-0 mt-2">{{ $this->getGroupName($group) }}</h5>
                                            <small class="text-body-secondary">
                                                <div class="card-icon">
                                                    <span class="badge bg-label-warning fs-5 rounded fw-bold">
                                                        {{ count($permissions) }}
                                                    </span>
                                                </div>
                                            </small>
                                        </div>
                                        <div class="card-body">
                                            <div class="row gap-2">
                                                @foreach ($permissions as $permission)
                                                <div
                                                    class="col stat-permissions bg-label-success me-2 border border-success text-nowrap py-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="">
                                                            <label class="switch switch-md">
                                                                <input wire:model="permissions"
                                                                    value="{{ $permission->id }}"
                                                                    id="permission{{ $permission->id }}" type="checkbox"
                                                                    class="switch-input">
                                                                <span class="switch-toggle-slider">
                                                                    <span class="switch-on">
                                                                        <i class="icon-base mdi mdi-check"></i>
                                                                    </span>
                                                                    <span class="switch-off">
                                                                        <i class="icon-base mdi mdi-x"></i>
                                                                    </span>
                                                                </span>
                                                                <span class="switch-label fw-normal fs-6">
                                                                    <h6 class="mb-0">{{ $permission->name }}</h6>
                                                                    <small class="fw-light ">{{
                                                                        Str::limit($permission->explain_name, 40)
                                                                        }}</small>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-3 col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="badge rounded bg-label-primary me-4 p-2">
                                                            <label
                                                                class="switch switch-md border rounded p-2 h-100 w-100 permission-item bg-label-hover-danger">
                                                                <input wire:model="permissions"
                                                                    value="{{ $permission->id }}"
                                                                    id="permission{{ $permission->id }}" type="checkbox"
                                                                    class="switch-input">
                                                                <span class="switch-toggle-slider mt-2">
                                                                    <span class="switch-on">
                                                                        <i class="icon-base mdi mdi-check"></i>
                                                                    </span>
                                                                    <span class="switch-off">
                                                                        <i class="icon-base mdi mdi-x"></i>
                                                                    </span>
                                                                </span>
                                                                <span class="switch-label fw-normal fs-6">
                                                                    {{ $permission->name }} <br>
                                                                    {{ Str::limit($permission->description, 40) }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="card-info">
                                                            <h5 class="mb-0">{{ $permission->name }}</h5>
                                                            <small>{{ Str::limit($permission->description, 40)
                                                                }}</small>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('permissions')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cancelEdit" class="btn btn-secondary">إلغاء</button>
                        <button type="submit" class="btn btn-primary">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Modal تأكيد الحذف -->
    @if ($confirmingDelete)
    <div class="modal fade show d-block" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من أنك تريد حذف هذا الدور؟</p>
                    <p class="text-danger">هذا الإجراء لا يمكن التراجع عنه.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelDelete">إلغاء</button>
                    <button type="button" class="btn btn-danger"
                        wire:click="deleteRole({{ $confirmingDelete }})">حذف</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif


    <style>
        .stat-card {
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .stat-card:hover::before {
            left: 100%;
        }

        .stat-permissions {
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            /* overflow: hidden; */
        }

        .stat-permissions:hover {
            transform: translateY(-5px);
            box-shadow: 0 7px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</div>