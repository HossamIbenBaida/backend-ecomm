<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

class RoleController 
{
  
    public function index()
    {
        Gate::authorize('view' , 'roles');
        return RoleResource::collection(Role::all());
    }

   
    public function store(Request $request)
    {
        Gate::authorize('edit' , 'roles');

        $role = Role::create($request->only('name'));
        if($permissions= $request->input('permissions')){
            foreach ($permissions as $permission_id ){
            DB::table('role_permission')->insert([
                'role_id'=>$role->id ,
                'permission_id'=>$permission_id
            ]);
        }}
        return response(
            new RoleResource($role) ,
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Gate::authorize('view' , 'roles');
        return new RoleResource(Role::find($id));

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
        Gate::authorize('edit' , 'roles');
        $role= Role::find($id) ;
        $role->update($request->only('name'));

        DB::table('role_permission')->where('role_id' ,  $role->id)->delete();

        if($permissions= $request->input('permissions')){
            foreach ($permissions as $permission_id ){
            DB::table('role_permission')->insert([
                'role_id'=>$role->id ,
                'permission_id'=>$permission_id
            ]);
        }}


        return response(new RoleResource($role) , Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('edit' , 'roles');
        DB::table('role_permission')->where('role_id' ,  $id)->delete();
        Role::destroy($id);
        return response(null , Response::HTTP_NO_CONTENT);
    }
}
