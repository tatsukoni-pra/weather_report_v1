<?php

namespace App\Services;

use App\Repositories\Infrastructures\Http\Client;

class RainService
{
    private Client $httpClient; 

    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @param string $coordinates
     * preturn array
     */
    public function getRainData(string $coordinates): array
    {
        return $this->generateDisplayData(
            $this->httpClient->get($this->generateRainUri($coordinates))
        );
    }

    /**
     * @param string $coordinates
     * @return string
     */
    private function generateRainUri(string $coordinates): string
    {
        return implode('', [
            config('appdomain.yahoo_weather_domain'),
            "?coordinates=$coordinates",
            '&output=json',
            '&appid=' . config('appdomain.yahoo_api_key')
        ]);
    }

    /**
     * @param array $rainData
     * @return array
     */
    private function generateDisplayData(array $rainData): array
    {
        /**
         * $rainData['Feature'][0]['Property']['WeatherList']['Weather']:
         * [
         *   'Type' => 'observation',
         *   'Date' => '202109212225',
         *   'Rainfall' => 0.0,
         * ],
         * ...
         */
        $min = 0;
        $weatherListData = [];
        foreach ($rainData['Feature'][0]['Property']['WeatherList']['Weather'] as $row) {
            $weatherListData[] = [
                'min' => $min,
                'rain_fall' => $row['Rainfall']
            ];
            $min += 10;
        }

        return [
            'title' => $rainData['Feature'][0]['Name'],
            'weather_list_data' => $weatherListData
        ];
    }
}
