<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';
    protected $fillable = ['name', 'description', 'subject_id', 'percentage_equivalence', 'school_group_id', 'teacher_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolGroup()
    {
        return $this->belongsTo(SchoolGroup::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }
    
}
