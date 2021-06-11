<?php

namespace App\Http\Controllers\User;

use App\Models\Role;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth, DB, View, DataTables, Alert, Lang, Laratrust;


class RoleController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-role')) return abort(404);
        return view('user.role.index');
    }

    public function dataRole()
    {
        if (!Laratrust::isAbleTo('view-role')) return abort(404);

        $roles = Role::orderBy('name','ASC');
        return DataTables::of($roles)
            ->editColumn('action',function($role){
                $show =  '<a href="'.route('role.show',$role->id).'" class="btn btn-flat btn-xs" title="'.Lang::get('Show').'"><i class="fa fa-eye fa-sm"></i></a>';
                $edit =  '<a href="'.route('role.edit',$role->id).'" class="btn btn-flat btn-xs" title="'.Lang::get('Edit').'"><i class="fa fa-pencil-alt fa-sm"></i></a>';
                 return $show.$edit;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        if (!Laratrust::isAbleTo('view-role')) return abort(404);

        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions()->select('id')->pluck('id')->toArray();
        $permissions = Permission::get();
        return view('user.role.show', compact('role', 'rolePermissions','permissions'));
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('view-role')) return abort(404);

        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions()->select('id')->pluck('id')->toArray();
        $permissions = Permission::get();
        return view('user.role.edit', compact('role', 'rolePermissions','permissions'));

    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('view-role')) return abort(404);

        $role = Role::findOrFail($id);
        $this->validate($request, [
            //'nama' => 'required|alpha_dash|unique:roles,name,' . $id,
            'hak_akses.*' => 'nullable|integer',
            'description' => 'required|unique:roles,description'
        ]);

        $permissions = [];
        foreach ($request->input('hak_akses', []) as $permissionId) {

                array_push($permissions, $permissionId);
        }

        DB::beginTransaction();
        try {
            $role->description = $request->description;
            $role->save();
            $role->syncPermissions($permissions);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Mengubah Hak Akes '.$role->display_name);

        Alert::alert('Success', 'Role '.$role->display_name.' Telah di Ubah', 'info');
        return redirect()->route('role.index');


    }
}
