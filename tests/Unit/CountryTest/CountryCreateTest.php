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
//    $this->actingAs($user, 'api');

    $response = $this->get('/countries');

    $response->assertSuccessful();

    return $response;
  }


  /**
   * @test
   *
   * @param int $count
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function _assert_DB_stores_country($count = 1) {
    return $this->_assert_DB_stores_entry($count, ['title']);
  }


  /** @test */
  public function country_index_fetches_countries() {
    // Assert DB stores country
    $count = 2;
    $this->_assert_DB_stores_country($count);

    // Fetch created countries
    $response = $this->get_countries_index();

    $fetched_countries = $response->json();
    $this->assertCount($count, $fetched_countries);

    // Check countries structure
    $latest_country = $fetched_countries->sortBy('published_at', 0, 'desc')->first();

    $response->assertJsonStructure([
      'id', 'name', 'continent', 'created_at'
    ], $latest_country);
  }


}
