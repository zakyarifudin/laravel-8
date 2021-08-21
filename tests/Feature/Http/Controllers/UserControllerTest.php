<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use DB;

class UserControllerTest extends TestCase
{
    use WithFaker;

    public function testGetAllUsersSuccess()
    {
        $this->getJson('api/users')
            ->assertOk()
            ->assertJsonStructure([
                'users'
            ]);
    }

    public function testGetOneUserSuccess()
    {
        $user = DB::table('users')->select('id_user')->first();

        if($user !== null){
            $this->getJson('api/users/' . $user->id_user)
                ->assertOk();
        }
        else{
            $this->getJson('api/users/no-data')
                ->assertNotFound();
        }       
    }

    public function testGetOneUserFail()
    {
        $this->getJson('api/users/dfwegfhwo')
            ->assertNotFound();
    }

    public function testAddUserSuccess()
    {
        $addUser = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password'
        ];


        $this->postJson('api/users', $addUser)
            ->assertStatus(201)
            ->assertJson([
                'status'    => 'Success Add User'
            ]);
            
    }

    public function testAddUserFail()
    {
        $addUser = [];

        $this->postJson('api/users', $addUser)
            ->assertStatus(422);
    }

    public function testUpdateUserSuccess()
    {
        $user = DB::table('users')->select('id_user')->first();

        if($user !== null){
            $updateUser = [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => 'password'
            ];

            $this->putJson('api/users/' . $user->id_user, $updateUser)
                ->assertOk()
                ->assertJson([
                    'status'    => 'Success Update User'
                ]);
                
        }
        else{
            $this->putJson('api/users/no-data')
                ->assertNotFound()
                ->assertJson([
                    'status'    => 'Update Failed', 
                    'message'   => 'user tidak ditemukan dengan id ' . $id 
                ]);
        }       
    }

    public function testUpdateUserFail()
    {
        $this->putJson('api/users/no-data')
        ->assertNotFound()
        ->assertJson([
            'status'    => 'Update Failed', 
            'message'   => 'user tidak ditemukan dengan id no-data' 
        ]);
    }

    public function testDeleteUserSuccess()
    {
        $user = DB::table('users as u')
            ->leftJoin('posts as p', 'p.id_user', '=', 'u.id_user')
            ->select('u.id_user', 'u.name')
            ->whereNull('p.id_user')
            ->first();

        if($user !== null){
            $this->deleteJson('api/users/' . $user->id_user)
                ->assertOk()
                ->assertJson([
                    'status'    => 'Success Delete User'
                ]);  
        }
        else{
            $this->deleteJson('api/users/dfwegfhwo')
                ->assertNotFound() 
                ->assertJson([
                    'status'    => 'Delete Failed', 
                    'message'   => 'user tidak ditemukan dengan id dfwegfhwo' 
                ]);
        }       
    }

    public function testDeleteUserFail()
    {
        $this->deleteJson('api/users/dfwegfhwo')
            ->assertNotFound()
            ->assertJson([
                'status'    => 'Delete Failed', 
                'message'   => 'user tidak ditemukan dengan id dfwegfhwo' 
            ]);
    }
}
