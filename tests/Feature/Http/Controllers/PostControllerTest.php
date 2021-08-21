<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Feature\Http\Controllers\AuthControllerTest;
use DB;

class PostControllerTest extends TestCase
{ 
    use WithFaker;

    // Semuanya test pakai auth login 

    public function testGetAllPostsSuccess()
    {
        AuthControllerTest::passportLogin();

        $this->getJson('api/posts')
            ->assertOk()
            ->assertJsonStructure([
                'posts'
            ]);
    }

    public function testGetOnePostSuccess()
    {
        AuthControllerTest::passportLogin();

        $post = DB::table('posts')->select('id_post')->first();
        
        if($post !== null){
            $this->getJson('api/posts/' . $post->id_post)
                ->assertOk();
        }
        else{
            $this->getJson('api/posts/no-data')
                ->assertNotFound();
        }       
    }

    public function testGetOnePostFail()
    {
        AuthControllerTest::passportLogin();

        $this->getJson('api/posts/dfwegfhwo')
            ->assertNotFound();
    }

    public function testAddPostSuccess()
    {
        AuthControllerTest::passportLogin();

        $addPost = [
            'title'     => $this->faker->sentence(3),
            'body'      => $this->faker->paragraph(3),
        ];

        $this->postJson('api/posts', $addPost)
            ->assertStatus(201)
            ->assertJson([
                'status'    => 'Success Add Post'
            ]);
            
    }

    public function testAddPostFail()
    {
        AuthControllerTest::passportLogin();

        $addPost = [];

        $this->postJson('api/posts', $addPost)
            ->assertStatus(422);
    }

    public function testUpdatePostSuccess()
    {
        AuthControllerTest::passportLogin();

        $post = DB::table('posts')->select('id_post')->first();

        if($post !== null){
            $updatePost = [
                'title'     => $this->faker->sentence(4),
                'body'      => $this->faker->paragraph(6),
            ];

            $this->putJson('api/posts/' . $post->id_post, $updatePost)
                ->assertOk()
                ->assertJson([
                    'status'    => 'Success Update Post'
                ]);
                
        }
        else{
            $this->putJson('api/posts/no-data')
                ->assertNotFound()
                ->assertJson([
                    'status'    => 'Update Failed', 
                    'message'   => 'post tidak ditemukan dengan id ' . $id 
                ]);
        }       
    }

    public function testUpdatePostFail()
    {
        AuthControllerTest::passportLogin();

        $this->putJson('api/posts/no-data')
        ->assertNotFound()
        ->assertJson([
            'status'    => 'Update Failed', 
            'message'   => 'post tidak ditemukan dengan id no-data' 
        ]);
    }

    public function testDeletePostSuccess()
    {
        AuthControllerTest::passportLogin();

        $post = DB::table('posts')
            ->orderBy('created_at')
            ->first();

        if($post !== null){
            $this->deleteJson('api/posts/' . $post->id_post)
                ->assertOk()
                ->assertJson([
                    'status'    => 'Success Delete Post'
                ]);  
        }
        else{
            $this->deleteJson('api/posts/dfwegfhwo')
                ->assertNotFound() 
                ->assertJson([
                    'status'    => 'Delete Failed', 
                    'message'   => 'post tidak ditemukan dengan id dfwegfhwo' 
                ]);
        }       
    }

    public function testDeletePostFail()
    {
        AuthControllerTest::passportLogin();

        $this->deleteJson('api/posts/dfwegfhwo')
            ->assertNotFound()
            ->assertJson([
                'status'    => 'Delete Failed', 
                'message'   => 'post tidak ditemukan dengan id dfwegfhwo' 
            ]);
    }
}
