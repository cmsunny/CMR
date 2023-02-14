<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
               'name' => 'list_employee',
               'guard_name' => 'web',
               'group'=>'employee',
            ],
            [
                'name' => 'create_employee',
                'guard_name' => 'web',
                'group'=>'employee',
            ],
            [
                'name' => 'edit_employee',
                'guard_name' => 'web',
                'group'=>'employee',
            ],
            [
                'name' => 'delete_employee',
                'guard_name' => 'web',
                'group'=>'employee',
            ],
            [
                'name' => 'list_company',
                'guard_name' => 'web',
                'group'=>'company',
            ],
            [
                'name' => 'create_company',
                'guard_name' => 'web',
                'group'=>'company',
            ],
            [
                'name' => 'edit_company',
                'guard_name' => 'web',
                'group'=>'company',
            ],
            [
                'name' => 'delete_company',
                'guard_name' => 'web',
                'group'=>'company',
            ],
            [
                'name' => 'list_role',
                'guard_name' => 'web',
                'group'=>'employee',
             ],
             [
                 'name' => 'create_role',
                 'guard_name' => 'web',
                 'group'=>'employee',
             ],
             [
                 'name' => 'edit_role',
                 'guard_name' => 'web',
                 'group'=>'employee',
             ],
             [
                 'name' => 'delete_role',
                 'guard_name' => 'web',
                 'group'=>'employee',
             ],

        ];
      foreach($permissions as $permission){
            Permission::create($permission);
      }

        $admin = Role::create([ 'name'=> 'admin' ]);

        $adminPermissions = Permission::get();
        $admin->permissions()->sync($adminPermissions);
        $subAdmin = Role::create([ 'name'=> 'subadmin' ]);
        $subAdminPermissions = Permission::where('name', 'create_employee')->orWhere('name', 'list_employee')->get();
        $subAdmin->permissions()->sync($subAdminPermissions);

    }
}
