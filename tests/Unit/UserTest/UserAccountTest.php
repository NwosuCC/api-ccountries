<?php

namespace Tests\Unit\UserTest;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\ApiTestCase;


/**
 * @group User
 */
class UserAccountTest extends ApiTestCase
{

  protected $model_name = User::class;
  protected $model_table = 'users';


  /**
   * @test
   *
   * @param int $count
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function _assert_DB_stores_user($count = 1) {
    return $this->_assert_DB_stores_entry($count, ['id', 'username', 'email']);
  }


  /*public function testRegisterUserTest()
  {
    // Make user, not persisted
    $count = 1;
    $new_user = $this->factory($count)->make(['password' => 'secret'])->first();

    $new_user_post = $new_user->toArray();
    $new_user_post['password'] = 'secret';

    $response = $this->post( $this->prefix('register'), $new_user_post );

    $response->assertStatus(200);

    // Check user structure
    $response->assertJsonStructure([
      'id', 'first_name', 'last_name', 'date_of_birth', 'email', 'username', 'created_at'
    ], $response->json());

    // Check returned json contains posted user information
    $response->assertJson([
      "email" => $new_user->{'email'},
      "username" => $new_user->{'username'}
    ]);
  }*/

  // POST /login : authenticate user

  /**
   * @test
   * POST /login : Authenticate user
   */
  public function LoginUserTest()
  {
    // Create user, persisted
    $count = 1;
    $password = 'secret';

    $new_user = $this->factory($count)
                     ->state('raw_pass')
                     ->create()
                     ->first();

    $credentials = [
      "email" => $new_user->email,
      "password" => $password
    ];

    $response = $this->post($this->prefix('login'), $credentials);

    $response->assertStatus(200);

    $response->assertJsonStructure([
      'id', 'email', 'username', 'token'
    ], $response->json());

    $response->assertJson($credentials);
  }

}