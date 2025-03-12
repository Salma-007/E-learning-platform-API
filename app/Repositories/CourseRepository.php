<?php

namespace App\Repositories;

use App\Models\Course;
use App\Interfaces\CourseInterface;
use App\Http\Resources\CourseResource;

class CourseRepository implements CourseInterface
{
    public function getAll()
    {
        // $courses = Course::with(['category', 'subCategory'])->get();
        // return CourseResource::collection($courses);
        return Course::all();
    }

}