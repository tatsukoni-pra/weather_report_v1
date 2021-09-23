<?php

namespace Tests\Unit\Services;

use App\Repositories\Infrastructures\Http\Client;
use App\Services\RainService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class RainServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testGetRainData200()
    {
        // Arrange
        $client = $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->times(1)
                ->andReturn([
                    'Feature' => [
                        [
                            'Name' => 'dummy-title',
                            'Property' => [
                                'WeatherList' => [
                                    'Weather' => [
                                        [
                                            'Type' => 'observation',
                                            'Date' => '202109212225',
                                            'Rainfall' => 0.0,
                                        ],
                                        [
                                            'Type' => 'observation',
                                            'Date' => '202109212235',
                                            'Rainfall' => 1.0,
                                        ],
                                        [
                                            'Type' => 'observation',
                                            'Date' => '202109212245',
                                            'Rainfall' => 2.0,
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]);
        });

        // Act
        $result = (new RainService())
            ->getRainData('139.732293,35.663613');

        // Assert
        $this->assertSame('dummy-title', $result['title']);
        $this->assertSame([
            [
                'min' => 0,
                'rain_fall' => 0.0
            ],
            [
                'min' => 10,
                'rain_fall' => 1.0
            ],
            [
                'min' => 20,
                'rain_fall' => 2.0
            ],
        ], $result['weather_list_data']);
    }
}
