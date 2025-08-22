<?php

namespace malpaso\LaravelAxcelerate\Services\Contracts;

interface CourseServiceInterface
{
    public function getDetail(array $parameters = []): array;

    public function create(array $data): array;

    public function getInstances(array $parameters = []): array;

    public function getInstanceDetail(array $parameters = []): array;

    public function createInstance(array $data): array;

    public function updateInstance(array $data): array;

    public function searchInstances(array $data): array;

    public function enrol(array $data): array;

    public function enrolMultiple(array $data): array;

    public function getEnrolments(array $parameters = []): array;

    public function updateEnrolment(array $data): array;

    public function getDiscounts(array $parameters = []): array;

    public function enquire(array $data): array;

    public function getCalendar(array $parameters = []): array;

    public function getLocations(array $parameters = []): array;

    public function getResources(array $parameters = []): array;

    public function getAttendance(array $parameters = []): array;

    public function setAttendance(array $data): array;

    public function getComplexDates(int $instanceId): array;

    public function setComplexDate(int $instanceId, array $data): array;

    public function getExtraTrainer(int $instanceId): array;

    public function addExtraTrainer(int $instanceId, array $data): array;

    public function updateExtraTrainer(int $instanceId, array $data): array;

    public function deleteExtraTrainer(int $instanceId): array;
}
