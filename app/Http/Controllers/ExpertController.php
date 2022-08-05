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

class ExpertController extends Controller
{
    public function incomingApplications()
    {
        $projects_exp = auth()->user()->expertiseUser()->where([
            ['grade', '=', null],
            ['comment', '=', null]
        ])->distinct()->pluck('project_id')->toArray();
        $projects = Project::find($projects_exp, ['id', 'project_owner_id', 'project_type_id', 'name', 'start_date', 'finish_date', 'updated_at']);
        foreach ($projects as $project){
            $project['owner'] = $project->owner()->get(['surname', 'name', 'patronymic'])->first();
            $project['project_type'] = $project->projectype()->get('name')->first()->name;
            $last_status = $project->projestatuses()->latest()->first()->statusproject()->get('name')->first();
            $project['status'] = $last_status->name;
            $for_project_role = new Collection();
            $project_roles = $project->projectroles()->get();
            foreach ($project_roles as $key){
                $role = $key->projectSubR()->get(['id', 'name', 'subject_area_id'])->first();
                $role['number_seats'] = $key->number_seats;
                $for_project_role->push($role);
            }
            $project['project_roles'] = $for_project_role;
            $project['subject_area'] = $project->subjectAreas()->get(['id', 'name'])->map(function ($value) {
                unset($value->pivot);
                return  $value;
            });
        }
        $projects = $projects->map(function ($value) {
            unset($value->project_owner_id);
            unset($value->project_type_id);
            return  $value;
        });
        return response()->json($projects);
    }

    public function evaluatedApplications()
    {
        $projects_exp = auth()->user()->expertiseUser()->where([
            ['grade', '!=', null],
            ['comment', '!=', null]
        ])->distinct()->pluck('project_id')->toArray();
        $projects = Project::find($projects_exp, ['id', 'project_owner_id', 'project_type_id', 'name', 'start_date', 'finish_date', 'updated_at']);
        foreach ($projects as $project){
            $project['owner'] = $project->owner()->get(['surname', 'name', 'patronymic'])->first();
            $project['project_type'] = $project->projectype()->get('name')->first()->name;
            $last_status = $project->projestatuses()->latest()->first()->statusproject()->get('name')->first();
            $project['status'] = $last_status->name;
            $for_project_role = new Collection();
            $project_roles = $project->projectroles()->get();
            foreach ($project_roles as $key){
                $role = $key->projectSubR()->get(['id', 'name', 'subject_area_id'])->first();
                $role['number_seats'] = $key->number_seats;
                $for_project_role->push($role);
            }
            $project['project_roles'] = $for_project_role;
            $project['subject_area'] = $project->subjectAreas()->get(['id', 'name'])->map(function ($value) {
                unset($value->pivot);
                return  $value;
            });
        }
        $projects = $projects->map(function ($value) {
            unset($value->project_owner_id);
            unset($value->project_type_id);
            return  $value;
        });
        return response()->json($projects);
    }

    public function sendFeedback(Request $request, $id)
    {
        $project_exp = auth()->user()->expertiseUser()->find($id);

        if ($project_exp){
            if ($project_exp['grade'] == null &&
                $project_exp['comment'] == null) {

                $validate = $request->validate([
                    'grade'            => 'required',
                    'comment'          => 'required',
                ]);
                $project_exp->update([
                    'grade' => $validate['grade'],
                    'comment' => $validate['comment'],
                    'datetime'=> Carbon::now()
                ]);

                $counter = 0;
                $all_project_expertise = $project_exp->checkingProjects()->get()->first()->projectExpertise()->get();
                foreach ($all_project_expertise as $expertise){
                    if ($expertise['grade'] == null &&
                        $expertise['comment'] == null){
                        $counter++;
                    }
                }
                if($counter == 0){
                    $statuses = $this->allStatuses();
                    $this->addStatus($project_exp->project_id, $statuses[4]);
                }
            }else {return [];}
        }else {return [];}
        return response()->json([]);
    }

    public function addStatus($project_id, $id_status): JsonResponse
    {
        $project_status = new ProjectStatus();
        $project_status->project_id = $project_id;
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
