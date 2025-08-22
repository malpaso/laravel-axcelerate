<?php

namespace malpaso\LaravelAxcelerate\Exceptions;

class CourseException extends AxcelerateException
{
    public static function invalidCourseType(string $type): self
    {
        return new self("Invalid course type '{$type}'. Must be one of: w (workshop), p (program), el (e-learning)");
    }

    public static function enrollmentNotAllowed(string $type): self
    {
        return new self("Enrollment operations are not allowed for course type '{$type}'");
    }

    public static function multipleEnrollmentNotAllowed(): self
    {
        return new self('Multiple enrollment is only allowed for workshop type (w)');
    }

    public static function requiredFieldMissing(string $field): self
    {
        return new self("Required field '{$field}' is missing or empty");
    }

    public static function invalidDateFormat(string $field): self
    {
        return new self("Field '{$field}' must be in format 'YYYY-MM-DD' or 'YYYY-MM-DD hh:mm'");
    }

    public static function attendanceNotSupported(): self
    {
        return new self('Cannot set attendance records against workshops that do not have Sessions');
    }
}
