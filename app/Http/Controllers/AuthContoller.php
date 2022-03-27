<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class AuthContoller extends Controller
{

    public function register(Request $request){
      $fields = $request->validate([
          'name'=> 'required|string',
          'email'=> 'required|string|unique:users,email',
          'password'=> 'required|string|confirmed'
      ]);

    $user = User::create([
        'name'=>$fields['name'],
        'email'=>$fields['email'],
        'password'=> bcrypt($fields['password'])
    ]);

    $token = $user->createToken('mytokenApp')->plainTextToken;

    $response = [
    'user'=>$user,
    'token'=>$token
    ];


    return response($response,201);

    }


    public function login(Request $request){
        $fields = $request->validate([
            'email'=> 'required|string',
            'password'=> 'required|string',
        ]);
      //Check Email
      $user = User::where('email',$fields['email'])->first();
      //Check Password
      if(!$user || !Hash::check($fields['password'], $user->password)){
       return response([
           'message'=>'Bad Creds'
       ], 401);
      }
      $token = $user->createToken('mytokenApp')->plainTextToken;

      $response = [
      'user'=>$user,
      'token'=>$token
      ];


      return response($response,201);

      }


      public function logout(Request $request){
        $user = $request->user();
        $user ->tokens()->delete();
        return [
            'message'=>'Logged out'
        ];
    }


}
