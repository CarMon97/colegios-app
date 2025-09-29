<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\MunicipalityController;
use App\Http\Controllers\Api\SchoolGradeController;
use App\Http\Controllers\Api\SchoolSheduleController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TypeDocumentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login'])->name('login');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/departments', [DepartmentController::class, 'index']);

    Route::get('/municipalities/{departmentId}', [MunicipalityController::class, 'index']);

    Route::group(['prefix' => 'users'], function () {
        Route::post('create-admin', [UserController::class, 'createAdmin'])->middleware('role:admin,Rector');
        Route::post('create-student', [UserController::class, 'createStudent']);
        Route::post('create-teacher', [UserController::class, 'createTeacher']);
        Route::post('create-director', [UserController::class, 'createDirector']);
        Route::get('get-teachers', [UserController::class, 'getTeachers']);
        Route::post('create-student-massive', [UserController::class, 'createStudentMassive']);
        Route::get('get-students', [UserController::class, 'getAllStudents']);
        Route::post('upload-photo', [UserController::class, 'uploadPhoto']);
        Route::get('get-documents-types', [TypeDocumentController::class, 'index']);
        Route::get('get-student-by-id/{id}', [UserController::class, 'getStudentById']);
        Route::get('get-students-without-groups', [UserController::class, 'getStudentsWithoutGroups']);
    });

    Route::group(['prefix' => 'groups'], function () {
        Route::get('get-all-school-grades', [SchoolGradeController::class, 'index']);
        Route::get('get-active-groups', [SchoolGradeController::class, 'getActiveGroups']);
        Route::post('create-group', [SchoolGradeController::class, 'createGroup']);
        Route::get('get-group-by-id/{id}', [SchoolGradeController::class, 'getGroupById']);
        Route::post('assign-students-to-group/{id}', [SchoolGradeController::class, 'assignStudentsToGroup']);
    });

    Route::group(['prefix' => 'subjects'], function () {
        Route::get('get-all-subjects', [SubjectController::class, 'index']);
        Route::post('create-subject', [SubjectController::class, 'create']);
    });

    Route::group(['prefix' => 'schedules'], function () {
        Route::post('create-schedule', [SchoolSheduleController::class, 'create']);
    });
});
