<?php

namespace malpaso\LaravelAxcelerate\Tests\Unit\Services;

use malpaso\LaravelAxcelerate\Http\Client;
use malpaso\LaravelAxcelerate\Services\CourseService;
use malpaso\LaravelAxcelerate\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CourseServiceTest extends TestCase
{
    private CourseService $courseService;

    private MockObject $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = $this->createMock(Client::class);
        $this->courseService = new CourseService($this->mockClient);
    }

    public function test_get_detail(): void
    {
        $parameters = ['type' => 'w', 'id' => 123];
        $expectedResponse = ['course' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('course/detail', $parameters)
            ->willReturn($expectedResponse);

        $result = $this->courseService->getDetail($parameters);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_create(): void
    {
        $data = ['name' => 'Test Course'];
        $expectedResponse = ['courseID' => 123];

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with('course/', $data)
            ->willReturn($expectedResponse);

        $result = $this->courseService->create($data);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_get_instances(): void
    {
        $parameters = ['type' => 'w', 'current' => true];
        $expectedQueryParams = ['type' => 'w', 'current' => 'true'];
        $expectedResponse = ['instances' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('course/instances', $expectedQueryParams)
            ->willReturn($expectedResponse);

        $result = $this->courseService->getInstances($parameters);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_enrol_with_valid_data(): void
    {
        $data = [
            'contactID' => 123,
            'type' => 'w',
            'instanceID' => 456,
        ];
        $expectedResponse = ['enrolmentID' => 789];

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with('course/enrol', $data)
            ->willReturn($expectedResponse);

        $result = $this->courseService->enrol($data);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_enrol_throws_exception_for_missing_required_fields(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Required field 'contactID' is missing or empty");

        $this->courseService->enrol([
            'type' => 'w',
            'instanceID' => 456,
        ]);
    }

    public function test_enrol_multiple_with_valid_data(): void
    {
        $data = [
            'payerContactID' => 123,
            'type' => 'w',
            'instanceID' => 456,
            'students' => [789, 101112],
        ];
        $expectedResponse = ['enrolmentIDs' => [789, 101112]];

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with('course/enrolMultiple', $data)
            ->willReturn($expectedResponse);

        $result = $this->courseService->enrolMultiple($data);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_enrol_multiple_throws_exception_for_non_workshop_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Multiple enrollment is only allowed for workshop type (w)');

        $this->courseService->enrolMultiple([
            'payerContactID' => 123,
            'type' => 'p',
            'instanceID' => 456,
            'students' => [789],
        ]);
    }

    public function test_get_enrolments_validates_course_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Enrolments endpoint only works with type "p" (program) or "w" (workshop)');

        $this->courseService->getEnrolments(['type' => 'el']);
    }

    public function test_get_enrolments_with_valid_type(): void
    {
        $parameters = ['type' => 'w', 'instanceID' => 123];
        $expectedResponse = ['enrolments' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('course/enrolments', $parameters)
            ->willReturn($expectedResponse);

        $result = $this->courseService->getEnrolments($parameters);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_validate_course_type_throws_exception_for_invalid_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Course type must be one of: w (workshop), p (program), el (e-learning)');

        $this->courseService->getDetail(['type' => 'invalid']);
    }

    public function test_get_complex_dates(): void
    {
        $instanceId = 123;
        $expectedResponse = ['sessions' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('course/instance/complexdate/123')
            ->willReturn($expectedResponse);

        $result = $this->courseService->getComplexDates($instanceId);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_set_complex_date(): void
    {
        $instanceId = 123;
        $data = ['sessions' => []];
        $expectedResponse = ['success' => true];

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with('course/instance/complexdate/123', $data)
            ->willReturn($expectedResponse);

        $result = $this->courseService->setComplexDate($instanceId, $data);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_get_extra_trainer(): void
    {
        $instanceId = 123;
        $expectedResponse = ['trainers' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('course/instance/extratrainer/123')
            ->willReturn($expectedResponse);

        $result = $this->courseService->getExtraTrainer($instanceId);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_add_extra_trainer(): void
    {
        $instanceId = 123;
        $data = ['trainerID' => 456];
        $expectedResponse = ['success' => true];

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with('course/instance/extratrainer/123', $data)
            ->willReturn($expectedResponse);

        $result = $this->courseService->addExtraTrainer($instanceId, $data);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_update_extra_trainer(): void
    {
        $instanceId = 123;
        $data = ['trainerID' => 456];
        $expectedResponse = ['success' => true];

        $this->mockClient
            ->expects($this->once())
            ->method('put')
            ->with('course/instance/extratrainer/123', $data)
            ->willReturn($expectedResponse);

        $result = $this->courseService->updateExtraTrainer($instanceId, $data);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_delete_extra_trainer(): void
    {
        $instanceId = 123;
        $expectedResponse = ['success' => true];

        $this->mockClient
            ->expects($this->once())
            ->method('delete')
            ->with('course/instance/extratrainer/123')
            ->willReturn($expectedResponse);

        $result = $this->courseService->deleteExtraTrainer($instanceId);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_search_instances(): void
    {
        $data = ['name' => 'Test Course'];
        $expectedResponse = ['instances' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with('course/instance/search', $data)
            ->willReturn($expectedResponse);

        $result = $this->courseService->searchInstances($data);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_enquire(): void
    {
        $data = [
            'contactID' => 123,
            'message' => 'Test enquiry',
        ];
        $expectedResponse = ['success' => true];

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with('course/enquire', $data)
            ->willReturn($expectedResponse);

        $result = $this->courseService->enquire($data);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_get_calendar(): void
    {
        $parameters = ['start' => '2023-12-01', 'end' => '2023-12-31'];
        $expectedResponse = ['events' => []];

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with('course/calendar', $parameters)
            ->willReturn($expectedResponse);

        $result = $this->courseService->getCalendar($parameters);

        $this->assertEquals($expectedResponse, $result);
    }
}
