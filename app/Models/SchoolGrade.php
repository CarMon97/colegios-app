<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolGrade extends Model
{
    protected $table = 'school_grades';
    protected $fillable = ['name'];

    public function schoolGroups()
    {
        return $this->hasMany(SchoolGroup::class);
    }
}
