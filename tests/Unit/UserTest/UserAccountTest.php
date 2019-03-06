<?php

namespace Tests\Unit\UserTest;

use App\User;
use Tests\ApiTestCase;


/**
 * @group User
 */
class UserAccountTest extends ApiTestCase
{

  protected $model_name = User::class;


  /**
   * @test
   *
   * @param int $count
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function _assert_DB_stores_user($count = 1) {
    return $this->_assert_DB_stores_entry($count, ['id', 'username', 'email']);
  }


  public function testRegisterUserTest()
  {
    // POST /register : sign-up user

    /*$data = [
      "first_name" => "Jane",
      "last_name" => "Minnowe",
      "date_of_birth" => "1997-12-01 10:13:47",
      "email" => "jane.minnowe@aol.com",
      "username" => "JaneMi",
      "password" => "secret"
    ];*/

    // Create user
    $count = 1;
    $new_user = $this->factory($count)->create();

    $response = $this->post('/api/v1/register', $new_user);

    $response->assertStatus(200);

    // Check user structure
    $response->assertJsonStructure([
      'id', 'first_name', 'last_name', 'date_of_birth', 'email', 'username', 'created_at'
    ], $response->json());

    // Check returned json contains posted user information
    $response->assertJson([
      "email" => $new_user['email'],
      "username" => $new_user['username']
    ]);
  }

  /*public function testLoginUserTest()
  {
    // POST /login : authenticate user
    $credentials = [
      "email" => "jane.minnowe@aol.com",
      'password' => 'secret',
    ];

    $response = $this->post('/login', $credentials);

    $response->assertStatus(200);

    $response->assertJsonStructure([
      'id', 'email', 'username', 'token'
    ], $response->json());

    $response->assertJson([
      "email" => "jane.minnowe@aol.com",
      "username" => "JaneMi"
    ]);
  }*/

}