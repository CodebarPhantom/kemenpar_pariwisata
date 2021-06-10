<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;

class InsertSuperadminToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = new Role();
        $role->name = 'superadmin';
        $role->display_name = 'Super Administrator';
        $role->description = 'Super Administrator';
        $role->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where('name', 'superadmin');
        Schema::disableForeignKeyConstraints();
        $role->delete();
        Schema::enableForeignKeyConstraints();
    }
}
