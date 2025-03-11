<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name', 'description', 'duration', 'level', 'status', 'category_id', 'sub_category_id'
    ];

    public function category(){
        return $this->belonsTo(Category::class);
    }

    public function subCategory(){
        return $this->belonsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
