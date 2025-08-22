<?php

namespace malpaso\LaravelAxcelerate\Services\Contracts;

interface CoursesServiceInterface
{
    public function getCourses(array $parameters = []): array;
}
