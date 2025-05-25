<?php

namespace Tests\Feature\API;

use App\Models\Platform;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PlatformControllerTest extends TestCase
{
    public function test_user_can_fetch_all_platforms(): void
    {
        $platforms = Platform::factory()->count(3)->create();

        $response = $this->getJson(route('platforms.index'), $this->headers);

        $response->json();

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals(3, count($response['data']['platforms']));
    }

    public function test_user_can_fetch_all_active_platforms(): void
    {
        $platforms = Platform::factory()->count(3)->create();
        Platform::factory()->count(3)->create();
        $this->loginUser->platforms()->sync($platforms->pluck('id')->toArray());
        $response = $this->getJson(route('platforms.index', ['active' => true]), $this->headers);

        $response->json();

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals(3, count($response['data']['platforms']));
    }

    public function test_user_can_sync_platforms(): void
    {
        $data = [
            'platforms' => Platform::factory()->count(3)->create()->pluck('id')->toArray()
        ];

        $response = $this->postJson(route('platforms.store'), $data, $this->headers);

        $response->json();

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('platform_users', [
            'user_id' => $this->loginUser->id,
            'platform_id' => $data['platforms'][0],
        ]);
    }
}
