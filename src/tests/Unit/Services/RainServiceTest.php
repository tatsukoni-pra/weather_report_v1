<?php

namespace Tests\Unit\Services;

use App\Models\WeatherReportLog;
use App\Repositories\Infrastructures\Http\Client;
use App\Services\RainService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

class RainServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2021-09-24 15:00:00');
    }

    /**
     * キャッシュなし
     * @return void
     */
    public function testGetRainDataNoCache200(): void
    {
        // Arrange
        $this->getClientMock();

        // Act
        $result = (new RainService())
            ->getRainData('139.732293,35.663613');

        // Assert
        $this->assertSame('dummy-title', $result['title']);
        $this->assertSame([
            [
                'min' => 0,
                'rain_fall' => 0
            ],
            [
                'min' => 10,
                'rain_fall' => 1
            ],
            [
                'min' => 20,
                'rain_fall' => 2
            ],
        ], $result['weather_list_data']);
        $this->assertDatabaseHas('weather_report_logs', [
            'coordinates' => '139.732293,35.663613'
        ]);
    }

    /**
     * キャッシュあり
     * @return void
     */
    public function testGetRainDataWithCache200(): void
    {
        // Arrange
        WeatherReportLog::factory()->create([
            'coordinates' => '139.732293,35.663613',
            'data' => json_encode($this->getClientMockResponce()),
            'created_at' => '2021-09-24 14:50:01'
        ]);

        // Act
        $result = (new RainService())
            ->getRainData('139.732293,35.663613');

        // Assert
        $this->assertSame('dummy-title', $result['title']);
        $this->assertSame([
            [
                'min' => 0,
                'rain_fall' => 0
            ],
            [
                'min' => 10,
                'rain_fall' => 1
            ],
            [
                'min' => 20,
                'rain_fall' => 2
            ],
        ], $result['weather_list_data']);
        $this->assertCount(1, WeatherReportLog::all()->toArray());
    }

    private function getClientMock()
    {
        return $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->times(1)
                ->andReturn($this->getClientMockResponce());
        });
    }

    private function getClientMockResponce()
    {
        return [
            'Feature' => [
                [
                    'Name' => 'dummy-title',
                    'Property' => [
                        'WeatherList' => [
                            'Weather' => [
                                [
                                    'Type' => 'observation',
                                    'Date' => '202109212225',
                                    'Rainfall' => 0,
                                ],
                                [
                                    'Type' => 'observation',
                                    'Date' => '202109212235',
                                    'Rainfall' => 1,
                                ],
                                [
                                    'Type' => 'observation',
                                    'Date' => '202109212245',
                                    'Rainfall' => 2,
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
