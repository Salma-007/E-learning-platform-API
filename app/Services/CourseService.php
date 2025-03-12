<?php

namespace App\Services;

use App\Interfaces\CourseInterface;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function listCourses()
    {
        return $this->courseRepository->getAll();
    }

}