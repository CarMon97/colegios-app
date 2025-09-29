<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SchoolGradeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SchoolGradeController extends Controller
{
    private $schoolGradeService;

    public function __construct(SchoolGradeService $schoolGradeService){
        $this->schoolGradeService = $schoolGradeService;
    }

    public function index(){
        try {
            return response()->json(['success' => true, 'data' => $this->schoolGradeService->getAllGrades()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createGroup(Request $request){
        $validator = Validator::make($request->all(), [
            'school_grade_id' => 'required|exists:school_grades,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            return response()->json(['success' => true, 'data' => $this->schoolGradeService->createGroup($request->all())], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllGroups(){
        try {
            return response()->json(['success' => true, 'data' => $this->schoolGradeService->getAllGroups()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getActiveGroups(){
        try {
            return response()->json(['success' => true, 'data' => $this->schoolGradeService->getActiveGroups()], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener los grupos activos: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getGroupById($id){
        //return response()->json(['success' => true, 'data' => $id ], 200);
        try {
            return response()->json(['success' => true, 'data' => $this->schoolGradeService->getGroupById($id)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function assignStudentsToGroup(Request $request, $id){
        try {
            $data = $this->schoolGradeService->assignStudentsToGroup($request->all(), $id);
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}
