<?php

namespace App\Repositories\Datastructures;

use App\Models\WeatherReportLog;
use Illuminate\Database\Eloquent\Builder;

class WeatherReportLogRepository
{
    private WeatherReportLog $weatherReportLog;

    /**
     * @param WeatherReportLog $weatherReportLog
     */
    public function __construct(WeatherReportLog $weatherReportLog)
    {
        $this->weatherReportLog = $weatherReportLog;
    }

    /**
     * @param array $queryConditions
     * @return bool
     */
    public function exist(array $queryConditions): bool
    {
        return $this->buildQuery($this->weatherReportLog::query(), $queryConditions)
            ->exists();
    }

    /**
     * @param array $queryConditions
     * @return WeatherReportLog
     */
    public function first(array $queryConditions): WeatherReportLog
    {
        return $this->buildQuery($this->weatherReportLog::query(), $queryConditions)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * @param array $createData
     * @return void
     */
    public function save(array $createData): void
    {
        $this->weatherReportLog->create($createData);
    }

    /**
     * @param Builder $query
     * @param array $queryConditions
     * @return Builder
     */
    private function buildQuery(Builder $query, array $queryConditions): Builder
    {
        foreach ($queryConditions as $column => $values) {
            if (!isset($values['parameter']) || !isset($values['value'])) {
                continue;
            }
            $query = $query->where($column, $values['parameter'], $values['value']);
        }
        return $query;
    }
}
