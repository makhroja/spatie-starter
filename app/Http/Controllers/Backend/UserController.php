<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\User\UserRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        // $this->team_id = \Auth::guard('web')->user()->roles->pluck("team_id")->first();

        $this->middleware('can:user.index')->only(['index']);
        $this->middleware('can:user.create')->only(['create', 'store']);
        $this->middleware('can:user.read')->only(['show']);
        $this->middleware('can:user.update')->only(['edit', 'update']);
        $this->middleware('can:user.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::latest()->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button href="javascript:void(0)" data-id="' . $row->id . '" data-original-title="Show" class="btn btn-outline-primary btn-icon show"><i class="feather icon-eye"></i></button>';

                    $btn = $btn . ' <button href="javascript:void(0)" data-id="' . $row->id . '" data-original-title="Edit" class="btn btn-outline-success btn-icon edit"><i class="feather icon-edit"></i></button>';

                    $btn = $btn . ' <button href="javascript:void(0)" data-name="' . $row->name . '" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-icon delete"><i class="feather icon-trash"></i></button>';

                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    $date = Carbon::parse($row->created_at)->translatedFormat('d F Y');
                    return $date;
                })
                ->addColumn('email_verified_at', function ($row) {
                    $date = ($row->email_verified_at != Null) ? Carbon::parse($row->email_verified_at)->translatedFormat('d F Y') : 'Tidak tersedia';
                    return $date;
                })
                ->rawColumns(['action', 'created_at', 'email_verified_at'])
                ->make(true);
        }
        return view('backend.user.index');
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => date('Y-m-d'),
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole('HRD');
        return back()
            ->withSuccess('User baru berhasil disimpan');
    }

    public function edit($id)
    {
        $query = User::findOrFail($id);

        return response()->json([
            'status' => 200,
            'user' => $query,
            'success' => 'User berhasil diambil',
        ]);
    }

    public function show($id)
    {
        $query = User::findOrFail($id);

        return response()->json([
            'status' => 200,
            'user' => $query,
            'success' => 'User berhasil diambil',
        ]);
    }

    public function update(UserRequest $request)
    {
        $query = User::findOrFail($request->user_id);
        $query->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return back()
            ->withSuccess('User berhasil diubah');
    }

    public function destroy($id)
    {
        $query = User::findOrFail($id);
        $query->delete();

        return response()->json([
            'status' => 200,
            'user' => $query,
            'success' => 'User berhasil dihapus',
        ]);
    }
}
