# Laravel Axcelerate

[![Latest Version on Packagist](https://img.shields.io/packagist/v/malpaso/laravel-axcelerate.svg?style=flat-square)](https://packagist.org/packages/malpaso/laravel-axcelerate)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/malpaso/laravel-axcelerate/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/malpaso/laravel-axcelerate/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/malpaso/laravel-axcelerate/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/malpaso/laravel-axcelerate/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/malpaso/laravel-axcelerate.svg?style=flat-square)](https://packagist.org/packages/malpaso/laravel-axcelerate)

A comprehensive Laravel package that provides a clean, expressive API wrapper for the Axcelerate training management system. This package allows you to easily integrate Axcelerate's RESTful API into your Laravel applications with proper error handling, validation, and Laravel-style syntax.

Axcelerate is a leading training management system used by educational institutions and training organizations. This package provides full access to course management, enrollments, contacts, and other Axcelerate features through a Laravel-friendly interface.

## Installation

You can install the package via composer:

```bash
composer require malpaso/laravel-axcelerate
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-axcelerate-config"
```

## Configuration

Add your Axcelerate API credentials to your `.env` file:

```env
AXCELERATE_BASE_URL=https://your-domain.axcelerate.com
AXCELERATE_WS_TOKEN=your-web-service-token
AXCELERATE_API_TOKEN=your-api-token
```

The published config file contains additional options:

```php
return [
    'base_url' => env('AXCELERATE_BASE_URL'),
    'ws_token' => env('AXCELERATE_WS_TOKEN'),
    'api_token' => env('AXCELERATE_API_TOKEN'),
    'timeout' => env('AXCELERATE_TIMEOUT', 30),
    'retry_attempts' => env('AXCELERATE_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('AXCELERATE_RETRY_DELAY', 1000),
    'log_requests' => env('AXCELERATE_LOG_REQUESTS', false),
];
```

> **Note:** Contact Axcelerate support to obtain your API tokens if you don't have them yet.

## Usage

### Basic Usage

Test your API connection:

```bash
php artisan axcelerate:test
```

### Course Management

```php
use malpaso\LaravelAxcelerate\Facades\LaravelAxcelerate;

// Get all courses
$courses = LaravelAxcelerate::getCourses();

// Get workshops only
$workshops = LaravelAxcelerate::getCourses(['type' => 'w']);

// Get current courses
$currentCourses = LaravelAxcelerate::getCourses([
    'type' => 'w',
    'current' => true,
    'public' => true
]);

// Get course details
$courseDetail = LaravelAxcelerate::getCourseDetail([
    'type' => 'w',
    'id' => 123
]);

// Get course instances
$instances = LaravelAxcelerate::getCourseInstances([
    'type' => 'w',
    'current' => true
]);
```

### Enrollments

```php
// Enroll a contact in a course
$enrollment = LaravelAxcelerate::enrolInCourse([
    'contactID' => 123,
    'type' => 'w',
    'instanceID' => 456,
    'generateInvoice' => true
]);

// Enroll multiple contacts in a workshop
$multipleEnrollment = LaravelAxcelerate::enrolMultipleInCourse([
    'payerContactID' => 123,
    'type' => 'w',
    'instanceID' => 456,
    'students' => [789, 101112]
]);

// Get course enrollments
$enrollments = LaravelAxcelerate::getCourseEnrolments([
    'type' => 'w',
    'instanceID' => 456
]);
```

### Advanced Features

```php
// Search course instances
$searchResults = LaravelAxcelerate::searchCourseInstances([
    'courseName' => 'Safety Training',
    'startAfter' => '2024-01-01',
    'location' => 'Sydney'
]);

// Get course calendar
$calendar = LaravelAxcelerate::getCourseCalendar([
    'start' => '2024-01-01',
    'end' => '2024-12-31'
]);

// Calculate course discounts
$discounts = LaravelAxcelerate::getCourseDiscounts([
    'type' => 'w',
    'instanceID' => 456,
    'contactID' => 123,
    'originalPrice' => 500
]);
```

### Using Service Classes Directly

```php
use malpaso\LaravelAxcelerate\Services\Contracts\CoursesServiceInterface;
use malpaso\LaravelAxcelerate\Services\Contracts\CourseServiceInterface;

// Inject via constructor or resolve from container
$coursesService = app(CoursesServiceInterface::class);
$courseService = app(CourseServiceInterface::class);

$courses = $coursesService->getCourses(['type' => 'w']);
$enrollment = $courseService->enrol([
    'contactID' => 123,
    'type' => 'w',
    'instanceID' => 456
]);
```

### Error Handling

```php
use malpaso\LaravelAxcelerate\Exceptions\AuthenticationException;
use malpaso\LaravelAxcelerate\Exceptions\ApiException;

try {
    $courses = LaravelAxcelerate::getCourses();
} catch (AuthenticationException $e) {
    // Handle authentication errors (invalid tokens)
    Log::error('Axcelerate authentication failed: ' . $e->getMessage());
} catch (ApiException $e) {
    // Handle API errors (server errors, validation errors, etc.)
    Log::error('Axcelerate API error: ' . $e->getMessage());
    $response = $e->getResponse(); // Get the original HTTP response
}
```

## Available Methods

### Courses Service
- `getCourses($parameters)` - List courses with filtering

### Course Service
- `getCourseDetail($parameters)` - Get course details
- `createCourse($data)` - Create a new course
- `getCourseInstances($parameters)` - Get course instances
- `getCourseInstanceDetail($parameters)` - Get instance details
- `createCourseInstance($data)` - Create course instance
- `updateCourseInstance($data)` - Update course instance
- `searchCourseInstances($data)` - Advanced instance search
- `enrolInCourse($data)` - Enroll contact in course
- `enrolMultipleInCourse($data)` - Enroll multiple contacts
- `getCourseEnrolments($parameters)` - Get course enrollments
- `updateCourseEnrolment($data)` - Update enrollment
- `getCourseDiscounts($parameters)` - Calculate discounts
- `enquireAboutCourse($data)` - Submit course enquiry
- `getCourseCalendar($parameters)` - Get course calendar
- `getCourseLocations($parameters)` - Get course locations
- `getCourseResources($parameters)` - Get course resources
- `getCourseAttendance($parameters)` - Get attendance records
- `setCourseAttendance($data)` - Set attendance records
- `getCourseComplexDates($instanceId)` - Get workshop sessions
- `setCourseComplexDate($instanceId, $data)` - Set workshop sessions
- `getCourseExtraTrainer($instanceId)` - Get extra trainers
- `addCourseExtraTrainer($instanceId, $data)` - Add extra trainer
- `updateCourseExtraTrainer($instanceId, $data)` - Update extra trainer
- `deleteCourseExtraTrainer($instanceId)` - Delete extra trainer

## Testing

```bash
composer test
```

## Course Types

The package supports three course types:
- `w` - Workshop
- `p` - Accredited Program  
- `el` - E-Learning

## Requirements

- PHP 8.3+
- Laravel 10.0+
- Guzzle HTTP 7.0+

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Bill Tindal](https://github.com/malpaso)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
