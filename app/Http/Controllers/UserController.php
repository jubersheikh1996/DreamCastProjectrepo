<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{

    function Response($message,$data=null,$status){
        return $data = [
            'status' => $status,
            'message'=> $message,
            'data'   => $data,
        ];
    }


    public function index(){
        
        $data['users'] = User::with('Role')->orderBy('id','desc')->paginate(8);   
        $data['role']  = Role::get();
        return view('user.index',$data);
    }


    public function store(UserRequest $request)
    {

        $imageName = null;
        if ($request->has('file')) {
            $imageName = time() . '_' . Str::random(10) . '.' . $request->file->extension();
            $request->file->move(public_path('file'), $imageName);
        }
        $user = User::with('Role')->Create([
          'name' => $request->name,
          'email'=> $request->email,
          'phone'=> $request->phone,
          'role_id'=> $request->role_id,
          'description'=>$request->description,
          'profile_image'=>$imageName,
        ]);
        $user['role'] = Role::where('id', $request->role_id)->pluck('name')->first();
        return Response::json($user);
    }




    public function destroy($id)
    {
        $user = User::where('id',$id)->delete();
        return Response::json($user);
    }
}
