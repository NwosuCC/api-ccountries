<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;

class ExampleTest extends TestCase
{
    use InteractsWithAuthentication;


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
      $this->assertTrue(true);
    }


}
