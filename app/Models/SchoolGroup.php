<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolGroup extends Model
{
    protected $table = 'school_groups';
    protected $fillable = ['name', 'school_year_id', 'status', 'school_grade_id'];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
    
    public function schoolGrade()
    {
        return $this->belongsTo(SchoolGrade::class);
    }

    public function schoolShedule()
    {
        return $this->hasMany(SchoolShedule::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    
    public function grades(){
        return $this->hasMany(Grade::class);
    }
    
    public function students(){
        return $this->belongsToMany(User::class, 'school_groups_students', 'school_group_id', 'student_id');
    }
}
