<?php

namespace malpaso\LaravelAxcelerate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \malpaso\LaravelAxcelerate\LaravelAxcelerate
 *
 * @method static \malpaso\LaravelAxcelerate\Http\Client getClient()
 * @method static array testConnection()
 *
 * // Courses Service Methods
 * @method static array getCourses(array $parameters = [])
 *
 * // Course Service Methods
 * @method static array getCourseDetail(array $parameters = [])
 * @method static array createCourse(array $data)
 * @method static array getCourseInstances(array $parameters = [])
 * @method static array getCourseInstanceDetail(array $parameters = [])
 * @method static array createCourseInstance(array $data)
 * @method static array updateCourseInstance(array $data)
 * @method static array searchCourseInstances(array $data)
 * @method static array enrolInCourse(array $data)
 * @method static array enrolMultipleInCourse(array $data)
 * @method static array getCourseEnrolments(array $parameters = [])
 * @method static array updateCourseEnrolment(array $data)
 * @method static array getCourseDiscounts(array $parameters = [])
 * @method static array enquireAboutCourse(array $data)
 * @method static array getCourseCalendar(array $parameters = [])
 * @method static array getCourseLocations(array $parameters = [])
 * @method static array getCourseResources(array $parameters = [])
 * @method static array getCourseAttendance(array $parameters = [])
 * @method static array setCourseAttendance(array $data)
 * @method static array getCourseComplexDates(int $instanceId)
 * @method static array setCourseComplexDate(int $instanceId, array $data)
 * @method static array getCourseExtraTrainer(int $instanceId)
 * @method static array addCourseExtraTrainer(int $instanceId, array $data)
 * @method static array updateCourseExtraTrainer(int $instanceId, array $data)
 * @method static array deleteCourseExtraTrainer(int $instanceId)
 *
 * // Service Getters
 * @method static \malpaso\LaravelAxcelerate\Services\Contracts\CoursesServiceInterface courses()
 * @method static \malpaso\LaravelAxcelerate\Services\Contracts\CourseServiceInterface course()
 */
class LaravelAxcelerate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \malpaso\LaravelAxcelerate\LaravelAxcelerate::class;
    }
}
