<?php

namespace Tests\Feature\API;

use App\Class\Rules\MaxLengthRule;
use App\Enums\PostStatusEnum;
use App\Models\Platform;
use App\Models\Post;
use App\Models\Rule;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    public function test_user_can_fetch_paginated_posts(): void
    {
        Post::factory()->count(3)->create(['user_id' => $this->loginUser->id]);
        Post::factory()->count(3)->create();

        $response = $this->getJson(route('post.index'), $this->headers);

        $response->json();

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonCount(3, 'data.items');

        $this->assertEquals($this->loginUser->id, $response['data']['items'][0]['user_id']);
    }

    public function test_user_can_filter_posts_based_on_status(): void
    {
        Post::factory()->count(3)->create(['user_id' => $this->loginUser->id, 'status' => PostStatusEnum::PUBLISHED->value]);
        Post::factory()->count(3)->create(['user_id' => $this->loginUser->id, 'status' => PostStatusEnum::DRAFT->value]);

        $response = $this->getJson(route('post.index', ['status' => PostStatusEnum::PUBLISHED->value]), $this->headers);

        $response->json();

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonCount(3, 'data.items');

        $this->assertEquals(PostStatusEnum::PUBLISHED->value, $response['data']['items'][0]['status']);
    }

    public function test_user_can_filter_posts_by_date(): void
    {
        Post::factory()->count(3)->create(['user_id' => $this->loginUser->id, 'scheduled_time' => now()->addDays(1)]);
        $post = Post::factory()->count(1)->create(['user_id' => $this->loginUser->id, 'scheduled_time' => now()->subDays(1)]);

        $response = $this->getJson(route('post.index', ['date' => now()->subDay()->format('Y-m-d')]), $this->headers);

        $response->json();

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonCount(1, 'data.items');

        $this->assertEquals(now()->subDay()->format('Y-m-d'), Carbon::parse($response['data']['items'][0]['scheduled_time'])->format('Y-m-d'));
    }

    public function test_user_can_store_new_post(): void
    {
        $platform = Platform::factory()->create();

        $rule = Rule::factory()->create([
            'path' => MaxLengthRule::class,
            'key' => 'title',
            'value' => 260,
            'platform_id' => $platform->id
        ]);

        $data = [
            'title' => fake()->sentence(),
            'content' => fake()->sentence(),
            'status' => PostStatusEnum::PUBLISHED->value,
            'scheduled_time' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'platform_id' => [$platform->id],
            'image' => UploadedFile::fake()->image('avatar.png')
        ];

        $response = $this->postJson(route('post.store'), $data, $this->headers);

        $response->json();

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('posts', [
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => $data['status'],
            'scheduled_time' => $data['scheduled_time'],
        ]);

        $this->assertDatabaseHas('post_platforms', [
            'platform_id' => $data['platform_id'][0],
            'post_id' => $response['data']['post']['id']
        ]);
    }


    public function test_user_can_pass_day_post_limit(): void
    {
        Post::factory()->count(10)->create(['user_id' => $this->loginUser->id]);

        $data = [
            'title' => fake()->sentence(),
            'content' => fake()->sentence(),
            'status' => PostStatusEnum::PUBLISHED->value,
            'scheduled_time' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'platform_id' => [Platform::factory()->create()->id],
            'image' => UploadedFile::fake()->image('avatar.png')
        ];

        $response = $this->postJson(route('post.store'), $data, $this->headers);


        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('posts', [
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => $data['status'],
            'scheduled_time' => $data['scheduled_time'],
        ]);
    }

    public function test_user_must_send_platform_valid_rules_request(): void
    {
        $platform = Platform::factory()->create();

        $rule = Rule::factory()->create([
            'path' => MaxLengthRule::class,
            'key' => 'title',
            'value' => 1,
            'platform_id' => $platform->id
        ]);

        $data = [
            'title' => fake()->sentence(),
            'content' => fake()->sentence(),
            'status' => PostStatusEnum::PUBLISHED->value,
            'scheduled_time' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'platform_id' => [$platform->id],
            'image' => UploadedFile::fake()->image('avatar.png')
        ];

        $response = $this->postJson(route('post.store'), $data, $this->headers);


        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('posts', [
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => $data['status'],
            'scheduled_time' => $data['scheduled_time'],
        ]);
    }
}
