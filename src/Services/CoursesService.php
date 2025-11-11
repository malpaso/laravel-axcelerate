<?php

namespace malpaso\LaravelAxcelerate\Services;

use malpaso\LaravelAxcelerate\Http\Client;
use malpaso\LaravelAxcelerate\Services\Contracts\CoursesServiceInterface;

class CoursesService implements CoursesServiceInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Returns a list of courses. Returns accredited, Non-accredited and e-learning courses separately or returns all together
     *
     * @param  array  $parameters  Query parameters for filtering courses
     */
    public function getCourses(array $parameters = []): array
    {
        $validParameters = [
            'ID',                // The ID of the Course to filter
            'type',              // The course type to return. w = workshop, p = accredited program, el = e-learning, all = All types
            'current',           // Current courses flag. True to show only current courses
            'public',            // Whether to include public courses only. If false, returns all course types regardless of public settings
            'lastUpdated_min',      // In 'YYYY-MM-DD hh:mm' format. The course last updated date must be greater than or equal to this datetime
            'lastUpdated_max',     // In 'YYYY-MM-DD hh:mm' format. The course last updated date must be less than or equal to this datetime
            'IsActive',            // Whether to include active/inactive courses only. By default both will be included
        ];

        $queryParams = $this->filterParameters($parameters, $validParameters);

        // Validate course type if provided
        if (isset($queryParams['type']) && ! in_array($queryParams['type'], ['w', 'p', 'el', 'all'])) {
            throw new \InvalidArgumentException('Course type must be one of: w, p, el, all');
        }

        // Validate boolean parameters
        foreach (['current', 'public', 'active'] as $boolParam) {
            if (isset($queryParams[$boolParam])) {
                $queryParams[$boolParam] = $this->normalizeBooleanParameter($queryParams[$boolParam]);
            }
        }

        // Validate date parameters
        foreach (['updatedAfter', 'updatedBefore'] as $dateParam) {
            if (isset($queryParams[$dateParam])) {
                $this->validateDateFormat($queryParams[$dateParam]);
            }
        }

        return $this->client->get('courses/', $queryParams);
    }

    /**
     * Filter parameters to only include valid ones
     */
    protected function filterParameters(array $parameters, array $validParameters): array
    {
        return array_filter($parameters, function ($key) use ($validParameters) {
            return in_array($key, $validParameters);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Normalize boolean parameter values
     */
    protected function normalizeBooleanParameter($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_string($value)) {
            $lowercaseValue = strtolower($value);
            if (in_array($lowercaseValue, ['true', '1', 'yes', 'on'])) {
                return 'true';
            }
            if (in_array($lowercaseValue, ['false', '0', 'no', 'off'])) {
                return 'false';
            }
        }

        return $value ? 'true' : 'false';
    }

    /**
     * Validate date format (YYYY-MM-DD hh:mm)
     */
    protected function validateDateFormat(string $date): void
    {
        // Allow both YYYY-MM-DD and YYYY-MM-DD hh:mm formats
        $patterns = [
            '/^\d{4}-\d{2}-\d{2}$/',
            '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/',
        ];

        $isValid = false;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $date)) {
                $isValid = true;
                break;
            }
        }

        if (! $isValid) {
            throw new \InvalidArgumentException("Date must be in format 'YYYY-MM-DD' or 'YYYY-MM-DD hh:mm'");
        }
    }
}
