<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Cookie as HttpFoundationCookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController 
{
    public function login(Request $request){
        if(Auth::attempt($request->only('email','password'))){
            $user = Auth::user();
            $scope = $request->input('scope');
            if($user->isInfluencer() && $scope !== 'influencer'){
                return response([
                    'error'=>'Access denied!' ,
                ], Response::HTTP_FORBIDDEN) ;
            }

            $token = $user->createToken($scope , [$scope])->accessToken;
            
            $cookie= Cookie('jwt',$token,36000);
            return Response([
                'token'=> $token ,
            ])->withCookie($cookie);
        }
            return response([
                'error'=>'invalid credientials' 
            ]) ;
        
    }

    public function logout()  {
        $cookie = Cookie::forget('jwt');
        return Response([
            'message'=> 'success' ,
        ])->withCookie($cookie);
    }
    public function register(RegisterRequest $request){

    $user = User::create($request->only('first_name','last_name','email',)+
    ['password'=>Hash::make($request->input('password'))]+['is_influencer'=>1]
    );
   
    return response()->json($user, 201);

    }
    public function updateInfo (Request $request ){
        $user = Auth::user();
        $user->update($request->only('first_name','last_name','email'));
        return response( new UserResource($user), Response::HTTP_ACCEPTED);
     }
     public function updatePassword(Request $request ){
        $user = Auth::user();
        $user->update($request->only(['password'=>Hash::make($request->input('password'))]));
        return response( new UserResource($user) , Response::HTTP_ACCEPTED);
     }
     public function user(){

        $user= Auth::user();
        $resource = new UserResource($user);
        if($resource->isInfluencer()){
            return $resource;
        }

        return $resource->additional([
           'data' => [
              'permissions' => $user->permissions()
           ]
        ]);
     }
}
