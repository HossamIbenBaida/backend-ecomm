<?php

namespace App\Http\Controllers\Admin;
//use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash as Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\UserRole;
use Auth;
use Illuminate\Support\Facades\Gate;

class UserController 
{
 public function index (){
     Gate::authorize('view' , 'users');
     $users = User::paginate();
     return UserResource::collection($users);
 }
 public function store (UserCreateRequest $request){
   Gate::authorize('edit' , 'users');
   $user = User::create($request->only('first_name','last_name','email')+['password'=>Hash::make(1234)]);
   
   UserRole::create([
      'user_id'=>$user->id,
      'role_id'=>$request->input('role_id'),
   ]);

   return response( new UserResource($user) , Response::HTTP_CREATED);
}
public function show($id){
   Gate::authorize('view' , 'users');
   $user=User::find($id);
   return new UserResource($user);
}

public function update (UserUpdateRequest $request , $id ){
   Gate::authorize('edit' , 'users');
   $user = User::find($id);
   $user->update($request->only('first_name','last_name','email'));
   UserRole::where('user_id',$user->id)->delete();
   UserRole::create([
      'user_id'=>$user->id,
      'role_id'=>$request->input('role_id'),
   ]);
   return response( new UserResource($user) , Response::HTTP_ACCEPTED);
}

public function destroy($id){
    Gate::authorize('edit' , 'users');
    User::destroy($id);
    return response(null,Response::HTTP_NO_CONTENT);
}



}
