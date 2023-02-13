<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\RoleRequest;
use Illuminate\Http\Request;
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

        return view('settings.role.index');
    }

    public function createRole(RoleRequest $request)
    {
        $request->merge([
            'guard_name' => 'web'
        ]);
        Role::create($request->all());
        return back()
            ->withSuccess('Role baru berhasil disimpan')
            ->withInput();
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
        $query = Role::findOrFail($request->id);

        return response()->json([
            'status' => 200,
            'role' => $query,
            'success' => 'Role berhasil diambil',
        ]);
    }

    public function updateRole(RoleRequest $request)
    {
        $query = Role::findOrFail($request->id);
        $query->update($request->all());

        return back()
            ->withSuccess('Role berhasil diubah')
            ->withInput();
    }
}
