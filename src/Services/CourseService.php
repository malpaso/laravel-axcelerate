<?php

namespace malpaso\LaravelAxcelerate\Services;

use malpaso\LaravelAxcelerate\Http\Client;
use malpaso\LaravelAxcelerate\Services\Contracts\CourseServiceInterface;

class CourseService implements CourseServiceInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Returns details of an activity
     */
    public function getDetail(array $parameters = []): array
    {
        $validParameters = ['type', 'id'];
        $queryParams = $this->filterParameters($parameters, $validParameters);

        if (isset($queryParams['type'])) {
            $this->validateCourseType($queryParams['type']);
        }

        return $this->client->get('course/detail', $queryParams);
    }

    /**
     * Create new instance of workshop type
     */
    public function create(array $data): array
    {
        return $this->client->post('course/', $data);
    }

    /**
     * Returns instances of an activity type
     */
    public function getInstances(array $parameters = []): array
    {
        $validParameters = [
            'type', 'id', 'public', 'current', 'active',
            'updatedAfter', 'updatedBefore',
        ];
        $queryParams = $this->filterParameters($parameters, $validParameters);

        if (isset($queryParams['type'])) {
            $this->validateCourseType($queryParams['type']);
        }

        // Validate boolean parameters
        foreach (['public', 'current', 'active'] as $boolParam) {
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

        return $this->client->get('course/instances', $queryParams);
    }

    /**
     * Returns details of an activity instance
     */
    public function getInstanceDetail(array $parameters = []): array
    {
        $validParameters = ['type', 'instanceID'];
        $queryParams = $this->filterParameters($parameters, $validParameters);

        if (isset($queryParams['type'])) {
            $this->validateCourseType($queryParams['type']);
        }

        return $this->client->get('course/instance/detail', $queryParams);
    }

    /**
     * Creates an activity instance
     */
    public function createInstance(array $data): array
    {
        return $this->client->post('course/instance/', $data);
    }

    /**
     * Updates existing activity instance
     */
    public function updateInstance(array $data): array
    {
        return $this->client->put('course/instance/', $data);
    }

    /**
     * Advanced Course Instance Search - Returns instances
     */
    public function searchInstances(array $data): array
    {
        return $this->client->post('course/instance/search', $data);
    }

    /**
     * Enrols a Contact in an Activity Instance
     */
    public function enrol(array $data): array
    {
        $requiredFields = ['contactID', 'type', 'instanceID'];
        $this->validateRequiredFields($data, $requiredFields);

        if (isset($data['type'])) {
            $this->validateCourseType($data['type']);
        }

        return $this->client->post('course/enrol', $data);
    }

    /**
     * Enrols one or more Contacts in a workshop activity Instance
     */
    public function enrolMultiple(array $data): array
    {
        $requiredFields = ['payerContactID', 'type', 'instanceID', 'students'];
        $this->validateRequiredFields($data, $requiredFields);

        // Only workshops allowed for multiple enrollment
        if ($data['type'] !== 'w') {
            throw new \InvalidArgumentException('Multiple enrollment is only allowed for workshop type (w)');
        }

        return $this->client->post('course/enrolMultiple', $data);
    }

    /**
     * Return one or more Contacts enrolled in an activity Instance
     */
    public function getEnrolments(array $parameters = []): array
    {
        $validParameters = ['type', 'instanceID', 'includeStatus'];
        $queryParams = $this->filterParameters($parameters, $validParameters);

        if (isset($queryParams['type'])) {
            // Only works on Program and Workshop courses
            if (! in_array($queryParams['type'], ['p', 'w'])) {
                throw new \InvalidArgumentException('Enrolments endpoint only works with type "p" (program) or "w" (workshop)');
            }
        }

        return $this->client->get('course/enrolments', $queryParams);
    }

    /**
     * Update an enrolment
     */
    public function updateEnrolment(array $data): array
    {
        $requiredFields = ['type', 'instanceID', 'contactID'];
        $this->validateRequiredFields($data, $requiredFields);

        if (isset($data['type'])) {
            $this->validateCourseType($data['type']);
        }

        return $this->client->put('course/enrolment', $data);
    }

    /**
     * Calculates potential discount values given a course instance
     */
    public function getDiscounts(array $parameters = []): array
    {
        $validParameters = [
            'type', 'instanceID', 'originalPrice', 'contactID',
            'groupSize', 'promoCode', 'discountIDs',
        ];
        $queryParams = $this->filterParameters($parameters, $validParameters);

        if (isset($queryParams['type'])) {
            $this->validateCourseType($queryParams['type']);
        }

        return $this->client->get('course/discounts', $queryParams);
    }

    /**
     * Allows an established contact to enquire on a course
     */
    public function enquire(array $data): array
    {
        $requiredFields = ['contactID', 'message'];
        $this->validateRequiredFields($data, $requiredFields);

        if (isset($data['type'])) {
            $this->validateCourseType($data['type']);
        }

        return $this->client->post('course/enquire', $data);
    }

    /**
     * Get course calendar data
     */
    public function getCalendar(array $parameters = []): array
    {
        $validParameters = [
            'locationID', 'start', 'end', 'type', 'trainerID',
        ];
        $queryParams = $this->filterParameters($parameters, $validParameters);

        return $this->client->get('course/calendar', $queryParams);
    }

    /**
     * Returns a JSON array of course locations
     */
    public function getLocations(array $parameters = []): array
    {
        return $this->client->get('course/locations', $parameters);
    }

    /**
     * Get course resources
     */
    public function getResources(array $parameters = []): array
    {
        $validParameters = ['type', 'id', 'instanceID'];
        $queryParams = $this->filterParameters($parameters, $validParameters);

        if (isset($queryParams['type'])) {
            $this->validateCourseType($queryParams['type']);
        }

        return $this->client->get('course/resources', $queryParams);
    }

    /**
     * Gets attendance for a Course Instance
     */
    public function getAttendance(array $parameters = []): array
    {
        $validParameters = ['type', 'instanceID', 'contactID'];
        $queryParams = $this->filterParameters($parameters, $validParameters);

        if (isset($queryParams['type'])) {
            $this->validateCourseType($queryParams['type']);
        }

        return $this->client->get('course/instance/attendance', $queryParams);
    }

    /**
     * Sets attendance for a Course Instance
     */
    public function setAttendance(array $data): array
    {
        $requiredFields = ['type', 'instanceID'];
        $this->validateRequiredFields($data, $requiredFields);

        if (isset($data['type'])) {
            $this->validateCourseType($data['type']);
        }

        return $this->client->put('course/instance/attendance', $data);
    }

    /**
     * Get sessions for a workshop
     */
    public function getComplexDates(int $instanceId): array
    {
        return $this->client->get("course/instance/complexdate/{$instanceId}");
    }

    /**
     * Set sessions for a workshop
     */
    public function setComplexDate(int $instanceId, array $data): array
    {
        return $this->client->post("course/instance/complexdate/{$instanceId}", $data);
    }

    /**
     * Get extra trainer for workshop
     */
    public function getExtraTrainer(int $instanceId): array
    {
        return $this->client->get("course/instance/extratrainer/{$instanceId}");
    }

    /**
     * Add extra trainer for workshop
     */
    public function addExtraTrainer(int $instanceId, array $data): array
    {
        return $this->client->post("course/instance/extratrainer/{$instanceId}", $data);
    }

    /**
     * Update extra trainer for workshop
     */
    public function updateExtraTrainer(int $instanceId, array $data): array
    {
        return $this->client->put("course/instance/extratrainer/{$instanceId}", $data);
    }

    /**
     * Delete extra trainer for workshop
     */
    public function deleteExtraTrainer(int $instanceId): array
    {
        return $this->client->delete("course/instance/extratrainer/{$instanceId}");
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
     * Validate course type
     */
    protected function validateCourseType(string $type): void
    {
        if (! in_array($type, ['w', 'p', 'el'])) {
            throw new \InvalidArgumentException('Course type must be one of: w (workshop), p (program), el (e-learning)');
        }
    }

    /**
     * Validate required fields are present
     */
    protected function validateRequiredFields(array $data, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (! isset($data[$field]) || $data[$field] === '' || (is_scalar($data[$field]) && (string) $data[$field] === '')) {
                throw new \InvalidArgumentException("Required field '{$field}' is missing or empty");
            }
        }
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
