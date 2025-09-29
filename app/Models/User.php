<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'identification',
        'phone',
        'address',
        'gender',
        'birth_date',
        'avatar',
        'email',
        'password',
        'type_document_id',
        'municipality_id',
    ];



    public function typeDocument()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    // Métodos requeridos por JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // normalmente el id del usuario
    }

    public function getJWTCustomClaims()
    {
        return []; // aquí puedes añadir datos extras al token
    }

    public function attendanceStudent(){
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function attendanceTeacher(){
        return $this->hasMany(Attendance::class, 'teacher_id');
    }

    public function schoolShedule(){
        return $this->hasMany(SchoolShedule::class, 'teacher_id');
    }

    public function grades(){
        return $this->hasMany(Grade::class, 'student_id');
    }

    public function activities(){
        return $this->hasMany(Activity::class, 'teacher_id');
    }

    public function schoolGroups(){
        return $this->belongsToMany(SchoolGroup::class, 'school_groups_students', 'student_id', 'school_group_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'school_schedules', 'teacher_id', 'subject_id');
    }

    public function periodGrades()
    {
        return $this->hasMany(PeriodGrade::class, 'student_id');
    }
    
}
