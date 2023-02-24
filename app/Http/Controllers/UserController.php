<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Throwable;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:user-index')->only('index');
        $this->middleware('can:user-create')->only(['create', 'store']);
        $this->middleware('can:user-edit')->only(['edit', 'update']);
        $this->middleware('can:user-destroy')->only(['destroy']);
        $this->middleware('can:user-password')->only(['changePassword', 'newPassword']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest('id');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($user) {
                    $test = '';
                    foreach ($user->getRoleNames() as $roles) {

                        $test .= "<label class='badge bg-light-success'>" . $roles . "</label>";

                    }
                    return '<div class="d-flex flex-wrap gap-2">'.$test.'</div>';
                })
                ->addColumn('action', function($row){
                    $actions = [];
                    if (auth()->user()->can('user-edit')) {
                        $actions['edit'] = route('user.edit', $row->id);
                    }
                    if (auth()->user()->can('user-destroy')) {
                        $actions['destroy'] = $row->id;
                    }

                    if (auth()->user()->can('user-password')) {
                        $actions['password'] = route('user.change-password', $row->id);
                    }

                    return view('layouts.component.button', $actions);
                })
                ->rawColumns(['action', 'role'])
                ->make(true);
        }
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles  = Role::all();

        return view('user.create', compact('roles'));
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
            'name'      => 'required|unique:users',
            'email'     => 'required|unique:users|email',
            'password'  => 'required'
        ]);

        try{

            User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => bcrypt($validated['password']),
            ]);

            $roles = $request->input('role');
            $userku = User::where('email', $request->input('email'))->first();
            $userku->assignRole($roles);

            $notification = array(
                'success'   => 'Berhasil tambah user '.$validated['name'],
            );

            return redirect()->route('user.index')->with($notification);

        } catch (Throwable $e) {
            return redirect()->route('user.index')->with(['error' => 'Tambah data gagal! ' . $e->getMessage()]);
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
        $roles  = Role::all();
        $detail = User::find($id);

        return view('user.edit', compact('roles', 'detail'));
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
            'name'      => 'required|',
            'email'     => 'required|email',
        ]);

        try{

            $detail = User::find($id);

            $detail->update([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
            ]);

            $detail->syncRoles($request->input('role'));

            $notification = array(
                'success'   => 'Berhasil update user '.$validated['name'],
            );

            return redirect()->route('user.index')->with($notification);

        } catch (Throwable $e) {
            return redirect()->route('user.index')->with(['error' => 'Update data gagal! ' . $e->getMessage()]);
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
            User::find($id)->delete();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function changePassword($id){
        $detail = User::find($id);

        return view('user.change-password', compact('detail'));
    }

    public function newPassword(Request $request){
        $validated = $request->validate([
            'id'        => 'required',
            'password'  => 'required'
        ]);

        try{

            $detail = User::find($request->input('id'));

            $detail->update([
                'password'      => bcrypt($validated['password']),
            ]);

            $notification = array(
                'success'   => 'Berhasil update password dengan nama user '.$detail->name,
            );

            return redirect()->route('user.index')->with($notification);

        } catch (Throwable $e) {
            return redirect()->route('user.index')->with(['error' => 'Update password gagal! ' . $e->getMessage()]);
        }

    }
}
