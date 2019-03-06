<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

use App\User;

/** @group Post */
class ExampleTest extends TestCase
{
    use InteractsWithAuthentication;


    private $user;

    private function printValue($value) {
        echo json_encode($value);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');
        $response->assertRedirect( route('post.index') );
    }

    public function testLoginTest()
    {
        // GET /login : View - log in
        $response = $this->get('/login');
        $response->assertViewIs('auth.login');
        $response->assertSeeInOrder(['E-Mail Address', 'Password', 'Remember Me']);

        // POST /login : authenticate user
        $credentials = [
            'email' => 'mario44@elite.com', 'password' => 'mario44',
            '_token' => 'lIXB7CSBLlfZ7JIKgNd9p8NVRp0qEIJrkFaHh93d'
        ];
        $response = $this->post('/login', $credentials);
        $response->assertStatus(302);
        $this->assertAuthenticated();

//        $this->printValue( $this->user = Auth::user() );
    }

    public function testPostsTest()
    {
        // GET /posts : View - all posts
        $response = $this->get('/posts');
        $response->assertSuccessful();
        $response->assertViewIs('post.index');
        $response->assertViewHasAll(['posts']);
    }

    public function testShowPostTest()
    {
        // GET /posts/:id : View - one post
        $response = $this->get('/posts/{wrongId}');
        $response->assertNotFound();

        $response = $this->get('/posts/2');
        $response->assertSuccessful();
        $response->assertViewIs('post.show');
        $response->assertViewHasAll(['post']);

        $this->assertEquals( $response->viewData('post')->id, 2 );
    }

    public function testCreatePostTest()
    {
        // GET /posts/create : View - create post
        $response = $this->get('/posts/create');
        $this->assertGuest();
        $response->assertRedirect(route('login') );

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->get('/posts/create');
        $response->assertViewIs('post.create');
        $response->assertViewHasAll(['categories']);
        $response->assertSeeInOrder(['Title', 'Body']);
    }

    public function testStorePostTest()
    {
        // POST /posts : save new post
        $post = [
            'title' => 'Feature Testing',
            'body' => 'A sublime article on PHPUnit capabilities'
        ];

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->post('/posts', $post);
//        $response->assertSuccessful();
        $response->assertSessionHas('message');
        $response->assertRedirect( route('post.index') );
    }

    public function testEditPostTest()
    {
        // GET /posts/{post}/edit : View - edit post
        $response = $this->get('/posts/2/edit');
        $this->assertGuest();
        $response->assertRedirect( route('login') );

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->get('/posts/2/edit');
        $response->assertViewIs('post.edit');
        $response->assertViewHasAll(['categories', 'post']);
        $response->assertSeeInOrder(['Title', 'Body']);
    }

    public function testUpdatePostTest()
    {
        // PUT /posts/{post} : update post
        $patch = [
            'title' => 'New Feature Testing',
            'body' => 'A sublime article on PHPUnit capabilities, updated Jan. 2019'
        ];

        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->put('/posts/2', $patch);
//        $response->assertSuccessful();
        $response->assertRedirect( route('post.show', ['post' => 2]) );
    }

    public function testDeletePostTest()
    {
        // DELETE /posts/{post} : update post
        $user = Auth::loginUsingId(1);
        $this->assertAuthenticated();

        $response = $this->actingAs($user)->delete('/posts/2');
//        $response->assertSuccessful();
        $response->assertSessionHas('message');
        $response->assertRedirect( route('post.index') );
    }



}
