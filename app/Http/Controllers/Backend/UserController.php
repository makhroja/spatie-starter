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
        $this->middleware(['auth', 'verified', function ($request, $next) {
            $this->user = \Auth::guard('web')->user();
            return $next($request);
        }]);
    }


    public function index(Request $request)
    {
        #check jika user login || permisionya sesuai
        if (is_null($this->user) || !$this->user->can('user.index')) {
            abort(403, 'Sorry! You are Unauthorized to see this page!');
        }

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
        #check jika user login || permisionya sesuai
        if (is_null($this->user) || !$this->user->can('user.create')) {
            abort(403, 'Sorry! You are Unauthorized to see this page!');
        }

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
        #check jika user login || permisionya sesuai
        if (is_null($this->user) || !$this->user->can('user.edit')) {
            abort(403, 'Sorry! You are Unauthorized to see this page!');
        }

        $query = User::findOrFail($id);

        return response()->json([
            'status' => 200,
            'user' => $query,
            'success' => 'User berhasil diambil',
        ]);
    }

    public function show($id)
    {
        #check jika user login || permisionya sesuai
        if (is_null($this->user) || !$this->user->can('user.show')) {
            abort(403, 'Sorry! You are Unauthorized to see this page!');
        }

        $query = User::findOrFail($id);

        return response()->json([
            'status' => 200,
            'user' => $query,
            'success' => 'User berhasil diambil',
        ]);
    }

    public function update(UserRequest $request)
    {
        #check jika user login || permisionya sesuai
        if (is_null($this->user) || !$this->user->can('user.edit')) {
            abort(403, 'Sorry! You are Unauthorized to see this page!');
        }

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
        #check jika user login || permisionya sesuai
        if (is_null($this->user) || !$this->user->can('user.delete')) {
            abort(403, 'Sorry! You are Unauthorized to see this page!');
        }

        $query = User::findOrFail($id);
        $query->delete();

        return response()->json([
            'status' => 200,
            'user' => $query,
            'success' => 'User berhasil dihapus',
        ]);
    }
}
