<?php

namespace App\Livewire\PermissionsRoles\Permissions;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $Permissions = [];
    public $PermissionSearch;
    public $name, $explain_name, $PermissionID;

    public function render()
    {
        $Permissions = Permission::WHERE('name', 'LIKE', $this->PermissionSearch . '%')->
            orderBy('name', 'ASC')->
            paginate(10);

        $links = $Permissions;
        $this->Permissions = collect($Permissions->items());

        return view('livewire.permissions-roles.permissions.permissions', [
            'links' => $links
        ]);
    }

    public function AddPermissionModalShow()
    {
        $this->reset();
        //$this->mount();
        $this->resetValidation();
        $this->dispatch('PermissionModalShow');
    }

    public function store()
    {
        $this->resetValidation();

        $this->validate([
            'name' => 'required|unique:permissions,name',
        ],
        [
            'name.required' => 'أسم التصريح مطلوب',
            'name.unique' => 'أسم التصريج مستخدم سابقاً',
        ]);

        Permission::create([
            'name' => $this->name,
            'explain_name' => $this->explain_name,
            'guard_name' => 'web'
        ]);

        $this->reset();
        //$this->mount();

        $this->dispatch('PermissionAddSuccess');
    }

    public function GetPermission($PermissionID)
    {
        $this->resetValidation();

        $this->PermissionID = $PermissionID;
        $Permission = Permission::find($PermissionID);
        $this->name = $Permission->name;
        $this->explain_name = $Permission->explain_name;
    }

    public function update()
    {
        $this->resetValidation();
        $this->validate([
            'name' => 'required|unique:permissions,name,'.$this->PermissionID,
        ],
        [
            'name.required' => 'أسم التصريح مطلوب',
            'name.unique' => 'أسم التصريح مستخدم سابقاً',
        ]);

        $Permission = Permission::find($this->PermissionID);
        $Permission->update([
            'name' => $this->name,
            'explain_name' => $this->explain_name
        ]);

        $this->reset();
        //$this->mount();
        
        $this->dispatch('PermissionUpdateSuccess');
    }

    public function destroy()
    {
        Permission::find($this->PermissionID)->delete();

       // $this->mount();

        $this->dispatch('PermissionDestroySuccess');
    }
}
