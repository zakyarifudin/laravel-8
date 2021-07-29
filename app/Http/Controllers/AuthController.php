<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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

    public function userLogout(){

        $revoke = DB::table('oauth_access_tokens')
            ->where('user_id', Auth::user()->id_user)
            ->update([
                'revoked' => 1
            ]);

        return response()->json([
            'message' => 'Success Logout',
            'revoke'  => $revoke
        ], 200);
    }
}
