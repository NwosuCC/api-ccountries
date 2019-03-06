<?php

namespace Tests\Unit\UserTest;

use Tests\TestCase;


/**
 * @group Api
 * @group User
 * @group Country
 * @group Audit
 */
class ApiTest extends TestCase
{
  public function testWebRedirectTest()
  {
    $response = $this->get('/');
    $response->assertRedirect(url('api'));
  }

  public function testApiRedirectTest()
  {
    $response = $this->get('/api');
    $response->assertRedirect(url('api/v1'));
  }

  public function testApiCountriesRedirectTest()
  {
    $response = $this->get('/api/v1');
    $response->assertRedirect(url('api/v1/countries'));
  }

}