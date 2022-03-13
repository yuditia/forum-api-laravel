<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function __construct()
    {
        return auth()->shouldUse('api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Forum::with('user:id,username')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);
        $user = auth()->user();
        
            if(Auth::check()){
                $user->forums()->create([
                    'title'=>request('title'),
                    'body'=>request('body'),
                    'slug'=>Str::slug(request('title'),'-'),
                    'category'=>request('category'),
                ]);
                // generate token, autologin, atau hanya login berhasil
                return response()->json(['message'=>'succesfully posted']);
            } 
            return response()->json(['message'=>'not authorized, you have to login first!']);

        // }catch(\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e){
        //     
        // }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Forum::with('user:id,username')->find($id);
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
        $this->validateRequest($request);
        $user = auth()->user();
            if(Auth::check()){
                Forum::find($id)->update([
                    'title'=>request('title'),
                    'body'=>request('body'),
                    'category'=>request('category'),
                ]);
                // generate token, autologin, atau hanya login berhasil
                return response()->json(['message'=>'succesfully updated']);
            } 
            return response()->json(['message'=>'not authorized, you have to login first!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function validateRequest($request)
    {
        $validador= Validator::make(request()->all(),[
            'title'=>'required|min:10',
            'body'=>'required',
            'category'=>'required',
        ]);

        if($validador->fails()){
            return response()->json($validador->messages());
        }
        return true;
    }
    public function destroy($id)
    {
        //
    }
}
