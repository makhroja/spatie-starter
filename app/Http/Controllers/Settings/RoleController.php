<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\RoleRequest;
use App\Models\Settings\Team;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Role::latest()->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button href="javascript:void(0)" data-id="' . $row->id . '" data-original-title="Show" class="btn btn-outline-primary btn-icon show"><i class="feather icon-eye"></i></button>';

                    $btn = $btn . ' <button href="javascript:void(0)" data-id="' . $row->id . '" data-original-title="Edit" class="btn btn-outline-success btn-icon edit"><i class="feather icon-edit"></i></button>';

                    $btn = $btn . ' <button href="javascript:void(0)" data-name="' . $row->name . '" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-icon delete"><i class="feather icon-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $teams = Team::all();
        $groupedPermissions = Permission::all()->groupBy('group_name');
        return view('settings.role.index', compact('teams','groupedPermissions'));
    }

    public function createRole()
    {
        $teams = Team::all();

        $all_permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('settings.role.createRole', compact('teams', 'all_permissions', 'permission_groups'));
    }

    public function storeRole(RoleRequest $request)
    {
        $role = Role::create([
            'team_id' => $request->team_id,
            'name' => $request->name,
            'guard_name' => 'web'
        ]);
        $permissions = $request->input('permission');

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        return redirect()->route('indexRole')
            ->withSuccess('Role berhasil dibuat');
    }

    public function deleteRole(Request $request)
    {
        $query = Role::findOrFail($request->id);
        $query->delete();

        return response()->json([
            'status' => 200,
            'success' => 'Role berhasil dihapus',
        ]);
    }

    public function showRole(Request $request)
    {
        $query = Role::findOrFail($request->id);

        return response()->json([
            'status' => 200,
            'role' => $query,
            'success' => 'Role berhasil diambil',
        ]);
    }

    public function editRole(Request $request)
    {
        $teams = Team::all();

        $role = Role::findById($request->id, 'web');
        $all_permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('settings.role.editRole', compact('role','teams', 'all_permissions', 'permission_groups'));
    }

    public function updateRole(RoleRequest $request)
    {
        $role = Role::findById($request->id, 'web');
        $permissions = $request->permission;

        if (!empty($permissions)) {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('indexRole')
            ->withSuccess('Role berhasil diubah');
    }
}
