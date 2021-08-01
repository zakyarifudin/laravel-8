<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;
use Auth;

class PostController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/posts",
     *      operationId="getAllPost",
     *      tags={"Posts"},
     *      summary="Get List of Posts",
     *      description="Return list of posts",
     *      @OA\Response(response=200, description="Successfully get list of posts", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent()),
     *      security={{"passport-laravel": {}}},
     * )
     * 
    */
    public function getAll(){

        $posts = Post::all();
        return response()->json([
            'posts' => $posts
        ]);
    }

    /**
     *  @OA\Get(
     *      path="/api/posts/{id_post}",
     *      operationId="getOnePost",
     *      tags={"Posts"},
     *      summary="Get Post Detail",
     *      description="Return post detail",
     *      @OA\Parameter(
     *          name="id_post",
     *          description="Post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successfully get post detail", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent()),
     *      @OA\Response(response=404, description="User Not Found", @OA\JsonContent()),
     *      security={{"passport-laravel": {}}},
     * )
     * 
    */
    public function getOne(Request $request, $id){

        $post = Post::find($id);
        if($post == null){
            return response([
                'message'   => 'post tidak ditemukan dengan id ' . $id 
            ], 404);
        }
        return response()->json([
            'post' => $post
        ], 200);
    }

    /**
     * @OA\Post(
     *      path="/api/posts",
     *      operationId="addPost",
     *      tags={"Posts"},
     *      summary="Add Post",
     *      description="Return Add Post",
     *      @OA\RequestBody(
     *         description="Input data format",
     *         @OA\JsonContent(
     *              type="object",
     *              required={"title","body"},
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="body", type="string")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successfully Add", @OA\JsonContent()),
     *      @OA\Response(response=201, description="Successfully Add", @OA\JsonContent()),
     *      @OA\Response(response=422, description="Error: Unprocessable Entity", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     *      @OA\Response(response=404, description="Not Found", @OA\JsonContent()),
     *      security={{"passport-laravel": {}}}
     * )
     */
    public function add(Request $request){

        $request->validate([
            'title'      => 'required',
            'body'       => 'required'
        ]);

        $post = [
            'id_post'   => Str::uuid(),
            'id_user'   => Auth::user()->id_user,
            'title'     => $request->input('title'),
            'body'      => $request->input('body'),
        ];

        $add_post = Post::create($post);
        return response()->json([
            'status'    => 'Success Add Post', 
            'post'      => $add_post
        ], 201);
    }

    /**
     * @OA\Put(
     *      path="/api/posts/{id_post}",
     *      operationId="updatePost",
     *      tags={"Posts"},
     *      summary="Update Post",
     *      description="Return Update Post",
     *      @OA\Parameter(
     *          name="id_post",
     *          description="Post id",
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
     *              required={"title","body"},
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="body", type="string")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successfully Update", @OA\JsonContent()),
     *      @OA\Response(response=422, description="Error: Unprocessable Entity", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     *      @OA\Response(response=404, description="Not Found", @OA\JsonContent()),
     *      security={{"passport-laravel": {}}}
     * )
     */
    public function update(Request $request, $id){

        $post = Post::find($id);
        if($post==null){
            return response()->json([
                'status'    => 'Update Failed', 
                'message'   => 'post tidak ditemukan dengan id ' . $id 
            ], 404);
        }

        $request->validate([
            'title'      => 'required',
            'body'       => 'required'
        ]);

        $post_data = [
            'title'     => $request->input('title'),
            'body'      => $request->input('body')
        ];
        

        $update_post = $post->update($post_data);
        return response()->json([
            'status'    => 'Success Update Post', 
            'post'      => $post
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/api/posts/{id_post}",
     *      operationId="deletePost",
     *      tags={"Posts"},
     *      summary="Delete Post",
     *      description="Return Delete Post",
     *      @OA\Parameter(
     *          name="id_post",
     *          description="Post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successfully Delete", @OA\JsonContent()),
     *      @OA\Response(response=422, description="Error: Unprocessable Entity", @OA\JsonContent()),
     *      @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     *      @OA\Response(response=404, description="Not Found", @OA\JsonContent()),
     *      security={{"passport-laravel": {}}}
     * )
     */
    public function delete($id){

        $post = Post::find($id);
        if($post==null){
            return response()->json([
                'status'    => 'Delete Failed', 
                'message'   => 'post tidak ditemukan dengan id ' . $id 
            ], 404);
        }

        $delete = $post->delete();
        return response()->json([
            'status'    => 'Success Delete Post', 
            'post'      => $delete
        ]);

    }
}
