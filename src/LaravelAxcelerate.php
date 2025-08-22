<?php

namespace malpaso\LaravelAxcelerate;

use malpaso\LaravelAxcelerate\Http\Client;
use malpaso\LaravelAxcelerate\Services\Contracts\CourseServiceInterface;
use malpaso\LaravelAxcelerate\Services\Contracts\CoursesServiceInterface;

class LaravelAxcelerate
{
    protected Client $client;

    protected CoursesServiceInterface $coursesService;

    protected CourseServiceInterface $courseService;

    public function __construct(
        Client $client,
        CoursesServiceInterface $coursesService,
        CourseServiceInterface $courseService
    ) {
        $this->client = $client;
        $this->coursesService = $coursesService;
        $this->courseService = $courseService;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function testConnection(): array
    {
        // This method can be used to test the API connection
        // Implementation will depend on available test endpoints
        return $this->client->get('');
    }

    // Courses Service Methods
    public function getCourses(array $parameters = []): array
    {
        return $this->coursesService->getCourses($parameters);
    }

    // Course Service Methods
    public function getCourseDetail(array $parameters = []): array
    {
        return $this->courseService->getDetail($parameters);
    }

    public function createCourse(array $data): array
    {
        return $this->courseService->create($data);
    }

    public function getCourseInstances(array $parameters = []): array
    {
        return $this->courseService->getInstances($parameters);
    }

    public function getCourseInstanceDetail(array $parameters = []): array
    {
        return $this->courseService->getInstanceDetail($parameters);
    }

    public function createCourseInstance(array $data): array
    {
        return $this->courseService->createInstance($data);
    }

    public function updateCourseInstance(array $data): array
    {
        return $this->courseService->updateInstance($data);
    }

    public function searchCourseInstances(array $data): array
    {
        return $this->courseService->searchInstances($data);
    }

    public function enrolInCourse(array $data): array
    {
        return $this->courseService->enrol($data);
    }

    public function enrolMultipleInCourse(array $data): array
    {
        return $this->courseService->enrolMultiple($data);
    }

    public function getCourseEnrolments(array $parameters = []): array
    {
        return $this->courseService->getEnrolments($parameters);
    }

    public function updateCourseEnrolment(array $data): array
    {
        return $this->courseService->updateEnrolment($data);
    }

    public function getCourseDiscounts(array $parameters = []): array
    {
        return $this->courseService->getDiscounts($parameters);
    }

    public function enquireAboutCourse(array $data): array
    {
        return $this->courseService->enquire($data);
    }

    public function getCourseCalendar(array $parameters = []): array
    {
        return $this->courseService->getCalendar($parameters);
    }

    public function getCourseLocations(array $parameters = []): array
    {
        return $this->courseService->getLocations($parameters);
    }

    public function getCourseResources(array $parameters = []): array
    {
        return $this->courseService->getResources($parameters);
    }

    public function getCourseAttendance(array $parameters = []): array
    {
        return $this->courseService->getAttendance($parameters);
    }

    public function setCourseAttendance(array $data): array
    {
        return $this->courseService->setAttendance($data);
    }

    public function getCourseComplexDates(int $instanceId): array
    {
        return $this->courseService->getComplexDates($instanceId);
    }

    public function setCourseComplexDate(int $instanceId, array $data): array
    {
        return $this->courseService->setComplexDate($instanceId, $data);
    }

    public function getCourseExtraTrainer(int $instanceId): array
    {
        return $this->courseService->getExtraTrainer($instanceId);
    }

    public function addCourseExtraTrainer(int $instanceId, array $data): array
    {
        return $this->courseService->addExtraTrainer($instanceId, $data);
    }

    public function updateCourseExtraTrainer(int $instanceId, array $data): array
    {
        return $this->courseService->updateExtraTrainer($instanceId, $data);
    }

    public function deleteCourseExtraTrainer(int $instanceId): array
    {
        return $this->courseService->deleteExtraTrainer($instanceId);
    }

    // Service Getters
    public function courses(): CoursesServiceInterface
    {
        return $this->coursesService;
    }

    public function course(): CourseServiceInterface
    {
        return $this->courseService;
    }
}
