<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodGrade extends Model
{
    protected $table = 'period_grades';
    protected $fillable = ['academic_period_id', 'student_id', 'final_grade', 'status'];

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class);
    }
}
