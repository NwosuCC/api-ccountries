<?php

namespace Tests\Unit\PostTest;

use App\Country;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Foundation\Testing\TestResponse as Response;


/** @group Post */
class PostCreateTest extends TestCase
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


  protected $model_name = Country::class;
  protected $model;

  protected function model() {
    if(!$this->model) {
      $this->model = app($this->model_name);
    }
    return $this->model;
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
    return factory($this->model_name, $count);
  }

  /** @test */
  public function root_redirects_to_posts_index()
  {
    $response = $this->get('/');
    $response->assertStatus(302);
    $response->assertRedirect( route('post.index') );
  }

  /** @test */
  public function get_posts_index() {
    $response = $this->get( $this->model()->route->index );
    $response->assertSuccessful();
    $response->assertViewIs('post.index');
    $response->assertViewHasAll(['posts', 'categories', 'user', 'category']);
    $response->assertSee('Articles');
    return $response;
  }

  private function _assert_DB_has_posts(Collection $posts) {
    $posts->each(function ($post) use ($posts) {
      $this->assertDatabaseHas('posts', ['title' => $post->title]);
    });
  }

  private function _DB_store_posts($count) {
    $posts = $this->factory($count)->create();

    $this->assertCount($count, $posts);

    $this->_assert_DB_has_posts($posts);

    return $posts;
  }

  /** @test */
  public function post_index_fetches_posts() {
    $posts_count = 2;

    $created_posts = $this->_DB_store_posts($posts_count);
    $this->assertCount($posts_count, $created_posts);

    $response = $this->get_posts_index();

    $fetched_posts = $response->viewData('posts');
    $this->assertCount($posts_count, $fetched_posts);

    $latest_post = $fetched_posts->sortBy('published_at', 0, 'desc')->first();

    $response->assertJsonStructure([
      'id', 'title', 'body', 'slug', 'user_id', 'category_id', 'published_at'
    ], $latest_post);
  }

  /** @test */
  public function post_index_fetches_only_active_published_posts() {
    $first_post = $this->factory()
      ->state('deleted_category')
      ->create(['title' => 'First Article With Deleted Category'])
      ->first();

    $second_post = $this->factory()
      ->state('not_yet_published')
      ->create(['title' => 'Second Article Not Yet Published'])
      ->first();

    $third_post = $this->factory()
      ->state('deleted_post')
      ->create(['title' => 'Third Article Already Deleted'])
      ->first();

    $fourth_post = $this->factory()
      ->create(['title' => 'Fourth Active Article'])
      ->first();

    $invalid_posts = [
      $first_post, $second_post, $third_post
    ];


    $this->_assert_DB_has_posts(
      collect([$first_post, $second_post, $third_post, $fourth_post])
    );

    $response = $this->get_posts_index();

    $fetched_titles = $response->viewData('posts')->pluck('title', 'id');


    foreach($invalid_posts as $invalid_post) {
      $this->assertArrayNotHasKey( $invalid_post->id, $fetched_titles);
    }
  }

}
