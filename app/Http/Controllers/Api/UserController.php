<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createAdmin(Request $request)
    {
        // Validación adicional de seguridad (aunque el middleware ya valida)
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Debe iniciar sesión.'
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->hasRole('Rector')) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores y rectores pueden crear administradores.'
            ], 403);
        }

        $this->validateUser($request);

        try {
            DB::beginTransaction();

            $this->userService->createAdmin($request->all());
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Administrador creado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createStudent(Request $request)
    {

      //  return response()->json(['success' => true, $request->all()], 200);
        $this->validateUser($request);

        DB::beginTransaction();
        try {

            $this->userService->createStudent($request->all());
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Estudiante creado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function createTeacher(Request $request)
    {

        $this->validateUser($request);

        DB::beginTransaction();
        try {

            $teacher = $this->userService->createTeacher($request->all());
            DB::commit();
            Log::info('Docente creado correctamente', ['user' => $teacher->roles]);
            return response()->json(['success' => true, 'data' => $teacher], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function createDirector(Request $request)
    {
        $this->validateUser($request);

        try {
            DB::beginTransaction();

            $this->userService->createRector($request->all());
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Director creado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createStudentMassive(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        DB::beginTransaction();
        try {
            $this->userService->createStudentMassive($request->all());
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Estudiantes creados correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $data = $this->userService->login($request->all());
            Log::info('Inicio de sesión exitoso', ['user' => $data['user'], 'token' => $data['token']]);
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            Log::error('Error al iniciar sesión: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {
            $data = $this->userService->logout();

            if (isset($data['error'])) {
                return response()->json(['success' => false, 'error' => $data['error']], 500);
            }
            Log::info('Cierre de sesión exitoso', ['message' => $data['message']]);
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function validateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'identification' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'gender' => 'required|string',
            'birth_date' => 'required|date',
            'email' => 'required|email',
            'password' => 'required|string',
            'type_document_id' => 'required|exists:type_documents,id',
            'municipality_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    }

    public function getAllStudents(Request $request)
    {
        try {
   
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            
            $data = $this->userService->getAllStudents($perPage, $page);
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $data = $this->userService->uploadPhoto($request->all());
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getStudentById($id)
    {
        try {
            $data = $this->userService->getStudentbyId($id);
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getStudentsWithoutGroups()
    {
        try {
            $data = $this->userService->getStudentsWithoutGroups();
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTeachers(){
        try {
            $data = $this->userService->getTeachers();
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
