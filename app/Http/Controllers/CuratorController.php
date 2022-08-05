<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Status;
use App\Models\ProjectStatus;
use App\Models\ProjectRole;
use App\Models\ObjectiveProject;
use App\Models\SubjectArea;
use App\Models\ProjectSubjectArea;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\MessageBag;
use Carbon\Carbon;

class CuratorController extends Controller
{
    public function poolForCurators()
    {
        $projectsall = Project::all(['id', 'project_owner_id', 'project_type_id', 'name', 'start_date', 'finish_date', 'updated_at']);
        $projects_pool_curators = new Collection();
        foreach ($projectsall as $project){
            $project['owner'] = $project->owner()->get(['surname', 'name', 'patronymic'])->first();
            $project['project_type'] = $project->projectype()->get('name')->first()->name;
            $last_status = $project->projestatuses()->latest()->first()->statusproject()->get('name')->first()->name;
            $project['status'] = $last_status;
            $for_project_role = new Collection();
            $project_roles = $project->projectroles()->get();
            foreach ($project_roles as $key){
                $role = $key->projectSubR()->get(['id', 'name', 'subject_area_id'])->first();
                $role['number_seats'] = $key->number_seats;
                $for_project_role->push($role);
            }
            $project['project_roles'] = $for_project_role;
            $project['subject_area'] = $project->subjectAreas()->get(['id', 'name']);
            if ($last_status == "Отправлен кураторам"){
                $projects_pool_curators->push($project);
            }
        }
        return response()->json($projects_pool_curators);
    }


    //тут остановились
    public function takeOnCuration($id)
    {
        $user = auth()->user();
        $project = Project::find($id);
        $checkRole = $user->hasRole('curator');
        $statuses = $this->allStatuses();
        if ($checkRole){
            $project_status = new ProjectStatus();
            $project->moderator_id = $user->id;
            $project->save();
            $this->addStatus($project, $statuses[2]);
        }else{
            return response()->json([
                'message' => 'Проект не найден'
            ], 400);
        }
        return response()->json();
    }


    public function addStatus($project, $id_status): JsonResponse
    {
        $project_status = new ProjectStatus();
        $project_status->project_id = $project->id;
        $project_status->status_id = $id_status;
        $project_status->datetime = Carbon::now();
        $project_status->save();
        return response()->json([]);
    }

    public function allStatuses()
    {
        $statuses = Status::get()->map(function ($status) {
            return $status->id;
        });
        return ($statuses);
    }
}
