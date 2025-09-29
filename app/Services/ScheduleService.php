<?php

namespace App\Services;

use App\Models\SchoolShedule;
use Illuminate\Database\Eloquent\Casts\Json;

class ScheduleService
{
    public function create($data)
    {
        $groupId = $data['group_id'];
        $sheduleData = Json::decode($data['schedule']);

        foreach ($sheduleData['days'] as $day => $dayData){
            foreach ($dayData as $schedule){
                
                // Validar que no exista un horario exactamente igual
                $exactSchedule = SchoolShedule::where('day', $day)
                    ->where('start_time', $schedule['start_time'])
                    ->where('end_time', $schedule['end_time'])
                    ->where('subject_id', $schedule['subject_id'])
                    ->where('teacher_id', $schedule['teacher_id'])
                    ->where('school_group_id', $groupId)
                    ->where('status', true)
                    ->first();
                
                if($exactSchedule){
                    continue; // Ya existe este horario exacto
                }

                // Validar conflictos de horarios para el profesor
                $teacherConflict = SchoolShedule::conflict($day, $schedule['start_time'], $schedule['end_time'])
                    ->where('teacher_id', $schedule['teacher_id'])
                    ->where('status', true)
                    ->first();

                if($teacherConflict){
                    throw new \Exception("El profesor ya tiene una clase programada en este horario: {$day} {$schedule['start_time']}-{$schedule['end_time']}");
                }

                // Validar conflictos de horarios para el grupo
                $groupConflict = SchoolShedule::conflict($day, $schedule['start_time'], $schedule['end_time'])
                    ->where('school_group_id', $groupId)
                    ->where('status', true)
                    ->first();

                if($groupConflict){
                    throw new \Exception("El grupo ya tiene una clase programada en este horario: {$day} {$schedule['start_time']}-{$schedule['end_time']}");
                }

                // Crear el nuevo horario
                $schoolShedule = new SchoolShedule();
                $schoolShedule->day = $day;
                $schoolShedule->start_time = $schedule['start_time'];
                $schoolShedule->end_time = $schedule['end_time'];
                $schoolShedule->subject_id = $schedule['subject_id'];
                $schoolShedule->teacher_id = $schedule['teacher_id'];
                $schoolShedule->school_group_id = $groupId;
                $schoolShedule->status = true;
                $schoolShedule->save();
            }
        }
    }
}

