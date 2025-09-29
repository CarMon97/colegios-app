<?php

namespace App\Services;

use App\Imports\UsersImport;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserService
{

    public function createAdmin($data)
    {
        $user = $this->createUser($data);
        if ($user) {
            $user->assignRole('admin');
        }
        return $user;
    }

    public function createTeacher($data)
    {
        $user = $this->createUser($data);
        if ($user) {
            $user->assignRole('Docente');
        }

        return $user;
    }

    public function createStudent($data)
    {
        $user = $this->createUser($data);
        if ($user) {
            $user->assignRole('Estudiante');
        }
        return $user;
    }

    public function createRector($data)
    {
        $user = $this->createUser($data);
        if ($user) {
            $user->assignRole('Rector');
        }
        return $user;
    }
    public function createCoordinator($data)
    {
        $user = $this->createUser($data);
        if ($user) {
            $user->assignRole('Coordinador');
        }
        return $user;
    }


    public function createSecretary($data)
    {
        $user = $this->createUser($data);
        if ($user) {
            $user->assignRole('Secretario');
        }
        return $user;
    }

    public function createStudentMassive($data)
    {
        Excel::import(new UsersImport(), $data['file']);

        return ['message' => 'Estudiantes creados correctamente'];
    }


    public function getTeachers()
    {
        return User::where('status', true)
            ->where('status', true)
            ->role('Docente')
            ->get();
    }
    private function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'identification' => $data['identification'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'birth_date' => $data['birth_date'],
            'avatar' => ($data['avatar'] ?? null),
            'email' => $data['email'],
            'password' => Hash::make($data['identification']),
            'type_document_id' => $data['type_document_id'],
            'municipality_id' => $data['municipality_id'],
        ]);

        return $user;
    }

    public function login($data)
    {
        // Verificar si el usuario existe por email
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return ['error' => 'El correo electrónico no está registrado'];
        }

        // Verificar si la contraseña es correcta
        if (!Hash::check($data['password'], $user->password)) {
            return ['error' => 'La contraseña es incorrecta'];
        }

        // Si llegamos aquí, las credenciales son correctas
        if (! $token = Auth::guard('api')->attempt($data)) {
            return ['error' => 'Error al generar el token de autenticación'];
        }

        return [
            'token' => $token,
            'user' => Auth::guard('api')->user(),
        ];
    }

    public function getAllStudents($perPage = 15, $page = 1)
    {
        // Usar paginate() de Laravel para obtener datos paginados
        $paginatedUsers = User::where('status', true)
            ->role('Estudiante')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'estudiantes' => $paginatedUsers->items(), // Los elementos de la página actual
            'cantidad' => $paginatedUsers->total(), // Total de registros
            'pagination' => [
                'current_page' => $paginatedUsers->currentPage(),
                'per_page' => $paginatedUsers->perPage(),
                'total' => $paginatedUsers->total(),
                'last_page' => $paginatedUsers->lastPage(),
                'from' => $paginatedUsers->firstItem(),
                'to' => $paginatedUsers->lastItem(),
                'has_more_pages' => $paginatedUsers->hasMorePages(),
                'prev_page_url' => $paginatedUsers->previousPageUrl(),
                'next_page_url' => $paginatedUsers->nextPageUrl(),
            ]
        ];
    }

    public function logout()
    {
        try {
            // Verificar si hay un token válido antes de intentar hacer logout
            if (Auth::guard('api')->check()) {
                Auth::guard('api')->logout(true); // true invalida el token
                return ['message' => 'Sesión cerrada correctamente'];
            } else {
                return ['message' => 'No hay sesión activa'];
            }
        } catch (\Exception $e) {
            return ['error' => 'Error al cerrar sesión: ' . $e->getMessage()];
        }
    }

    public function uploadPhoto($data)
    {
        $user = User::find(Auth::guard('api')->user()->id);
        $photo = $data['photo'];
        $filename = $user->identification . '.' . $photo->getClientOriginalExtension();

        // Guarda el archivo en storage/app/public/photos/{identification}/
        $path = $photo->storeAs(
            'photos/' . $user->identification, // carpeta
            $filename,                         // nombre del archivo
            'public'                           // disco donde se guarda
        );

        // Genera automáticamente la URL accesible vía /storage
        $user->avatar = Storage::url($path);
        // Esto devolverá: /storage/photos/{identification}/{archivo}.jpg

        $user->save();

        return $user;
    }

    public function getStudentbyId($id)
    {
        // Obtener el año escolar activo (el más reciente)
        $activeSchoolYear = \App\Models\SchoolYear::orderBy('year', 'desc')->first();

        if (!$activeSchoolYear) {
            return ['error' => 'No hay año escolar configurado'];
        }

        // Obtener el estudiante con sus grupos escolares del año activo
        $student = User::where('status', true)
            ->role('Estudiante')
            ->with(['schoolGroups' => function ($query) use ($activeSchoolYear) {
                $query->where('status', true)
                    ->where('school_year_id', $activeSchoolYear->id)
                    ->with(['schoolYear', 'schoolGrade']);
            }])
            ->find($id);

        if (!$student) {
            return ['error' => 'Estudiante no encontrado'];
        }

        // Obtener el grupo actual del año escolar activo
        $currentGroup = $student->schoolGroups
            ->where('status', true)
            ->where('school_year_id', $activeSchoolYear->id)
            ->first();

        return [
            'estudiante' => $student,
            'grupo_actual' => $currentGroup,
            'año_escolar_activo' => $activeSchoolYear,
            'todos_los_grupos' => $student->schoolGroups->where('status', true)
        ];
    }

    /**
     * Obtiene el grupo escolar actual de un estudiante para un año específico
     */
    public function getStudentCurrentGroup($studentId, $schoolYearId = null)
    {
        // Si no se especifica año escolar, usar el activo
        if (!$schoolYearId) {
            $activeSchoolYear = SchoolYear::orderBy('year', 'desc')->first();
            if (!$activeSchoolYear) {
                return ['error' => 'No hay año escolar configurado'];
            }
            $schoolYearId = $activeSchoolYear->id;
        }

        $student = User::where('status', true)
            ->role('Estudiante')
            ->with(['schoolGroups' => function ($query) use ($schoolYearId) {
                $query->where('status', true)
                    ->where('school_year_id', $schoolYearId)
                    ->with(['schoolYear', 'schoolGrade']);
            }])
            ->find($studentId);

        if (!$student) {
            return ['error' => 'Estudiante no encontrado'];
        }

        $currentGroup = $student->schoolGroups
            ->where('status', true)
            ->where('school_year_id', $schoolYearId)
            ->first();

        return [
            'estudiante' => $student,
            'grupo_actual' => $currentGroup,
        ];
    }

    public function getStudentsWithoutGroups()
    {
        return User::where('status', true)
            ->role('Estudiante')
            ->whereDoesntHave('schoolGroups')
            ->get();
    }
}
