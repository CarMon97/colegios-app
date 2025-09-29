<?php

namespace App\Services;

use App\Models\SchoolGrade;
use App\Models\SchoolGroup;
use App\Models\SchoolYear;
use App\Models\User;

class SchoolGradeService
{

    public function getAllGrades()
    {
        return SchoolGrade::all();
    }

    public function getAllGroups()
    {
        return SchoolGroup::all();
    }

    public function getActiveGroups()
    {
        return SchoolGroup::where('status', true)
            ->with('schoolGrade')
            ->with('schoolYear')
            ->get();
    }

    public function getGroupById($id)
    {
        return SchoolGroup::with(['schoolGrade', 'schoolYear', 'students'])->find($id);
    }

    public function createGroup($data)
    {
        $grade = SchoolGrade::find($data['school_grade_id']);

        $count = SchoolGroup::where('school_grade_id', $grade->id)->count();

        $year = SchoolYear::where('status', true)->first();


        $newSchoolGroup = new SchoolGroup();
        $newSchoolGroup->name = $grade->name . '-' . $count + 1;
        $newSchoolGroup->school_grade_id = $grade->id;
        $newSchoolGroup->status = true;
        $newSchoolGroup->school_year_id = $year->id;
        $newSchoolGroup->save();

        return $newSchoolGroup;
    }


    public function assignStudentsToGroup($data, $id)
    {
        $group = SchoolGroup::find($id);
        $students = User::whereIn('id', $data['students_id'])->get();
        $group->students()->attach($students, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return $group->students;
    }
}
