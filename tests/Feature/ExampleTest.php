<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

use App\User;

class ExampleTest extends TestCase
{
    use InteractsWithAuthentication;


    /**
     * A basic test example.
     *
     * @return void
     */
    /*public function testBasicTest()
    {
      $response = $this->get('/');
      $response->assertRedirect( url('api/v1') );
    }

    public function testCountriesTest()
    {
        // GET /countries : View all countries
        $response = $this->get('/countries');

        $response->assertSuccessful();
      
        $response->assertJson([
          "id" => 1, 
          "name" => "Nigeria", 
          "continent" => "Africa",
          "created_at" => "2019-03-05 06:42:42"
        ]);
    }

    public function testShowCountryTest()
    {
        // GET /countries/:id : View one country
        $response = $this->get('/countries/wrongId');
        $response->assertNotFound();

        $response = $this->get('/countries/2');
        $response->assertSuccessful();
        $response->assertViewIs('country.show');
        $response->assertViewHasAll(['country']);

        $this->assertEquals( $response->viewData('country')->id, 2 );
    }

    public function testCreateCountryTest()
    {
        // GET /countries/create : View - create country
        $response = $this->get('/countries/create');
        $this->assertGuest();
        $response->assertRedirect(route('login') );

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->get('/countries/create');
        $response->assertViewIs('country.create');
        $response->assertViewHasAll(['categories']);
        $response->assertSeeInOrder(['Title', 'Body']);
    }

    public function testStoreCountryTest()
    {
        // POST /countries : save new country
        $country = [
            'title' => 'Feature Testing',
            'body' => 'A sublime article on PHPUnit capabilities'
        ];

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->country('/countries', $country);
//        $response->assertSuccessful();
        $response->assertSessionHas('message');
        $response->assertRedirect( route('country.index') );
    }

    public function testEditCountryTest()
    {
        // GET /countries/{country}/edit : View - edit country
        $response = $this->get('/countries/2/edit');
        $this->assertGuest();
        $response->assertRedirect( route('login') );

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->get('/countries/2/edit');
        $response->assertViewIs('country.edit');
        $response->assertViewHasAll(['categories', 'country']);
        $response->assertSeeInOrder(['Title', 'Body']);
    }

    public function testUpdateCountryTest()
    {
        // PUT /countries/{country} : update country
        $patch = [
            'title' => 'New Feature Testing',
            'body' => 'A sublime article on PHPUnit capabilities, updated Jan. 2019'
        ];

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->put('/countries/2', $patch);
//        $response->assertSuccessful();
        $response->assertRedirect( route('country.show', ['country' => 2]) );
    }

    public function testDeleteCountryTest()
    {
        // DELETE /countries/{country} : update country
        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->delete('/countries/2');
//        $response->assertSuccessful();
        $response->assertSessionHas('message');
        $response->assertRedirect( route('country.index') );
    }*/



}
