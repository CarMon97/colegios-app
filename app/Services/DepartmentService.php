<?php

namespace App\Services;

use App\Models\Department;

class DepartmentService{


    public function getAllDepartments(){
        return Department::all();
    }

    
}