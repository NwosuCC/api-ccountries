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

  private $options, $default_options = [
    'assert_columns' => ['title']
  ];


  /**
   * @test
   * DB : Store a Country model instance in the database
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  protected function _assert_DB_stores_country() {
    $this->options = array_merge($this->default_options, $this->options);
    return $this->_assert_DB_stores_entry($this->options);
  }


  /**
   * Create countries with the Authenticated user id
   * See ApiTestCase::withAuthId() for more
   *
   * @param int $count
   * @return \Illuminate\Database\Eloquent\Collection
   */
  protected function createdByMe(int $count = 1) {
    $this->options = [
      'count' => $count, 'state' => 'created_by_me'
    ];

    $this->withAuthId();

    return $this->_assert_DB_stores_country();
  }


  /**
   * Create countries with other user id to be created in CountryFactory
   * @param int $count
   * @return \Illuminate\Database\Eloquent\Collection
   */
  protected function createdByOtherUser(int $count = 1) {
    $this->options = [
      'count' => $count, 'state' => 'created_by_other_users'
    ];

    return $this->_assert_DB_stores_country();
  }


  /*
   * @test
   * GET /api/v1/countries : Get All Countries
   */
  public function GetAllCountriesIndexTest() {
    // Authenticate to make requests
    $this->signIn();

    $countries_by_me = $this->createdByMe(2);
    $one_country_by_me = $countries_by_me->first();

    $countries_by_others = $this->createdByOtherUser();
    $one_country_by_others = $countries_by_others->first();


    // Fetch created countries
    $response = $this->get( $this->prefix('countries') );

    $response->assertSuccessful();

    // Array Collection : pick the first
    $response_country = $response->json()[0];

    $response->assertJsonStructure([
      'id', 'name', 'continent', 'created_at'
    ], $response_country);

    $response->assertJsonFragment([
      "id" => $one_country_by_me->id,
      "name" => $one_country_by_me->name,
      "continent" => $one_country_by_me->continent
    ]);

    $response->assertJsonFragment([
      "id" => $one_country_by_others->id,
      "name" => $one_country_by_others->name,
      "continent" => $one_country_by_others->continent
    ]);
  }


  /*
   * @test
   * POST /api/v1/countries : Create Country
   */
  public function CreateCountryTest()
  {
    // Create countries, persisted
    $countries_attributes = $this->factory()->raw();
    $new_country_attributes = array_shift($countries_attributes);

    $url = $this->prefix('countries');
    $response = $this->signIn()->postJson( $url, $new_country_attributes );

    $response->assertStatus(200);

    $new_country = $response->json();

    $response->assertJsonStructure([
      'id', 'name', 'continent', 'created_at'
    ], $response->json());

    $response->assertJsonFragment([
      "name" => $new_country_attributes['name'],
      "continent" => $new_country_attributes['continent'],
      "created_at" => $new_country['created_at'],
      "id" => $new_country['id'],
    ]);
  }


  /*
   * @test
   * GET /api/v1/countries/:id : Get One Country
   */
  public function GetOneCountryTest()
  {
    // Get existing country from database
    $country = $this->model()->all()->first();

    // Fetch the country via API call
    $url = $this->prefix('countries/' . $country->id);
    $response = $this->signIn()->getJson( $url );

    $response->assertSuccessful();

    $response->assertJsonStructure([
      'id', 'name', 'continent', 'created_at'
    ], $response->json());

    $response->assertJsonFragment([
      "id" => $country->id,
      "name" => $country->name,
      "continent" => $country->continent,
    ]);
  }


  /**
   * @test
   * PUT /api/v1/countries/:id : Update Country
   */
  public function UpdateCountryTest()
  {
    // Authenticate to make requests
    $this->signIn();

    $countries_by_others = $this->createdByOtherUser();
    $one_country_by_others = $countries_by_others->first();

    $countries_by_me = $this->createdByMe(2);
    $one_country_by_me = $countries_by_me->first();

    // Get new attributes to update the models with
    $countries_attributes = $this->factory()->raw();
    $country_changes = array_shift($countries_attributes);


    /*
     * 1.) Edit country created by others : $one_country_by_others
     *     Changes : name and continent
     *     Expects this Update to FAIL with 403 ('Unauthorised / Forbidden')
     */
    $url_other = $this->prefix('countries/' . $one_country_by_others->id);
    $response = $this->putJson( $url_other, $country_changes );

    $response->assertForbidden();  // Code 403


    /*
     * 2.) Edit country created by me : $one_country_by_others
     *     Changes : name and continent
     *     Expects this Update to PASS with 200 ('Successful')
     */
    $url_me = $this->prefix('countries/' . $one_country_by_me->id);
    $response = $this->putJson( $url_me, $country_changes );

    $response->assertSuccessful();

    $response->assertJsonStructure([
      'id', 'name', 'continent', 'created_at'
    ], $response->json());
    dd($one_country_by_me->getAttributes(), $country_changes, $response->json());

    $response->assertJsonFragment([
      "id" => $one_country_by_me->id,
      "name" => $country_changes['name'],
      "continent" => $country_changes['continent'],
    ]);
  }


  /*
    $this->assertSoftDeleted([
      "id" => $country->id,
      "name" => $country->name,
      "continent" => $country->continent,
    ]);
   */


}
