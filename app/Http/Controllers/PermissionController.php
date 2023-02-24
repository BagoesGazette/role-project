<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:permission-index')->only('index');
        $this->middleware('can:permission-create')->only(['create', 'store']);
        $this->middleware('can:permission-edit')->only(['edit', 'update']);
        $this->middleware('can:permission-destroy')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actions = [];
                    if (auth()->user()->can('permission-edit')) {
                        $actions['edit'] = route('permission.edit', $row->id);
                    }
                    if (auth()->user()->can('permission-destroy')) {
                        $actions['destroy'] = $row->id;
                    }
                    return view('layouts.component.button', $actions);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'  => 'required|min:5|max:50'
        ]);

        try{

            Permission::create([
                'name'          => $validated['nama'],
                'guard_name'    => 'web'
            ]);

            $notification = array(
                'success'   => 'Berhasil tambah permission '.$validated['nama']
            );

            return redirect()->route('permission.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('permission.index')->with(['error' => 'Tambah data gagal! ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detail = Permission::find($id);
        
        return view('permission.edit', compact('detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama'  => 'required|min:5|max:50'
        ]);

        try{
            $detail = Permission::find($id);

            $detail->update([
                'name'          => $validated['nama'],
            ]);

            $notification = array(
                'success'   => 'Berhasil update permission '.$validated['nama']
            );

            return redirect()->route('permission.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('permission.index')->with(['error' => 'Update data gagal! ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Permission::find($id)->delete();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
