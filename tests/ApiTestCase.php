<?php

namespace Tests;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;


class ApiTestCase extends TestCase
{

  /**
   * Resets the entire migration after the tests
   */
  use RefreshDatabase;

  /**
   * [Preferred] Wraps queries in transactions and rolls back after the tests
   * @param array $connectionsToTransact  A list of connections for multiple databases
   */
//  use DatabaseTransactions;
  protected $connectionsToTransact = [];

  protected $model;
  protected $model_name = '';
  protected $model_table = '';

  private $attributes = [];

  protected $user;


  protected function setUp(){
    parent::setUp();

    \Artisan::call('migrate', ['-vvv' => true, '--seed' => true]);
    \Artisan::call('passport:install', ['-vvv' => true]);
  }

  /**
   * Return an instance of the model specified in the child class
   * @return \App\Model
   */
  protected function model() {
    if(!$this->model && $this->model_name) {
      $this->model = app($this->model_name);
    }
    return $this->model;
  }


  /**
   * Add the API prefix and return the new URL
   * @param string $url
   * @return string
   */
  protected function prefix(string $url) {
    return "/api/v1/" . $url;
  }


  /**
   * Tests the factory() function with the default value provided as the param
   * @param int   $count
   * @testWith    [1]
   * NOTE: Read more about this at https://phpunit.de
   *
   * @param string $model_name [optional] If NOT supplied, the $this->>model_name specified in the sub_class is used
   *
   * @return mixed
   */
  protected function factory(int $count = 1, $model_name = '') {
    return factory( $model_name ?: $this->model_name )->times($count);
  }


  /**
   * Sets an 'admin' email for authentication as Admin
   * See project documentation on GitHub for more on admin emails
   *
   * @return $this
   */
  protected function asAdmin() {
    $domain = str_random(8);

    $this->attributes = ['email' => "admin@{$domain}.com"];

    return $this;
  }


  /**
   * Set an API authenticated User
   * If $this->asAdmin() was called just before this, the User is authenticated as ADMIN
   *
   * @param $user [optional]
   * @return $this
   */
  protected function signIn($user = null) {
    if( ! $user){
      $attributes = array_key_exists('email', $this->attributes) ? $this->attributes : [];
      $this->attributes = [];

      $user = $this->factory(1,'App\User')->create($attributes)->first();
    }

    Passport::actingAs($user);

    $this->user = $user;

    return $this;
  }


  /**
   * Captures the user_id of an authenticated User, e.g for creation of other models
   * @return $this
   */
  protected function withAuthId() {
    if($this->user){
      $this->attributes = ['user_id' => $this->user->id];
    }

    return $this;
  }


  protected function _assert_DB_has_entries(Collection $entries, array $columns) {
    $entries->each(function ($entry) use ($entries, $columns) {
      $entry_properties = array_intersect_key(
        $entry->toArray(), array_fill_keys($columns, '')
      );

      $this->assertDatabaseHas($this->model_table, $entry_properties);
    });
  }


  /**
   * Inserts model entries into the database and makes default/specified assertions
   * Used to test that database operation is OK before proceeding with Http calls
   *
   * @param array $options
   *    $options['count'] => Specifies the number of model instances to create
   *    $options['state'] => Specifies the model factory state to apply
   *    $options['attributes'] => Specifies which model attributes to overwrite
   *    $options['assert_columns'] => Specifies the columns to test during DB assertions
   *
   *@return \Illuminate\Database\Eloquent\Collection
   */
  protected function _assert_DB_stores_entry(array $options = []) {
    $count = $options['count'] ?? 1;

    $attributes = array_merge($this->attributes, ($options['attributes'] ?? []));
    $this->attributes = [];

    $states = (array) ($options['states'] ?? []);

    $assert_columns = $options['assert_columns'] ?? [];

    $entries = $states
        ? $this->factory($count)->states($states)->create($attributes)
        : $this->factory($count)->create($attributes);

    $this->assertCount($count, $entries);

    if($assert_columns){
      $this->_assert_DB_has_entries($entries, $assert_columns);
    }

    return $entries;
  }

}
