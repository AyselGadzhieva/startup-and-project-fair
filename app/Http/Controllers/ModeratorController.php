<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Project;
use App\Models\SubjectArea;
use App\Models\Expertise;
use App\Models\ProjectType;
use App\Models\Status;
use App\Models\ProjectStatus;
use App\Models\ProjectRole;
use App\Models\ObjectiveProject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ModeratorController extends Controller
{
    public function forModeration()
    {
        $projectsall = Project::all(['id', 'project_owner_id', 'project_type_id', 'name', 'start_date', 'finish_date', 'updated_at']);
        $projectsmod = new Collection();
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
            if ($last_status == "Отправлена заявка"){
                $projectsmod->push($project);
            }
        }
        return response()->json($projectsmod);
    }

    public function takeForModeration($id)
    {
        $user = auth()->user();
        $project = Project::find($id);
        $checkRole = $user->hasRole('moderator');
        $statuses = $this->allStatuses();
        if ($checkRole){
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

    public function rejectProject($id)
    {
        $user = auth()->user();
        $project_mod = $user->projectsmoderator()->find($id);
        $checkRole = $user->hasRole('moderator');
        $statuses = $this->allStatuses();
        $last_status = $project_mod->projestatuses()->latest()->first()->statusproject()->get();
        foreach ($last_status as $key){ $name = $key->name;}
        if ($checkRole && $project_mod){
            if ($name == "В обработке"){
                $this->addStatus($project_mod, $statuses[10]);
            }
        }
        else{
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

    //проверить данные (возможно необхомо добавить)
    public function showProjectsModerator()
    {
        $projects_under_moderation = auth()->user()->projectsmoderator()
            ->get(['id', 'project_owner_id', 'project_type_id', 'name', 'start_date', 'finish_date']);
        if (!$projects_under_moderation){
            return response()->json([
                'message' => 'У вас нет модерируемых проектов'
            ]);
        }
        else{
            foreach ($projects_under_moderation as $project){
//                $project['owner'] = $project->owner()->get(['surname', 'name', 'patronymic'])->first();
//                $project['project_type'] = $project->projectype()->get('name')->first()->name;
//                $last_status = $project->projestatuses()->latest()->first()->statusproject()->get('name')->first();
//                $project['status'] = $last_status->name;
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
            }
        }
        return response()->json($projects_under_moderation);
    }

    public function sendExamination(Request $request, $id)
    {
        $id_experts = array_values($request->id);
        $user = auth()->user();
        $checkRole = $user->hasRole('admin');
        $projectMod = $user->projectsmoderator()->find($id);
        $statuses = $this->allStatuses();
        if ($projectMod || $checkRole){
            $last_status = $projectMod->projestatuses()->latest()->first()->statusproject()->get('name')->first()->name;
            if ($last_status == "В обработке" || $last_status == "На экспертизе" || $last_status == "Оценен"){
                foreach ($id_experts as $id_expert) {
                    $expertise = new Expertise();
                    $expertise->project_id = $projectMod->id;
                    $expertise->user_id = $id_expert;
                    $expertise->save();
                    $last_status = $projectMod->projestatuses()->latest()->first()->statusproject()->get('name')->first()->name;
                    if ($last_status == "В обработке"){
                        $project_status = new ProjectStatus();
                        $project_status->project_id = $projectMod->id;
                        $project_status->status_id = $statuses[3];
                        $project_status->datetime = Carbon::now();
                        $project_status->save();
                    }
                }
            }
            else{
                return response()->json(['message' => 'Проект не может быть отправлен на экспертизу!']);
            }
        }
        return response()->json([]);
    }

    public function getExperts($id)
    {
        $project = Project::find($id);
        $subject_areas_project = $project->subjectAreas()->distinct()->pluck('id')->toArray();

        $users = DB::table('user_subject_areas')
            ->join('users', 'users.id', '=', 'user_subject_areas.user_id')
            ->join('subject_areas', 'subject_areas.id', '=', 'user_subject_areas.subject_area_id')
            ->select('users.id as id', 'users.surname as surname',
                'users.patronymic as patronymic',
                'users.name as name', 'subject_areas.id as sub_id', 'subject_areas.name as sub_name')
            ->whereIn('subject_area_id', $subject_areas_project)
            ->where('role_id', '=', 3)
            ->get();

        $users = $users->map(function ($value) {
            $value->subject_area = ['id' => $value->sub_id, 'name' => $value->sub_name];
            unset($value->sub_id);
            unset($value->sub_name);
            return  $value;
        });


        return response()->json($users);
    }

    public function sendToCurators($id)
    {
        $project_mod = auth()->user()->projectsmoderator()->find($id);
        $statuses = $this->allStatuses();
        if($project_mod){
            $last_status = $project_mod->projestatuses()->latest()->first()->statusproject()->get('name')->first()->name;
            if ($last_status == "Оценен"){
                $this->addStatus($project_mod, $statuses[5]);
            }
        }
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
