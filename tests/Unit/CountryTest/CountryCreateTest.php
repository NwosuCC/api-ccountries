<?php

namespace Tests\Unit\CountryTest;

use App\Country;
use Tests\ApiTestCase;


/**
 * @group Country
 */
class CountryCreateTest extends ApiTestCase
{

  protected $model_name = Country::class;
  protected $model_table = 'countries';


  protected function get_countries_index() {
    $response = $this->get( $this->prefix('countries') );

    $response->assertSuccessful();

    return $response;
  }


  /*
   * @test
   * DB : Store a Country model instance in the database
   *
   * @param int $count
   * @return \Illuminate\Database\Eloquent\Collection
   */
  protected function _assert_DB_stores_country($count = 1, $options = []) {
    return $this->_assert_DB_stores_entry($count, ['title'], $options);
  }


  /**
   * @test
   */
  public function GetCountriesIndexTest() {
    $this->signIn();

    // Assert DB stores country
    $count = 2;

    $options = [
      'attributes' => ['user_id' => $this->user->id]
    ];

    $countries = $this->_assert_DB_stores_country($count, $options);
    dd($countries);

    // Fetch created countries
    /*$response = $this->get_countries_index();

    $fetched_countries = $response->json();
    $this->assertCount($count, $fetched_countries);

    // Check countries structure
    $latest_country = $fetched_countries->sortBy('created_at', 0, 'desc')->first();

    $response->assertJsonStructure([
      'id', 'name', 'continent', 'created_at'
    ], $latest_country);*/
  }


}
