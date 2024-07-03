<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use App\Services\RebrickableService;
use App\Models\CustomSet;
use Illuminate\Support\Facades\Http;

class LegoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $rebrickableService;

    public function setUp(): void
    {
        parent::setUp();

        $this->rebrickableService = Mockery::mock(RebrickableService::class);
        $this->app->instance(RebrickableService::class, $this->rebrickableService);
    }

    public function testGetSets()
    {
        $mockResponse = [
            'count' => 1,
            'results' => [
                [
                    'set_num' => '1234',
                    'name' => 'Test Set',
                    'year' => 2024,
                    'theme_id' => 1,
                    'num_parts' => 100,
                ]
            ]
        ];

        $this->rebrickableService->shouldReceive('getSets')
            ->once()
            ->with(null, 1, 20, null, 2024)
            ->andReturn($mockResponse);

        $response = $this->getJson('/api/lego/sets');

        $response->assertStatus(200)
                 ->assertJson($mockResponse);
    }

    public function testGetParts()
    {
        $mockResponse = [
            'count' => 1,
            'results' => [
                [
                    'part_num' => '3001',
                    'name' => 'Brick 2x4',
                    'category_id' => 1,
                ]
            ]
        ];

        $this->rebrickableService->shouldReceive('getParts')
            ->once()
            ->with(null, 1, 20, null)
            ->andReturn($mockResponse);

        $response = $this->getJson('/api/lego/parts');

        $response->assertStatus(200)
                 ->assertJson($mockResponse);
    }

    public function testGetSetDetails()
    {
        $mockResponse = [
            'set_num' => '1234',
            'name' => 'Test Set',
            'year' => 2024,
            'theme_id' => 1,
            'num_parts' => 100,
            'partsInSet' => [
                [
                    'part_num' => '3001',
                    'name' => 'Brick 2x4',
                    'quantity' => 10
                ]
            ],
            'customParts' => []
        ];

        $this->rebrickableService->shouldReceive('getSetDetails')
            ->once()
            ->with('1234')
            ->andReturn($mockResponse);

        $response = $this->getJson('/api/lego/sets/1234');

        $response->assertStatus(200)
                 ->assertJson($mockResponse);
    }

    public function testCustomizePartsInSet()
    {
        $customParts = [
            [
                'part_num' => '3001',
                'quantity' => 10
            ]
        ];

        $this->rebrickableService->shouldReceive('customizePartsInSet')
            ->once()
            ->with('1234', $customParts)
            ->andReturn(['message' => 'Parts customized successfully']);

        $response = $this->postJson('/api/lego/sets/1234/parts', $customParts);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Parts customized successfully']);
    }

    public function testGetCustomPartsInSet()
    {
        $mockResponse = [
            'count' => 1,
            'results' => [
                [
                    'part_num' => '3001',
                    'name' => 'Brick 2x4',
                    'quantity' => 10
                ]
            ]
        ];

        $this->rebrickableService->shouldReceive('getCustomPartsInSet')
            ->once()
            ->with('1234')
            ->andReturn($mockResponse);

        $response = $this->getJson('/api/lego/sets/1234/custom-parts');

        $response->assertStatus(200)
                 ->assertJson($mockResponse);
    }
}