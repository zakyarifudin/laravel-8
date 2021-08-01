<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Auth;

class AuthController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/user-auth",
     *      operationId="userAuth",
     *      tags={"Auth"},
     *      summary="Get user auth info",
     *      description="Return user auth info",
     *      @OA\Response(response=200, description="successfully get user info", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent()),
     *      security={{"passport-laravel": {}}},
     * )
     *
     * 
    */

    public function userAuth(){

        $user = DB::table('users')
            ->select('id_user', 'name', 'email')
            ->where('id_user', Auth::user()->id_user)
            ->first();

        return response()->json($user, 200);
    }


    /**
     * @OA\Get(
     *      path="/api/user-logout",
     *      operationId="userLogout",
     *      tags={"Auth"},
     *      summary="Logout user auth",
     *      description="Return logout user auth",
     *      @OA\Response(response=200, description="successfully logout", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent()),
     *      security={{"passport-laravel": {}}},
     * )
     *
     * 
    */

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
