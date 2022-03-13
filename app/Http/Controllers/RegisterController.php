<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register()
    {
       $validador= Validator::make(request()->all(),[
            'username'=>'required|unique:users',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ]);

        if($validador->fails()){
            return response()->json($validador->messages());
        }

        $user = User::create([
            'username'=>request('username'),
            'email'=>request('email'),
            'password'=>Hash::make(request('password'))
        ]);
        // generate token, autologin, atau hanya login berhasil
        return response()->json(['message'=>'succesfully registered']);

    }
}
