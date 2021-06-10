<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tourism\TourismInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables, Laratrust;

class UserController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        return view('user.index');
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $roles = Role::get();

        if (!Laratrust::hasRole('superadmin')) {
            $roles = $roles->where('name', '!=', 'superadmin');
        }

        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $roles = Role::get('id')
            ->pluck('id')
            ->toArray();

        $superadmin = Role::where('name', 'superadmin')
            ->pluck('id')
            ->toArray('id');

        $this->validate($request, [
            'type_user' => 'required',
            'pic_name' => 'required',
            'password' => 'required',
            'email' => 'required|unique:users,email',
            'photo' => 'required',
            'type_user' => 'required|in:' . implode(',', $roles),
            'tourism_place' => 'required_unless:type_user,' . implode(',', $superadmin),
        ]);

        $email = $request->email;
        // if (User::whereEmail($email)->exists()) {
        //     return $this->validationError(Lang::get('The email has already been taken.'));
        // }

        DB::beginTransaction();
        try {
            $filePathPhoto = $request->photo->store('public/photos');
            $fileUrlPhoto = url('/storage') . str_replace('public', '', $filePathPhoto);

            $user = new User();
            $user->tourism_info_id = $request->tourism_place;
            $user->name = $request->pic_name;
            $user->email = $email;
            $user->password = bcrypt($request->password);
            $user->url_photo = $fileUrlPhoto;
            $user->user_type = $request->type_user;
            if ($user->user_type == 2) {
                $user->raw_password = $request->password;
            }
            $user->save();
            $user->syncRoles([$request->type_user]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Membuat User Baru ' . $request->pic_name);
        Alert::alert('Success', 'User Baru Telah di Daftarkan', 'success');
        return redirect()->route('user.index');
    }

    public function show($id)
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $this->checkPermission($id);

        $userData = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.url_photo',
            'users.is_active',
            'users.user_type',
            'users.raw_password',
            'ti.name as tourism_name'
        )
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'users.tourism_info_id')
            ->where('users.id', $id)
            ->first();
        return view('user.show', compact('userData'));
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $this->checkPermission($id);

        $userData = User::select(
            'users.id as idUser',
            'users.tourism_info_id',
            'users.name',
            'users.email',
            'users.url_photo',
            'users.is_active',
            'users.user_type',
            'ti.name as tourism_name'
        )
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'users.tourism_info_id')
            ->where('users.id', $id)
            ->first();
        $tourismInfo = TourismInfo::select('id', 'name')
            ->whereId($userData->tourism_info_id)
            ->first();

        $roles = Role::get();

        if (!Laratrust::hasRole('superadmin')) {
            $roles = $roles->where('name', '!=', 'superadmin');
        }

        return view('user.edit', compact('userData', 'tourismInfo', 'roles'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $roles = Role::get('id')
            ->pluck('id')
            ->toArray();

        $superadmin = Role::where('name', 'superadmin')
            ->pluck('id')
            ->toArray('id');

        $this->validate($request, [
            'type_user' => 'required',
            'pic_name' => 'required',
            'email' => 'required',
            'type_user' => 'required|in:' . implode(',', $roles),
            'tourism_place' => 'required_unless:type_user,' . implode(',', $superadmin),
        ]);

        $user = User::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->photo) {
                Storage::delete(str_replace(url('storage'), 'public', $user->url_logo));
                $filePathPhoto = $request->photo->store('public/photos');
                $fileUrlPhoto = url('/storage') . str_replace('public', '', $filePathPhoto);
                $user->url_photo = $fileUrlPhoto;
            }
            $user->name = $request->pic_name;
            $user->user_type = $request->type_user;
            $user->tourism_info_id = $request->tourism_place;
            $user->is_active = $request->is_active;
            $user->save();
            $user->syncRoles([$request->type_user]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Mengubah User ' . $request->pic_name);

        Alert::alert('Success', 'User ' . $user->name . ' Telah di Ubah', 'info');
        return redirect()->route('user.index');
    }

    public function dataUsers()
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $users = User::select(
            'users.id',
            'users.tourism_info_id',
            'users.name',
            'users.email',
            'users.url_photo',
            'users.is_active',
            'users.user_type',
            'ti.name as tourism_name'
        )
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'users.tourism_info_id')
            ->orderBy('name', 'ASC');

        if (!Laratrust::hasRole('superadmin')) {
            $users = $users->where('tourism_info_id', auth()->user()->tourism_info_id);
        }

        return DataTables::of($users)
            ->editColumn('name', function ($user) {
                return '<a href="' .
                    $user->url_photo .
                    '" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="30px" height="30px" src="' .
                    $user->url_photo .
                    '"></a>' .
                    ' ' .
                    $user->name;
            })
            ->editColumn('user_type', function ($user) {
                if ($user->user_type == 1) {
                    $color = 'success';
                    $type = 'Administrator';
                } elseif ($user->user_type == 2) {
                    $color = 'info';
                    $type = 'User';
                } elseif ($user->user_type == 3) {
                    $color = 'danger';
                    $type = 'Super Administrator';
                }
                if ($user->is_active == 0) {
                    $color1 = 'danger';
                    $status = 'Inactive';
                } elseif ($user->is_active == 1) {
                    $color1 = 'success';
                    $status = 'Active';
                }

                return '<span class="badge bg-' .
                    $color .
                    '">' .
                    $type .
                    '</span> <span class="badge bg-' .
                    $color1 .
                    '">' .
                    Lang::get($status) .
                    '</span>';
            })
            ->editColumn('action', function ($user) {
                $show =
                    '<a href="' .
                    route('user.show', $user->id) .
                    '" class="btn btn-info btn-flat btn-xs" title="' .
                    Lang::get('Show') .
                    '"><i class="fa fa-eye fa-sm"></i></a>';
                $edit =
                    '<a href="' .
                    route('user.edit', $user->id) .
                    '" class="btn btn-danger btn-flat btn-xs" title="' .
                    Lang::get('Edit') .
                    '"><i class="fa fa-pencil-alt fa-sm"></i></a>';
                return $show . $edit;
            })
            ->rawColumns(['name', 'action', 'user_type'])
            ->make(true);
    }

    public function dataTourisms(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $tourismInfos = TourismInfo::select('id', 'name', 'price', 'code')->paginate(25);
        } else {
            $tourismInfos = TourismInfo::select('id', 'name', 'price', 'code')
                ->where('name', 'like', '%' . $search . '%')
                ->where('is_active', 1)
                ->paginate(25);
        }

        $response = [];
        foreach ($tourismInfos as $tourismInfo) {
            $response[] = [
                'id' => $tourismInfo->id,
                'text' =>
                    $tourismInfo->code . ' - ' . $tourismInfo->name . ' (' . number_format($tourismInfo->price) . ')',
            ];
        }

        echo json_encode($response);
        exit();
    }

    private function checkPermission($id)
    {
        if (!Laratrust::hasRole('superadmin')) {
            if (
                !User::where('id', $id)->first() ||
                User::where('id', $id)->first()->tourism_info_id != auth()->user()->tourism_info_id
            ) {
                return abort(
                    config('laratrust.middleware.handlers.abort.code'),
                    config('laratrust.middleware.handlers.abort.message')
                );
            }
        }
    }
}
