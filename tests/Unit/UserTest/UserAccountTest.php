<?php

namespace Tests\Unit\UserTest;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\ApiTestCase;


/**
 * @group User
 *
 * Requires ApiTestCase::setUp() to install Laravel Passport
 */
class UserAccountTest extends ApiTestCase
{

  protected $model_name = User::class;
  protected $model_table = 'users';


  /**
   * @test
   * DB : Store a User model instance in the database
   *
   * @param int $count
   * @return \Illuminate\Database\Eloquent\Collection
   */
  protected function _assert_DB_stores_user($count = 1) {
    return $this->_assert_DB_stores_entry($count, ['id', 'username', 'email']);
  }


  /**
   * @test
   * POST /api/v1/register : Create user
   */
  public function RegisterUserTest()
  {
    // Simply get user attributes, as Array, directly from the factory
    $count = 1;
    $attributes = $this->factory($count)->raw(['password' => 'secret']);
    $new_user = array_shift($attributes);

    $response = $this->post( $this->prefix('register'), $new_user );

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


  /**
   * @test
   * POST /api/v1/login : Authenticate user
   */
  public function LoginUserTest()
  {
    // Create user, persisted
    $count = 1;
    $password = 'secret';

    $new_user = $this->factory($count)->state('raw_pass')->create()->first();

    $credentials = [
      "email" => $new_user->email,
      "password" => $password
    ];

    $response = $this->post($this->prefix('login'), $credentials);

    $response->assertStatus(200);

    $response->assertJsonStructure([
      'id', 'email', 'username', 'token'
    ], $response->json());

    $response->assertJson([
      "id" => $new_user->id,
      "email" => $new_user->email,
      "username" => $new_user->username,
    ]);
  }

}