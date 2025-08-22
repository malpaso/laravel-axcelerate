<?php

namespace malpaso\LaravelAxcelerate\Tests\Unit;

use malpaso\LaravelAxcelerate\Http\Client;
use malpaso\LaravelAxcelerate\LaravelAxcelerate;
use malpaso\LaravelAxcelerate\Services\CourseService;
use malpaso\LaravelAxcelerate\Services\CoursesService;
use malpaso\LaravelAxcelerate\Tests\TestCase;

class LaravelAxcelerateTest extends TestCase
{
    public function test_it_can_be_instantiated(): void
    {
        $config = [
            'base_url' => 'https://example.axcelerate.com',
            'ws_token' => 'test-ws-token',
            'api_token' => 'test-api-token',
        ];

        $client = new Client($config);
        $coursesService = new CoursesService($client);
        $courseService = new CourseService($client);
        $axcelerate = new LaravelAxcelerate($client, $coursesService, $courseService);

        $this->assertInstanceOf(LaravelAxcelerate::class, $axcelerate);
    }

    public function test_it_returns_http_client(): void
    {
        $config = [
            'base_url' => 'https://example.axcelerate.com',
            'ws_token' => 'test-ws-token',
            'api_token' => 'test-api-token',
        ];

        $client = new Client($config);
        $coursesService = new CoursesService($client);
        $courseService = new CourseService($client);
        $axcelerate = new LaravelAxcelerate($client, $coursesService, $courseService);

        $this->assertInstanceOf(Client::class, $axcelerate->getClient());
        $this->assertSame($client, $axcelerate->getClient());
    }

    public function test_it_returns_course_services(): void
    {
        $config = [
            'base_url' => 'https://example.axcelerate.com',
            'ws_token' => 'test-ws-token',
            'api_token' => 'test-api-token',
        ];

        $client = new Client($config);
        $coursesService = new CoursesService($client);
        $courseService = new CourseService($client);
        $axcelerate = new LaravelAxcelerate($client, $coursesService, $courseService);

        $this->assertSame($coursesService, $axcelerate->courses());
        $this->assertSame($courseService, $axcelerate->course());
    }
}
