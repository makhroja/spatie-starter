<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Permission::latest()->get();

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

        return view('settings.permission.index');
    }

    public function createPermission(PermissionRequest $request)
    {
        $request->merge([
            'guard_name' => 'web'
        ]);
        Permission::create($request->all());
        return back()
            ->withSuccess('Permission baru berhasil disimpan');
    }

    public function deletePermission(Request $request)
    {
        $query = Permission::findOrFail($request->id);
        $query->delete();

        return response()->json([
            'status' => 200,
            'success' => 'Permission berhasil dihapus',
        ]);
    }

    public function showPermission(Request $request)
    {
        $query = Permission::findOrFail($request->id);

        return response()->json([
            'status' => 200,
            'permission' => $query,
            'success' => 'Permission berhasil diambil',
        ]);
    }

    public function editPermission(Request $request)
    {
        $query = Permission::findOrFail($request->id);

        return response()->json([
            'status' => 200,
            'permission' => $query,
            'success' => 'Permission berhasil diambil',
        ]);
    }

    public function updatePermission(PermissionRequest $request)
    {
        $query = Permission::findOrFail($request->id);
        $query->update($request->all());

        return back()
            ->withSuccess('Permission baru berhasil disimpan');
    }
}
