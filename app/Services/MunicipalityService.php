<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Municipality;

class MunicipalityService{

    public function getMunicipalitiesByDepartmentId($departmentId){
        return Municipality::where('department_id', $departmentId)->get();
    }
}
