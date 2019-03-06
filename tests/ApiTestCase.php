<?php

namespace Tests;

use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;


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


  /**
   * Return an instance of the model specified in the child class
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
   * @return mixed
   */
  protected function factory(int $count = 1) {
    return factory($this->model_name)->times($count);
  }


  protected function _assert_DB_has_entries(Collection $entries, array $columns) {
    $entries->each(function ($entry) use ($entries, $columns) {
      $entry_properties = array_intersect_key(
        $entry->toArray(), array_fill_keys($columns, '')
      );

      $this->assertDatabaseHas($this->model_table, $entry_properties);
    });
  }

  protected function _assert_DB_stores_entry($count = 1, array $columns = []) {
    $entries = $this->factory($count)->create();

    $this->assertCount($count, $entries);

    if($columns){
      $this->_assert_DB_has_entries($entries, $columns);
    }

    return $entries;
  }

}
