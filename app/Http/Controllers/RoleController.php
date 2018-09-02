<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use App\Role;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('manage.roles.index')->withRoles($roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        $permissions = Permission::all();
        return view('manage.roles.create')->withPermissions($permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'display_name'=>'required|max:255',
            'name' => 'required|max:100|alpha_dash|unique:permissions,name',
            'description' => 'sometimes|max:255'
        ]);

        $role = new Role();
        $role->display_name = $request->input('display_name');
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->save();
        if($request->has('permissions')){
            $role->syncPermissions(explode(',',$request->input('permissions')));
        }
        Session::flash('success','Successfuly created new role:'.$role->display_name);
        return redirect()->route('roles.show',$role->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::where('id',$id)->with('permissions')->first();
        return view('manage.roles.show')->with('role',$role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::where('id',$id)->with('permissions')->first();
        $permissions = Permission::all();
        return view('manage.roles.edit')->withRole($role)->withPermissions($permissions);
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
        $this->validate($request,[
            'display_name'=>'required|max:255',
            'description' => 'sometimes|max:255'
        ]);

        $role = Role::findOrFail($id);
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();
        if($request->has('permissions')){
            $role->syncPermissions(explode(',',$request->input('permissions')));
        }
        Session::flash('success','Successfuly update the'.$role->display_name);
        return redirect()->route('roles.show',$id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
