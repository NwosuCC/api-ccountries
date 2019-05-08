<?php

namespace Tests\Unit\CountryTest;

use App\Continent;
use App\Country;
use Tests\ApiTestCase;


/**
 * @group Country
 */
class CountryCrudTest extends ApiTestCase
{

  protected $model_name = Country::class;

  protected $model_table = 'countries';

  private $options = [];

  private $default_options = [
    'assert_columns' => ['title']
  ];


  /**
   * @test
   * DB : Store a Country model instance in the database
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function _assert_DB_stores_country()
  {
    if(empty($this->options['states']) || count($this->options['states']) < 2){
      $this->options['states'][] = 'for_db_store';
      $this->options['states'][] = 'created_by_other_users';
    }

    $this->options = array_merge($this->default_options, $this->options);

    foreach ($this->options as $key => $values){
      $this->options[ $key ] = is_array($values) ? array_unique($values) : $values;
    }

    return $this->_assert_DB_stores_entry($this->options);
  }


  /**
   * Create countries with the Authenticated user id
   * See ApiTestCase::withAuthId() for more
   *
   * @param int $count
   * @param bool $db_store [Optional]
   * @return \Illuminate\Database\Eloquent\Collection
   */
  protected function createdByAuthUser(int $count = 1, $db_store = true) {
    $this->options = [
      'count' => $count, 'states' => ['created_by_me']
    ];

    $this->options['states'][] = $db_store ? 'for_db_store' : 'for_http_api';

    $this->withAuthId();

    return $this->_assert_DB_stores_country();
  }


  /**
   * Create countries with other user id to be created in CountryFactory
   * @param int $count
   * @param bool $db_store [Optional]
   * @return \Illuminate\Database\Eloquent\Collection
   */
  protected function createdByOtherUser(int $count = 1, $db_store = true) {
    $this->options = [
      'count' => $count, 'states' => ['created_by_other_users']
    ];

    $this->options['states'][] = $db_store ? 'for_db_store' : 'for_http_api';

    return $this->_assert_DB_stores_country();
  }


  /**
   * @test
   * GET /api/v1/countries : Get All Countries
   */
  public function GetAllCountriesIndexTest() {
    // Authenticate to make requests
    $this->signIn();

    $countries_by_auth = $this->createdByAuthUser(2);
    $one_country_by_auth = $countries_by_auth->first();

    $countries_by_others = $this->createdByOtherUser(2);
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
      "id" => $one_country_by_auth->id,
      "name" => $one_country_by_auth->name,
      "continent" => $one_country_by_auth->continent
    ]);

    $response->assertJsonFragment([
      "id" => $one_country_by_others->id,
      "name" => $one_country_by_others->name,
      "continent" => $one_country_by_others->continent
    ]);
  }


  /**
   * @test
   * POST /api/v1/countries : Create Country
   */
  public function CreateCountryTest()
  {
    // Create countries, persisted
    $countries_attributes = $this->factory()->state('for_http_api')->raw();
    $new_country_attributes = array_shift($countries_attributes);

    $url = $this->prefix('countries');
    $response = $this->signIn()->postJson( $url, $new_country_attributes );

    $response->assertStatus(200);

    $new_country = $response->json();

    $response->assertJsonStructure([
      'id', 'name', 'continent', 'created_at'
    ], $response->json());


    // Get the to-update $new_continent from its name
    // Then, assert that the updated country 'continent_id' equals the 'id' of the $new_continent
    $new_continent = Continent::fromName( $new_country_attributes['continent'] );

    $response->assertJsonFragment([
      "id" => $new_country['id'],
      "name" => $new_country_attributes['name'],
      "created_at" => $new_country['created_at'],
      "continent" => $response->json()['continent']
    ]);
  }


  /**
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
      "continent" => $country->continent
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

    $countries_by_others = $this->createdByOtherUser(2);
    $one_country_by_others = $countries_by_others->first();

    $countries_by_auth = $this->createdByAuthUser(2);
    $one_country_by_auth = $countries_by_auth->first();

    // Get new attributes to update the models with
    $countries_attributes = $this->factory()->state('for_http_api')->raw();
    $country_changes = array_shift($countries_attributes);


    /*
     * 1.) Edit country created by others : $one_country_by_others
     *     Changes : ['name', 'continent']
     *     Expects this Update to FAIL with 403 ('Unauthorised / Forbidden')
     */
    $url_other = $this->prefix('countries/' . $one_country_by_others->id);
    $response = $this->putJson( $url_other, $country_changes );

    // Code 403 : Auth user CANNOT update countries created by other users
    $response->assertForbidden();


    /*
     * 2.) Edit country created by me : $one_country_by_others
     *     Changes : ['name', 'continent']
     *     Expects this Update to PASS with 200 ('Successful')
     */
    $url_me = $this->prefix('countries/' . $one_country_by_auth->id);
    $response = $this->putJson( $url_me, $country_changes );

    // Code 200 : Auth user CAN update countries created by him/her
    $response->assertSuccessful();


    $response->assertJsonStructure([
      'id', 'name', 'continent', 'created_at'
    ], $response->json());


    // Get the to-update $new_continent from its name
    // Then, assert that the updated country 'continent_id' equals the 'id' of the $new_continent
//    $new_continent = Continent::fromName( $country_changes['continent'] );

    $response->assertJsonFragment([
      "name" => $country_changes['name'],
      "id" => $one_country_by_auth->id,
      "continent" => $response->json()['continent']
    ]);
  }


  /**
   * @test
   * DELETE /api/v1/countries/:id : Delete Country
   */
  public function DeleteCountryTest()
  {
    // Authenticate to make requests
    $this->signIn();

    $countries_by_others = $this->createdByOtherUser(1);
    $one_country_by_others = $countries_by_others->first();

    $countries_by_auth = $this->createdByAuthUser(1);
    $one_country_by_auth = $countries_by_auth->first();


    /*
     * 1.) Delete country created by others : $one_country_by_others
     *     Expects this Delete to FAIL with 403 ('Unauthorised / Forbidden')
     */
    $url_other = $this->prefix('countries/' . $one_country_by_others->id);
    $response = $this->deleteJson( $url_other );

    // Code 403 : Auth user CANNOT update countries created by other users
    $response->assertForbidden();


    /*
     * 2.) Delete country created by me : $one_country_by_others
     *     Expects this Delete to PASS with 200 ('Successful')
     */
    $url_me = $this->prefix('countries/' . $one_country_by_auth->id);
    $response = $this->deleteJson( $url_me );

    // Code 200 : Auth user CAN update countries created by him/her
    $response->assertSuccessful();


    $this->assertSoftDeleted($this->model_table, [
      "id" => $one_country_by_auth->id,
      "name" => $one_country_by_auth->name,
    ]);
  }


}
