<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $fillable = ['name'];

    public function schoolShedule()
    {
        return $this->hasMany(SchoolShedule::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'school_schedules', 'subject_id', 'teacher_id');
    }
}
