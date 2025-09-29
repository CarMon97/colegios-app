<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolGroup;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolSheduleController extends Controller
{
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }   
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->scheduleService->create($request->all());
            $grupo = SchoolGroup::find($request->group_id);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Horario creado correctamente para el grupo ' . $grupo->name], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
