<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        // $this->team_id = \Auth::guard('web')->user()->roles->pluck("team_id")->first();

        $this->middleware('can:permission.index')->only(['index']);
        $this->middleware('can:permission.create')->only(['create', 'store']);
        $this->middleware('can:permission.read')->only(['show']);
        $this->middleware('can:permission.update')->only(['edit', 'update']);
        $this->middleware('can:permission.destroy')->only(['destroy']);
    }

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

    public function store(PermissionRequest $request)
    {
        $request->merge([
            'guard_name' => 'web'
        ]);
        Permission::create($request->all());
        return back()
            ->withSuccess('Permission baru berhasil disimpan');
    }

    public function destroy($id)
    {
        $query = Permission::findOrFail($id);
        $query->delete();

        return response()->json([
            'status' => 200,
            'success' => 'Permission berhasil dihapus',
        ]);
    }

    public function show($id)
    {
        $query = Permission::findOrFail($id);

        return response()->json([
            'status' => 200,
            'permission' => $query,
            'success' => 'Permission berhasil diambil',
        ]);
    }

    public function edit($id)
    {
        $query = Permission::findOrFail($id);

        return response()->json([
            'status' => 200,
            'permission' => $query,
            'success' => 'Permission berhasil diambil',
        ]);
    }

    public function update(PermissionRequest $request)
    {
        $query = Permission::findOrFail($request->permission_id);
        $query->update($request->all());

        return back()
            ->withSuccess('Permission baru berhasil disimpan');
    }
}
