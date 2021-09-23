<?php

namespace App\Services;

use App\Repositories\Datastructures\WeatherReportLogRepository;
use App\Repositories\Infrastructures\Http\Client;
use Exception;
use Illuminate\Support\Carbon;

class RainService
{
    private Client $httpClient; 

    private WeatherReportLogRepository $weatherReportLogRepository;

    public function __construct()
    {
        $this->httpClient = resolve(Client::class);
        $this->weatherReportLogRepository = resolve(WeatherReportLogRepository::class);
    }

    /**
     * @param string $coordinates
     * preturn array
     * @throws Exception
     */
    public function getRainData(string $coordinates): array
    {
        // キャッシュの有無を問い合わせて、キャッシュが存在する場合はそのまま返却
        $queryConditions = [
            'coordinates' => ['parameter' => '=', 'value' => $coordinates],
            'created_at' => ['parameter' => '>=', 'value' => $this->getCacheLimitDate()],
        ];
        if ($this->weatherReportLogRepository->exist($queryConditions)) {
            return $this->generateDisplayData(
                json_decode($this->weatherReportLogRepository->first($queryConditions)->data, true)
            );
        }

        // キャッシュが存在しない場合は、apiを叩いて取得。同時にキャッシュを保存する
        $rainData = $this->httpClient->get($this->generateRainUri($coordinates));
        $this->weatherReportLogRepository->save([
            'coordinates' => $coordinates,
            'data' => json_encode($rainData)
        ]);
        return $this->generateDisplayData($rainData);
    }

    /**
     * @return string
     */
    private function getCacheLimitDate(): string
    {
        return Carbon::now()->subMinutes(10)->format('Y-m-d H:i:s');
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
        $weatherList = $rainData['Feature'][0]['Property']['WeatherList']['Weather'] ?? [];
        $weatherListData = [];
        foreach ($weatherList as $row) {
            $weatherListData[] = [
                'min' => $min,
                'rain_fall' => $row['Rainfall']
            ];
            $min += 10;
        }

        return [
            'title' => $rainData['Feature'][0]['Name'] ?? '',
            'weather_list_data' => $weatherListData
        ];
    }
}
