<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;
use Auth;

class PostController extends Controller
{
    public function getAll(){

        $posts = Post::all();
        return response()->json([
            'posts' => $posts
        ]);
    }

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
