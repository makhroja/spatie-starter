<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\TeamRequest;
use Illuminate\Http\Request;
use App\Models\Settings\Team;
use Yajra\DataTables\DataTables;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        // $this->team_id = \Auth::guard('web')->user()->roles->pluck("team_id")->first();

        $this->middleware('can:team.index')->only(['index']);
        $this->middleware('can:team.create')->only(['create', 'store']);
        $this->middleware('can:team.read')->only(['show']);
        $this->middleware('can:team.update')->only(['edit', 'update']);
        $this->middleware('can:team.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Team::latest()->get();

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

        return view('settings.team.index');
    }

    public function store(TeamRequest $request)
    {
        $request->merge([
            'guard_name' => 'web'
        ]);
        Team::create($request->all());
        return back()
            ->withSuccess('Team baru berhasil disimpan');
    }

    public function destroy($id)
    {
        $query = Team::findOrFail($id);
        $query->delete();

        return response()->json([
            'status' => 200,
            'success' => 'Team berhasil dihapus',
        ]);
    }

    public function show($id)
    {
        $query = Team::findOrFail($id);

        return response()->json([
            'status' => 200,
            'team' => $query,
            'success' => 'Team berhasil diambil',
        ]);
    }

    public function edit($id)
    {
        $query = Team::findOrFail($id);

        return response()->json([
            'status' => 200,
            'team' => $query,
            'success' => 'Team berhasil diambil',
        ]);
    }

    public function update(TeamRequest $request)
    {
        $query = Team::findOrFail($request->team_id);
        $query->update($request->all());

        return back()
            ->withSuccess('Team berhasil diubah');
    }
}
