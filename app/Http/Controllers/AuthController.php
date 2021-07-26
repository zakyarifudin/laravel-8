<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class AuthController extends Controller
{
    public function userAuth(){

        $user = DB::table('users')
            ->select('id_user', 'name', 'email')
            ->where('id_user', Auth::user()->id_user)
            ->first();

        return response()->json($user, 200);
    }
}
