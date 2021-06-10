<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RevokeAdministratorViewRolePermissionToPermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $viewRole = Permission::where('name', '=', 'view-role')->first();

        $administrator = Role::where('name', 'administrator')->first();

        DB::beginTransaction();
        try {
            Schema::disableForeignKeyConstraints();
            DB::table('permission_role')
                ->where('permission_id', $viewRole->id)
                ->where('role_id', $administrator->id)
                ->delete();
            Schema::enableForeignKeyConstraints();

            $permissionsAdministrator = DB::table('permission_role')->where('role_id', $administrator->id)->get()->pluck('permission_id');

            $administrator->syncPermissions($permissionsAdministrator);

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
        $administrator = Role::where('name', 'administrator')->first();

        $viewRole = Permission::where('name', 'view-role')->first();

        DB::beginTransaction();
        try {
            DB::table('permission_role')->insert([
                'permission_id' => $viewRole->id,
                'role_id' => $administrator->id,
            ]);

            $permissionsAdministrator = Permission::where('role_id', $administrator->id)->get()->pluck('permission_id');

            $administrator->syncPermissions($permissionsAdministrator);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return abort(500);
        }
    }
}
