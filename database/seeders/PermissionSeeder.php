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
            ],
            [
                'name' => 'create_employee',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit_employee',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete_employee',
                'guard_name' => 'web',
            ],
            [
                'name' => 'list_company',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create_company',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit_company',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete_company',
                'guard_name' => 'web',
            ]
           
        ];
        Permission::insert($permissions);
    
        $admin = Role::create([ 'name'=> 'admin' ]);
        $adminPermissions = Permission::get();
        $admin->permissions()->sync($adminPermissions);
        $subAdmin = Role::create([ 'name'=> 'subadmin' ]);
        $subAdminPermissions = Permission::where('name', 'create_employee')->orWhere('name', 'list_employee')->get();
        $subAdmin->permissions()->sync($subAdminPermissions);

    }
}
