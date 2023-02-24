<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:role-index')->only('index');
        $this->middleware('can:role-create')->only(['create', 'store']);
        $this->middleware('can:role-edit')->only(['edit', 'update']);
        $this->middleware('can:role-destroy')->only(['destroy']);
        $this->middleware('can:role-access')->only(['access', 'sendAccess']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actions = [];
                    if (auth()->user()->can('role-edit')) {
                        $actions['edit'] = route('role.edit', $row->id);
                    }
                    if (auth()->user()->can('role-destroy')) {
                        $actions['destroy'] = $row->id;
                    }

                    if (auth()->user()->can('role-access')) {
                        $actions['access'] = route('role.access', $row->id);
                    }
                    return view('layouts.component.button', $actions);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('role.index');
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('role.create');
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

            Role::create([
                'name'          => $validated['nama'],
                'guard_name'    => 'web'
            ]);

            $notification = array(
                'success'   => 'Berhasil tambah role '.$validated['nama']
            );

            return redirect()->route('role.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('role.index')->with(['error' => 'Tambah data gagal! ' . $e->getMessage()]);
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
        $detail = Role::find($id);
        
        return view('role.edit', compact('detail'));
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
            $detail = Role::find($id);

            $detail->update([
                'name'          => $validated['nama'],
            ]);

            $notification = array(
                'success'   => 'Berhasil update role '.$validated['nama']
            );

            return redirect()->route('role.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('role.index')->with(['error' => 'Update data gagal! ' . $e->getMessage()]);
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
            Role::find($id)->delete();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function access($id){
        $permission = Permission::all();
        $detail     = Role::find($id);
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('role.access', compact('permission', 'detail', 'rolePermissions'));
    }

    public function sendAccess(Request $request){
        $request->validate([
            'id'    => 'required'
        ]);

        $role = Role::find($request->input('id'));        
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('role.index')->with('success','Berhasil menambahkan permission');

    }
}
