<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Hash;


class UserController extends Controller
{
    public function getAll(){

        $users = User::all();
        return response()->json([
            'users' => $users
        ]);
    }

    public function getOne(Request $request, $id){

        $user = User::find($id);
        return response()->json([
            'user' => $user
        ]);
    }

    public function add(Request $request){

        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required'
        ]);

        $user = [
            'id_user'   => Str::uuid(),
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => Hash::make($request->input('password'))
        ];

        $add_user = User::create($user);
        return response()->json([
            'status'    => 'Success Add User', 
            'user'      => $add_user
        ]);
    }

    public function update(Request $request, $id){

        $user = User::find($id);
        if($user==null){
            return response()->json([
                'status'    => 'Update Failed', 
                'message'   => 'user tidak ditemukan dengan id ' . $id 
            ]);
        }

        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$id.',id_user',
            'password'  => 'required'
        ]);

        $user_data = [
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => Hash::make($request->input('password'))
        ];
        

        $update_user = $user->update($user_data);
        return response()->json([
            'status'    => 'Success Update User', 
            'user'      => $user
        ]);
    }

    public function delete($id){

        $user = User::find($id);
        if($user==null){
            return response()->json([
                'status'    => 'Delete Failed', 
                'message'   => 'user tidak ditemukan dengan id ' . $id 
            ]);
        }

        $delete = $user->delete();
        return response()->json([
            'status'    => 'Success Delete User', 
            'user'      => $delete
        ]);

    }
}

