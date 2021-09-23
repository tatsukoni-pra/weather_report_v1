<?php

namespace Tests\Feature\Controllers;

use App\Services\RainService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class RainControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testIndex200()
    {
        // Arrange
        $this->mock(RainService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getRainData')
                ->times(1)
                ->with('139.732293,35.663613')
                ->andReturn([
                    'title' => '',
                    'weather_list_data' => []
                ]);
        });

        // Act
        $response = $this->get('/rain');

        // Assert
        $response->assertStatus(200);
    }
}
