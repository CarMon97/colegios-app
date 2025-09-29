<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $table = 'school_years';
    protected $fillable = ['year', 'start_date', 'end_date', 'status'];

    public function academicPeriods()
    {
        return $this->hasMany(AcademicPeriod::class);
    }

    public function schoolGroups()
    {
        return $this->hasMany(SchoolGroup::class);
    }
}
