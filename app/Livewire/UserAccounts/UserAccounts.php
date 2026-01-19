<?php

namespace App\Livewire\UserAccounts;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserAccounts extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $user;
    public $name;
    public $email;
    public $password, $ConfirmPassword;
    public $role;
    public $active;
    public $roles = [];
    public $editId = null;
    public $search = '';
    public $confirmingDelete = null;

    protected $listeners = [
        'selectedRole',
        'selectedStatus',
    ];

    public function hydrate()
    {
        $this->dispatch('select2');
        $this->dispatch('flatpickr');
    }

    protected $rules = [
        'name' => 'required|min:3|max:255|unique:users,name',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|same:ConfirmPassword',
        'role' => 'required',
        'active' => 'nullable',
    ];
    protected $messages = [
        'name.required' => 'أسم المستخدم مطلوب',
        'name.min' => 'أسم المستخدم يجب أن يكون على الأقل 3 أحرف',
        'name.max' => 'أسم المستخدم يجب أن لا يتجاوز 255 حرف',
        'name.unique' => 'الاسم تم استخدامه',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
        'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
        'password.required' => 'كلمة المرور مطلوبة',
        'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف',
        'password.same' => 'يجب أن تتطابق كلمة المرور مع تأكيد كلمة المرور',
        'role.required' => 'دور المستخدم مطلوب',
        //'role.array' => 'دور المستخدم يجب أن يكون مصفوفة',
        'role.min' => 'يجب اختيار دور واحد على الأقل',
    ];

    public function render()
    {
        $users = User::with(['roles'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.user-accounts.user-accounts', [
            'users' => $users,
            'allRoles' => Role::all(),
        ]);
    }

    public function selectedRole($role)
    {
        $this->role = $role;
    }
    public function selectedStatus($active)
    {
        $this->active = $active;
    }

    public function createUsersAccount()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetInputs();
        $this->dispatch('showCreateUsersAccountModal');
    }

    public function store()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'active' => $this->active,
        ]);

        $user->roles()->sync($this->role);

        $this->dispatch('success',
            title: 'حساب المستخدم',
            message: 'تم إنشاء حساب المستخدم بنجاح.'
        );

        $this->resetInputs();
    }

    public function editUser($id)
    {
        $this->getUser($id);

        $this->dispatch('showEditUsersAccountModal');
    }

    public function getUser($id)
    {
        $this->user = User::with('roles')->findOrFail($id);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->password = '';
        $this->role = $this->user->roles->pluck('id')->toArray();
        $this->active = $this->user->active;
    }

    public function update()
    {
        $rules = [
            'name' => 'required|min:3|max:255|unique:users,name,'.$this->user->id,
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'password' => 'nullable|min:8|same:ConfirmPassword',
            'role' => 'required',
            'active' => 'nullable',
        ];

        if ($this->password) {
            $rules['password'] = 'min:8';
        }

        $this->validate($rules);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'active' => $this->active
        ]);

        if ($this->password) {
            $this->user->update(['password' => Hash::make($this->password)]);
        }

        $this->user->roles()->sync($this->role);

        $this->dispatch('success',
            title: 'حساب المستخدم',
            message: 'تم تحديث حساب المستخدم بنجاح.',
        );

        $this->cancelEdit();
    }

    public function cancelEdit()
    {
        $this->user = null;
        $this->resetInputs();
    }

    public function deleteUser($id)
    {
        $this->getUser($id);

        $this->dispatch('showDeleteUsersAccountModal');
    }

    public function confirmDelete()
    {
        $this->user->delete();

        $this->dispatch('success',
            title: 'حساب المستخدم',
            message: 'تم حذف حساب المستخدم بنجاح.',
        );

        $this->cancelDelete();
    }

    public function cancelDelete()
    {
        $this->user = '';
        $this->resetInputs();
    }

    private function resetInputs()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->active = '';
    }
}
