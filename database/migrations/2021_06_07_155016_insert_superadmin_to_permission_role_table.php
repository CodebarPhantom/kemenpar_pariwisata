<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class InsertSuperadminToPermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissionsSuperadmin = Permission::where('name', 'not like', '%user')
            ->where('name', '!=', 'view-ticket')
            ->orWhere('name', 'view-user')
            ->get();

        $superadmin = Role::where('name', 'superadmin')->first();

        DB::beginTransaction();
        try {
            foreach ($permissionsSuperadmin as $permissionSuperadmin) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permissionSuperadmin->id,
                    'role_id' => $superadmin->id,
                ]);
            }

            $superadmin->syncPermissions($permissionsSuperadmin);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return abort(500);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $superadmin = Role::where('name', 'superadmin')->first();

        DB::beginTransaction();
        try {
            // DB::table('permission_role')
            //     ->where('role_id', $superadmin->id)
            //     ->delete();

            $permissionsSuperadmin = DB::table('permission_role')->where('role_id', $superadmin->id)->get()->pluck('permission_id');

            $superadmin->syncPermissions($permissionsSuperadmin);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return abort(500);
        }
    }
}
