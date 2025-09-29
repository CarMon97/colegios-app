<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grades';
    protected $fillable = ['student_id', 'activity_id', 'grade', 'comment'];

    public function student()
    {
        return $this->belongsTo(User::class);
    }
    
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
