<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    protected $table = 'academic_periods';
    protected $fillable = ['name', 'start_date', 'end_date', 'school_year_id', 'status'];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function periodGrades()
    {
        return $this->hasMany(PeriodGrade::class);
    }
}
