<?php

use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class GrantSuperadminRoleToAdminWithoutTourismInfoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admins = User::select('users.*')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->whereNull('tourism_info_id')
            ->get();

        $role = Role::where('name', 'superadmin')
            ->first();

        $permissions = Permission::where('name', 'not like', '%user')
            ->where('name', '!=', 'view-ticket')
            ->orWhere('name', 'view-user')
            ->pluck('id');

        DB::beginTransaction();
        try {
            foreach ($admins as $admin) {
                DB::table('role_user')
                    ->where('user_id', $admin->id)
                    ->delete();

                $admin->user_type = $role->id;

                $admin->syncPermissions($permissions);
                $admin->syncRoles([$role->id]);

                $admin->save();
            }

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
        //TODO: reverse migration
    }
}
