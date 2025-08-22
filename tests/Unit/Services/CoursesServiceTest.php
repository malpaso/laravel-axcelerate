<?php

namespace malpaso\LaravelAxcelerate\Tests\Unit\Services;

use malpaso\LaravelAxcelerate\Http\Client;
use malpaso\LaravelAxcelerate\Services\CoursesService;
use malpaso\LaravelAxcelerate\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CoursesServiceTest extends TestCase
{
    private CoursesService $coursesService;

    private MockObject $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = $this->createMock(Client::class);
        $this->coursesService = new CoursesService($this->mockClient);
    }

    public function test_get_courses_without_parameters(): void
    {
        $expectedResponse = ['courses' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('courses/', [])
            ->willReturn($expectedResponse);

        $result = $this->coursesService->getCourses();

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_get_courses_with_valid_parameters(): void
    {
        $parameters = [
            'courseID' => 123,
            'type' => 'w',
            'current' => true,
            'public' => false,
        ];

        $expectedQueryParams = [
            'courseID' => 123,
            'type' => 'w',
            'current' => 'true',
            'public' => 'false',
        ];

        $expectedResponse = ['courses' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('courses/', $expectedQueryParams)
            ->willReturn($expectedResponse);

        $result = $this->coursesService->getCourses($parameters);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_get_courses_filters_invalid_parameters(): void
    {
        $parameters = [
            'courseID' => 123,
            'invalidParam' => 'value',
            'type' => 'w',
        ];

        $expectedQueryParams = [
            'courseID' => 123,
            'type' => 'w',
        ];

        $expectedResponse = ['courses' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('courses/', $expectedQueryParams)
            ->willReturn($expectedResponse);

        $result = $this->coursesService->getCourses($parameters);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_get_courses_throws_exception_for_invalid_course_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Course type must be one of: w, p, el, all');

        $this->coursesService->getCourses(['type' => 'invalid']);
    }

    public function test_get_courses_validates_date_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Date must be in format 'YYYY-MM-DD' or 'YYYY-MM-DD hh:mm'");

        $this->coursesService->getCourses(['updatedAfter' => 'invalid-date']);
    }

    public function test_get_courses_accepts_valid_date_formats(): void
    {
        $parameters = [
            'updatedAfter' => '2023-12-01',
            'updatedBefore' => '2023-12-31 23:59',
        ];

        $expectedResponse = ['courses' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('courses/', $parameters)
            ->willReturn($expectedResponse);

        $result = $this->coursesService->getCourses($parameters);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_normalize_boolean_parameter_with_various_inputs(): void
    {
        $testCases = [
            [true, 'true'],
            [false, 'false'],
            ['true', 'true'],
            ['false', 'false'],
            ['1', 'true'],
            ['0', 'false'],
            ['yes', 'true'],
            ['no', 'false'],
            ['on', 'true'],
            ['off', 'false'],
            [1, 'true'],
            [0, 'false'],
        ];

        foreach ($testCases as $index => [$input, $expected]) {
            $mockClient = $this->createMock(Client::class);
            $coursesService = new CoursesService($mockClient);

            $parameters = ['current' => $input];
            $expectedQueryParams = ['current' => $expected];

            $mockClient
                ->expects($this->once())
                ->method('get')
                ->with('courses/', $expectedQueryParams)
                ->willReturn(['courses' => []]);

            $coursesService->getCourses($parameters);
        }
    }
}
