<?php

namespace Tests\Unit\AuditTest;

use App\Audit;
use App\Country;
use Tests\ApiTestCase;
use Tests\Unit\CountryTest\CountryCrudTest;


/**
 * @group Audit
 */
class AuditTrailTest extends ApiTestCase
{

  protected $model_name = Audit::class;
  protected $model_table = 'audits';

  private $options = [];
  private $default_options = [];


  /**
   * @test
   * GET /api/v1/activities : Get All Audits
   */
  public function GetAllAuditsIndexTest()
  {
    /*
     * 1.) Authenticate as normal user (non-Admin)
     *     Expects this Request to FAIL with 403 ('Unauthorised / Forbidden')
     */
    $this->signIn();

    // Fetch audits
    $response = $this->get( $this->prefix('activities'));

    // Code 403 : Non-Admin Auth user CANNOT view Audit Trail
    $response->assertForbidden();


    /*
     * 1.) Authenticate as Admin
     *     Expects this Request to PASS with 200 ('Successful')
     */
    $this->asAdmin()->signIn();

    // Fetch audits
    $response = $this->get( $this->prefix('activities'));

    $response->assertSuccessful();

    // Array Collection : pick the first
    $audits = $response->json();
    $first_audit = reset($audits);

    $response->assertJsonStructure([
      'id', 'user_id', 'event', 'old_values', 'new_values'
    ], $first_audit);
  }


  /**
   * @test
   * GET /api/v1/activities : Get All Audits
   */
  public function GetOneAuditAfterCreateCountryTest()
  {
    // Create countries, persisted
    $new_country_attributes = factory(Country::class)->state('for_http_api')->raw();

    $url = $this->prefix('countries');
    $response = $this->signIn()->postJson( $url, $new_country_attributes );

    $response->assertStatus(200);

    $new_country = $response->json();

    $response->assertJsonStructure([
      'id', 'name', 'continent', 'created_at'
    ], $new_country);


    // Authenticate as Admin
    $this->asAdmin()->signIn();

    // Fetch created countries
    $response = $this->get($this->prefix('activities'));

    $response->assertSuccessful();

    // Array Collection : pick the last
    // Normally, the last Audit entry should contain the latest 'create' event performed above in this function
    $audits = $response->json();
    $last_audit = end($audits);

    $response->assertJsonStructure([
      'id', 'user_id', 'event', 'old_values', 'new_values'
    ], $last_audit);

    $this->assertTrue( $last_audit['event'] === "created");
    $this->assertTrue( $last_audit['old_values'] === "[]");


    // Compare the audit $new_values with the created $new_country values
    $new_values = json_decode($last_audit['new_values'], true);

    $response->assertJsonStructure([
      'id', 'name', 'continent_id'
    ], $new_values);

    $this->assertTrue( $new_values['id'] === $new_country['id'] );
    $this->assertTrue( $new_values['name'] === $new_country['name'] );
    $this->assertTrue( $new_values['continent_id'] === $new_country['continent']['id'] );
  }

}