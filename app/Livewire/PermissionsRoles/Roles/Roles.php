<?php

namespace App\Livewire\PermissionsRoles\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class Roles extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $description, $role_id;
    public $permissions = [];
    public $selectedPermissions = [];
    public $editId = null;
    public $search = '';
    public $confirmingDelete = null;
    public $isModalOpen = false;

    protected function rules()
    {
        $rules = [
            'description' => 'required|min:3|max:255',
            'permissions' => 'required|array|min:1',
        ];

        if ($this->editId) {
            $rules['name'] = [
                'required',
                'min:3',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->editId)
            ];
        } else {
            $rules['name'] = 'required|min:3|max:255|unique:roles,name';
        }

        return $rules;
    }

    public function render()
    {
        $roles = Role::withCount(['permissions', 'users'])
            ->with(['users' => function($query) {
                $query->select('id', 'name')->take(5);
            }, 'permissions' => function($query) {
                $query->select('id', 'name')->take(3);
            }])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            //->orderBy('name')
            ->get();

        $allPermissions = Permission::all()->groupBy(function($item) {
            $parts = explode('-', $item->name);
            return $parts[0];
        });

        return view('livewire.permissions-roles.roles.roles', [
            'roles' => $roles,
            'allPermissions' => $allPermissions
        ]);
    }

    public function createRole()
    {
        $this->validate();

        $role = Role::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

         // الحصول على أسماء الصلاحيات من الـ IDs
        $permissionNames = Permission::whereIn('id', $this->permissions)
            ->pluck('name')
            ->toArray();

        $role->syncPermissions($permissionNames);

        $this->dispatch('RoleAddSuccess');
        session()->flash('message', 'تم إنشاء الدور بنجاح.');
        $this->resetInputs();
    }

    // تأكد من أن اسم الدالة مطابق تماماً لما في القالب
    public function editRole($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editId = $role->id;
        $this->name = $role->name;
        $this->description = $role->description;
        $this->permissions = $role->permissions->pluck('id')->toArray(); // هذا يبقى IDs للعرض في الواجهة
    }

    public function updateRole()
    {
        $this->validate([
            'name' => 'required|min:3|max:255|unique:roles,name,' . $this->editId,
            'description' => 'required|min:3|max:255',
            'permissions' => 'required|array|min:1',
        ]);

        $role = Role::findOrFail($this->editId);
        $role->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // الحصول على أسماء الصلاحيات من الـ IDs
        $permissionNames = Permission::whereIn('id', $this->permissions)
            ->pluck('name')
            ->toArray();

        $role->syncPermissions($permissionNames);

        $this->dispatch('RoleUpdateSuccess');

        session()->flash('message', 'تم تحديث الدور بنجاح.');
        $this->cancelEdit();
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        // منع حذف الأدوار الأساسية
        if (in_array($role->name, ['admin', 'it_staff', 'user', 'department_manager'])) {
            session()->flash('error', 'لا يمكن حذف هذا الدور لأنه دور أساسي في النظام.');
            $this->confirmingDelete = null;
            return;
        }

        // منع حذف الدور إذا كان مرتبطاً بمستخدمين
        if ($role->users()->count() > 0) {
            session()->flash('error', 'لا يمكن حذف هذا الدور لأنه مرتبط بمستخدمين.');
            $this->confirmingDelete = null;
            return;
        }

        $role->delete();

        $this->dispatch('RoleDestroySuccess');
        session()->flash('message', 'تم حذف الدور بنجاح.');
        $this->confirmingDelete = null;
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = null;
    }

    public function cancelEdit()
    {
        $this->editId = null;
        $this->resetInputs();
    }

    private function resetInputs()
    {
        $this->name = '';
        $this->description = '';
        $this->permissions = [];
    }

    public function getGroupName($group)
    {
        $groupNames = [
            'view' => 'عرض',
            'create' => 'إنشاء',
            'edit' => 'تعديل',
            'delete' => 'حذف',
            'assign' => 'تعيين',
            'ticket' => 'التذاكر',
            'tickets' => 'التذاكر',
            'department' => 'الأقسام',
            'departments' => 'الأقسام',
            'user' => 'المستخدمين',
            'users' => 'المستخدمين',
            'role' => 'الأدوار',
            'roles' => 'الأدوار',
            'permission' => 'الصلاحيات',
            'permissions' => 'الصلاحيات',
            'report' => 'التقارير',
            'reports' => 'التقارير'
        ];

        return $groupNames[$group] ?? $group;
    }
}
