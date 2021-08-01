<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Hash;


class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/users",
     *      operationId="getAllUser",
     *      tags={"Users"},
     *      summary="Get List of Users",
     *      description="Returns list of users",
     *      @OA\Response(response=200, description="Successfully get user list"),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     *
     * 
    */

    public function getAll(){

        $users = User::all();
        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/users/{id_user}",
     *      operationId="getOneUser",
     *      tags={"Users"},
     *      summary="Get User Detail",
     *      description="Returns user detail",
     *      @OA\Parameter(
     *          name="id_user",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successfully get user"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */

    public function getOne(Request $request, $id){

        $user = User::find($id);

        if($user==null){
            return response()->json([
                'status'    => 'Not Found', 
                'message'   => 'user tidak ditemukan dengan id ' . $id 
            ], 404);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/users",
     *      operationId="addUser",
     *      tags={"Users"},
     *      summary="Add User",
     *      description="Return Add User",
     *      @OA\RequestBody(
     *         description="Input data format",
     *         @OA\JsonContent(
     *              type="object",
     *              required={"email","name", "password"},
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="password", type="string", format="password")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successfully Add"),
     *      @OA\Response(response=201, description="Successfully Add"),
     *      @OA\Response(response=422, description="Error: Unprocessable Entity", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Not Found")
     * )
     */

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
        ],201);
    }

    /**
     * @OA\Put(
     *      path="/api/users/{id_user}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update User",
     *      description="Return Update User",
     *      @OA\Parameter(
     *          name="id_user",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *         description="Input data format",
     *         @OA\JsonContent(
     *              type="object",
     *              required={"email","name", "password"},
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="password", type="string", format="password")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successfully Update"),
     *      @OA\Response(response=422, description="Error: Unprocessable Entity", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="User Not Found")
     * )
     */

    public function update(Request $request, $id){

        $user = User::find($id);
        if($user==null){
            return response()->json([
                'status'    => 'Update Failed', 
                'message'   => 'user tidak ditemukan dengan id ' . $id 
            ], 404);
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

    /**
     * @OA\Delete(
     *      path="/api/users/{id_user}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="Delete User",
     *      description="Return delete user",
     *      @OA\Parameter(
     *          name="id_user",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successfully delete user"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="User Not Found")
     * )
     */

    public function delete($id){

        $user = User::find($id);
        if($user==null){
            return response()->json([
                'status'    => 'Delete Failed', 
                'message'   => 'user tidak ditemukan dengan id ' . $id 
            ], 404);
        }

        $delete = $user->delete();
        return response()->json([
            'status'    => 'Success Delete User', 
            'user'      => $delete
        ]);

    }
}

