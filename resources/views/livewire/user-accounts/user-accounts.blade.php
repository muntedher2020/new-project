@php
  use Illuminate\Support\Facades\Cache;
  use Illuminate\Support\Facades\Auth;
@endphp
<div class="mt-n3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom-icon">
                <li class="breadcrumb-item">
                    <a href="{{ route('User-Accounts.index') }}">
                        <span class="text-muted fw-light fs-5">حسابات المستخدمين</span>
                    </a>
                    <i class="breadcrumb-icon icon-base ri ri-arrow-right-s-line icon-22px align-middle"></i>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">إدارة المستخدمين</a>
                </li>
            </ol>
        </nav>

        @can('user-create')
          <button wire:click="createUsersAccount"
              class="btn rounded-pill btn-icon btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#createUsersAccountModal">
              <i class="ri ri-user-add-line"></i>
          </button>

          @include('livewire.user-accounts.modals.create')
        @endcan
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row mx-4 mt-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri ri-user-search-line icon-20px"></i></span>
                                <input type="text" wire:model.live="search" class="form-control" placeholder="ابحث عن مستخدم...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive p-0 mt-3">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-secondary">اسم المستخدم</th>
                                    <th class="text-secondary">البريد الإلكتروني</th>
                                    <th class="text-secondary text-center">الأدوار</th>
                                    <th class="text-secondary text-center">اخر ظهور</th>
                                    <th class="text-secondary"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    @if(!$user->hasRole('OWNER') || Auth::user()->hasRole('OWNER'))
                                        <tr class="fw-normal">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h6 class="text-xs mb-0">
                                                    {{ $user->email }}
                                                </h6>
                                            </td>
                                            <td class="text-center">
                                                @foreach ($user->roles as $role)
                                                    <span class="badge bg-primary bg-glow">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td class="number text-center">
                                                @php $active = 'text-dark'; @endphp
                                                @if ($user->active == 1)
                                                    @php $active = 'text-success'; @endphp
                                                @else
                                                    @php $active = 'text-danger'; @endphp
                                                @endif
                                                <small class="{{ $active }}">{{ $user->active == 1 ? 'مفعل':'غير مفعل' }}</small>

                                                @if (Cache::has('user-online' . $user->id))
                                                    <small class="text-success">متصل</small>
                                                @else
                                                    <small class="text-danger">غير متصل</small>
                                                @endif
                                                <div>
                                                    @if ($user->last_seen != null)
                                                        <span class="number" dir="ltr">{{ Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}</span>
                                                    @else
                                                        <small>لم يظهر ابداً</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group" role="group" aria-label="Second group">
                                                    <button wire:click="editUser({{ $user->id }})" type="button" class="btn btn-label-success waves-effect p-1" data-bs-toggle="tooltip" title="تعديل">
                                                        <i class="icon-base ri ri-user-follow-line"></i>
                                                    </button>
                                                    <button wire:click="deleteUser({{ $user->id }})" type="button" class="btn btn-label-danger waves-effect p-1" data-bs-toggle="tooltip" title="حذف">
                                                        <i class="icon-base ri ri-user-unfollow-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4">
                                            <p class="text-muted">لا يوجد مستخدمين لعرضهم</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 pt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
        @if($user)
            @include('livewire.user-accounts.modals.edit')
            @include('livewire.user-accounts.modals.delete')
        @endif
    </div>

    <!-- Modal تأكيد الحذف -->
    {{-- @if($confirmingDelete)
      <div class="modal fade show d-block" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد الحذف</h5>
                      <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                  </div>
                  <div class="modal-body">
                      <p>هل أنت متأكد من أنك تريد حذف هذا المستخدم؟</p>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" wire:click="cancelDelete">إلغاء</button>
                      <button type="button" class="btn btn-danger" wire:click="deleteUser({{ $confirmingDelete }})">حذف</button>
                  </div>
              </div>
          </div>
      </div>
      <div class="modal-backdrop fade show"></div>
    @endif --}}
</div>
