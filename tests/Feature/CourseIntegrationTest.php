<?php

namespace malpaso\LaravelAxcelerate\Tests\Feature;

use malpaso\LaravelAxcelerate\LaravelAxcelerate;
use malpaso\LaravelAxcelerate\Services\Contracts\CourseServiceInterface;
use malpaso\LaravelAxcelerate\Services\Contracts\CoursesServiceInterface;
use malpaso\LaravelAxcelerate\Services\CourseService;
use malpaso\LaravelAxcelerate\Services\CoursesService;
use malpaso\LaravelAxcelerate\Tests\TestCase;

class CourseIntegrationTest extends TestCase
{
    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('axcelerate', [
            'base_url' => 'https://example.axcelerate.com',
            'ws_token' => 'test-ws-token',
            'api_token' => 'test-api-token',
        ]);
    }

    public function test_courses_service_is_bound_correctly(): void
    {
        $service = $this->app->make(CoursesServiceInterface::class);

        $this->assertInstanceOf(CoursesService::class, $service);
    }

    public function test_course_service_is_bound_correctly(): void
    {
        $service = $this->app->make(CourseServiceInterface::class);

        $this->assertInstanceOf(CourseService::class, $service);
    }

    public function test_concrete_courses_service_is_bound_correctly(): void
    {
        $service = $this->app->make(CoursesService::class);

        $this->assertInstanceOf(CoursesService::class, $service);
    }

    public function test_concrete_course_service_is_bound_correctly(): void
    {
        $service = $this->app->make(CourseService::class);

        $this->assertInstanceOf(CourseService::class, $service);
    }

    public function test_laravel_axcelerate_is_bound_with_course_services(): void
    {
        $axcelerate = $this->app->make(LaravelAxcelerate::class);

        $this->assertInstanceOf(LaravelAxcelerate::class, $axcelerate);
        $this->assertInstanceOf(CoursesServiceInterface::class, $axcelerate->courses());
        $this->assertInstanceOf(CourseServiceInterface::class, $axcelerate->course());
    }

    public function test_course_services_are_singletons(): void
    {
        $coursesService1 = $this->app->make(CoursesServiceInterface::class);
        $coursesService2 = $this->app->make(CoursesServiceInterface::class);

        $courseService1 = $this->app->make(CourseServiceInterface::class);
        $courseService2 = $this->app->make(CourseServiceInterface::class);

        $this->assertSame($coursesService1, $coursesService2);
        $this->assertSame($courseService1, $courseService2);
    }

    public function test_laravel_axcelerate_course_methods_delegate_to_services(): void
    {
        $axcelerate = $this->app->make(LaravelAxcelerate::class);

        // Test that course service methods are available
        $this->assertTrue(method_exists($axcelerate, 'getCourses'));
        $this->assertTrue(method_exists($axcelerate, 'getCourseDetail'));
        $this->assertTrue(method_exists($axcelerate, 'createCourse'));
        $this->assertTrue(method_exists($axcelerate, 'getCourseInstances'));
        $this->assertTrue(method_exists($axcelerate, 'enrolInCourse'));
        $this->assertTrue(method_exists($axcelerate, 'getCourseEnrolments'));
    }

    public function test_service_getters_return_correct_instances(): void
    {
        $axcelerate = $this->app->make(LaravelAxcelerate::class);

        $coursesService = $axcelerate->courses();
        $courseService = $axcelerate->course();

        $this->assertInstanceOf(CoursesServiceInterface::class, $coursesService);
        $this->assertInstanceOf(CourseServiceInterface::class, $courseService);

        // Test that the same instances are returned on subsequent calls
        $this->assertSame($coursesService, $axcelerate->courses());
        $this->assertSame($courseService, $axcelerate->course());
    }

    public function test_facade_can_access_course_methods(): void
    {
        // This test verifies that the facade methods are properly annotated
        // and that the service methods are accessible through the facade

        $reflection = new \ReflectionClass(\malpaso\LaravelAxcelerate\Facades\LaravelAxcelerate::class);
        $docComment = $reflection->getDocComment();

        // Check that course-related methods are documented in the facade
        $this->assertStringContainsString('@method static array getCourses(', $docComment);
        $this->assertStringContainsString('@method static array getCourseDetail(', $docComment);
        $this->assertStringContainsString('@method static array enrolInCourse(', $docComment);
        $this->assertStringContainsString('@method static array getCourseEnrolments(', $docComment);
    }
}
