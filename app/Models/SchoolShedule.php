<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolShedule extends Model
{
    protected $table = 'school_schedules';
    protected $fillable = ['day', 'start_time', 'end_time', 'subject_id', 'teacher_id', 'school_group_id', 'status'];

    public function schoolGroup()
    {
        return $this->belongsTo(SchoolGroup::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeConflict($query, $day, $start, $end)
    {
        return $query->where('day', $day)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($sub) use ($start, $end) {
                        $sub->where('start_time', '<=', $start)
                            ->where('end_time', '>=', $end);
                    });
            });
    }
}
