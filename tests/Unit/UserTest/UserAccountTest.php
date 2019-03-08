<?php

namespace Tests\Unit\UserTest;

use App\User;
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
   * @param array $options
   * @return \Illuminate\Database\Eloquent\Collection
   */
  protected function _assert_DB_stores_user($options = []) {
    $options['assert_columns'] = ['id', 'username', 'email'];
    return $this->_assert_DB_stores_entry($options);
  }


  /**
   * @test
   * POST /api/v1/register : Create user
   */
  public function RegisterUserTest()
  {
    // Simply get user attributes, as Array, directly from the factory
    $users_attributes = $this->factory()->raw(['password' => 'secret']);
    $new_user_attributes = array_shift($users_attributes);

    $response = $this->post( $this->prefix('register'), $new_user_attributes );

    $response->assertStatus(200);

    // Check user structure
    $response->assertJsonStructure([
      'id', 'first_name', 'last_name', 'date_of_birth', 'email', 'username', 'created_at'
    ], $response->json());

    // Check returned json contains posted user information
    $response->assertJson([
      "email" => $new_user_attributes['email'],
      "username" => $new_user_attributes['username']
    ]);
  }


  /**
   * @test
   * POST /api/v1/login : Authenticate user
   */
  public function LoginUserTest()
  {
    // Create user, persisted. Apply state 'raw_pass' to UserFactory
    $password = 'secret';
    $options = ['state' => 'raw_pass'];
    $new_user = $this->_assert_DB_stores_user($options)->first();

    $credentials = [
      "email" => $new_user->email,
      "password" => $password
    ];

    $response = $this->post($this->prefix('login'), $credentials);

    $response->assertStatus(200);

    $response->assertJsonStructure([
      'id', 'email', 'username', 'token'
    ], $response->json());

    $response->assertJsonFragment([
      "id" => $new_user->id,
      "email" => $new_user->email,
      "username" => $new_user->username,
    ]);
  }

}