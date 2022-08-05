<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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


class ProjectController extends Controller
{
    public function index()
    {//сделать вывод только проектов, со статусом ['Набор участников', 'В процессе', 'Завершён']

        $projects_active = new Collection();
        $projects = Project::all(['id', 'project_owner_id', 'project_type_id', 'name', 'start_date', 'finish_date', 'updated_at']);
        foreach ($projects as $project){

            $project['owner'] = $project->owner()->get(['id', 'surname', 'name', 'patronymic'])->first();
            $project['project_type'] = $project->projectype()->get('name')->first()->name;
            $last_status = $project->projestatuses()->latest()->first()->statusproject()->get('name')->first()->name;
            $project['status'] = $last_status;
            $for_project_role = new Collection();
            $project_roles = $project->projectroles()->get();
            foreach ($project_roles as $key){
                $subject_area_name = DB::table('subject_areas')
                    ->select('name')
                    ->where('id', $key->projectSubR()->get()->first()->subject_area_id)
                    ->get()->first();
                $role = $key->projectSubR()->get(['id', 'name'])->first();
                $role['number_seats'] = $key->number_seats;
                $role->subject_area = ['id' => $key->projectSubR()->get()->first()->subject_area_id,
                    'name' => $subject_area_name->name];
                $for_project_role->push($role);
            }
            $project['project_roles'] = $for_project_role;
            $project['subject_areas'] = $project->subjectAreas()->get(['id', 'name'])->map(function ($value) {
                unset($value->pivot);
                return  $value;
            });
        }
        return response()->json($projects);
    }

    public function createDraft(Request $request)
    {
        $validate = $request->validate([
            'name'                      => 'required',
            'description'               => 'required',
            'start_date'                => 'required|date',
            'finish_date'               => 'required|date',
            'remuneration'              => 'required',
            'project_type_id'           => 'required',

        ]);
        $user_id = auth()->id();
        $validate['project_owner_id'] = $user_id;
        $user_projects_count = Project::where('project_owner_id', '=', $user_id)->count();
        $maximum_number_of_projects = auth()->user()->maximum_number_of_projects;
        if ($maximum_number_of_projects <= $user_projects_count){
            return response()->json([
                'message' => 'Лимит на создание проектов превышен'
            ], 422);
        }
        $project_type_list = ProjectType::query()->get();
        foreach ($project_type_list as $project_type){
            $id[] = $project_type['id'];
        }
        if (!in_array($validate['project_type_id'], $id)){
            return response()->json([
                'message' => 'Тип проекта не найден'
            ], 422);
        } elseif (auth()->user()->hasRole('student')){
            $validate['project_type_id'] = 1;
        }

        $project = Project::query()->create($validate);

        $id = ($project->only(['id']))['id'];

        $statuses = $this->allStatuses();

        $this->addStatus($project, $statuses[0]);
        $this->addSubjectAreasProject($request, $id);
        $this->addRolesProject($request, $id);
        $this->addGoalsProject($request, $id);

        return response()->json([
            'status' => 'Created'
        ], 201);
    }

    public function createSendModeration(Request $request)
    {
        $validate = $request->validate([
            'name'                      => 'required',
            'description'               => 'required',
            'start_date'                => 'required|date',
            'finish_date'               => 'required|date',
            'remuneration'              => 'required',
            'project_type_id'           => 'required',

        ]);
        $user_id = auth()->id();
        $validate['project_owner_id'] = $user_id;
        $user_projects_count = Project::where('project_owner_id', '=', $user_id)->count();
        $maximum_number_of_projects = auth()->user()->maximum_number_of_projects;
        if ($maximum_number_of_projects <= $user_projects_count){
            return response()->json([
                'message' => 'Лимит на создание проектов превышен'
            ], 422);
        }
        $project_type_list = ProjectType::query()->get();
        foreach ($project_type_list as $project_type){
            $id[] = $project_type['id'];
        }
        if (!in_array($validate['project_type_id'], $id)){
            return response()->json([
                'message' => 'Тип проекта не найден'
            ], 422);
        } elseif (auth()->user()->hasRole('student')){
            $validate['project_type_id'] = 1;
        }

        $project = Project::query()->create($validate);

        $id = ($project->only(['id']))['id'];

        $statuses = $this->allStatuses();

        $this->addStatus($project, $statuses[1]);
        $this->addSubjectAreasProject($request, $id);
        $this->addRolesProject($request, $id);
        $this->addGoalsProject($request, $id);

        return response()->json([
            'status' => 'Created and submitting for moderation'
        ], 201);
    }

    public function sendModeration($id)
    {
        $project = auth()->user()->projectsowner()->find($id);
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Проект не найден'
            ], 400);
        }
        $statuses = $this->allStatuses();
        $this->addStatus($project, $statuses[1]);
        return response()->json([
            'status' => 'Заявка отправлена на модерацию'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $input  = $this->extractInputUpdate($request);
        $this->validateUpdate($input);
        $project_type_list = ProjectType::query()->get();
        foreach ($project_type_list as $project_type){
            $id_project_type[] = $project_type['id'];
        }
        if (!in_array($request['project_type_id'], $id_project_type)){
            return response()->json([
                'message' => 'Тип проекта не найден'
            ], 422);
        } elseif (auth()->user()->hasRole('student')){
            $request['project_type_id'] = 1;
        }
//        !(auth()->user()->hasRole('moderator'))
        if(auth()->user()->projectsowner()->find($id)){
            $project = auth()->user()->projectsowner()->find($id);

            $last_status = $project->projestatuses()->latest()->first()->statusproject()->get('name')->first()->name;
            if ($last_status != "Черновик"){
                return response()->json([
                    'success' => false,
                    'message' => 'Проект не может быть обновлен!'
                ], 422);
            } elseif (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Проект не найден'
                ], 400);
            }
        }elseif (auth()->user()->projectsmoderator()->find($id)){
            $project = Project::find($id);
        }

        $updated = $project->update([
            'project_type_id' => $request->project_type_id,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'finish_date' => $request->finish_date,
            'remuneration' => $request->remuneration
            ]);

        $this->updateRolesProject($request, $project);
        $this->updateGoalsProject($request, $project);
        $this->updateSubjectAreasProject($request, $project);

        if ($updated)
            return response()->json([
                'success' => true,
                'message' => 'Проект успешно обновлен'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Проект не может быть обновлен'
            ], 500);
    }





    //Здесь остановилась!!!!
    public function showProject($id)
    {
        $project = Project::find($id, ['id', 'project_owner_id', 'project_type_id', 'name', 'start_date', 'finish_date', 'updated_at']);
        $last_status = $project->projestatuses()->latest()->first()->statusproject()->get('name')->first()->name;
        $user = auth()->user();
        if (in_array($last_status, ['Набор участников', 'В процессе', 'Завершён'])){
            if ($user){
                $user = $user->hasRole(['partner', 'moderator', 'expert', 'curator', 'admin']);
                if ($user){
                    $project['owner'] = $project->owner()->get()->first();
                }
            }
            else{
                $project['owner'] = $project->owner()->get(['surname', 'name', 'patronymic', 'organization'])->first();
            }
            $project['project_type'] = $project->projectype()->get('name')->first()->name;
            $project['status'] = $last_status;
            $project['goals'] = $project->projectGoals()->get(['id', "description"]);
            $for_project_role = new Collection();
            $project_roles = $project->projectroles()->get();
            foreach ($project_roles as $key){
                $role = $key->projectSubR()->get(['id', 'name', 'subject_area_id'])->first();
                $role['number_seats'] = $key->number_seats;
                $for_project_role->push($role);
            }
            $project['project_roles'] = $for_project_role;
            $project['subject_area'] = $project->subjectAreas()->get(['id', 'name'])->map(function ($value){
                unset($value->pivot);
                return  $value;
            });
            unset($project->project_owner_id);
            unset($project->project_type_id);
            return response()->json($project);
        }
//        elseif (){
//



//        !!!!!!!!!!





//        }
        else{
            return response()->json([]);
        }
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

    public function addRolesProject(Request $request, $id)
    {
        foreach ($request['rolesubarea'] as $key => $value){
            $rolesproject = new ProjectRole;
            $rolesproject->project_id = $id;
            $rolesproject->role_subject_area_id = $value['role_subject_area_id'];
            $rolesproject->number_seats = $value['number_seats'];
            $rolesproject->save();
        }
    }

    public function addGoalsProject(Request $request, $id)
    {
        foreach ($request['goals'] as $key => $value){
            $goalsproject = new ObjectiveProject;
            $goalsproject->project_id = $id;
            $goalsproject->description = $value['description'];
            $goalsproject->save();
        }
    }

    public function addSubjectAreasProject(Request $request, $id)
    {
        foreach ($request['subject_areas'] as $key => $value){
            $goalsproject = new ProjectSubjectArea;
            $goalsproject->project_id = $id;
            $goalsproject->subject_area_id = $value['subject_area_id'];
            $goalsproject->save();
        }
    }

    public function updateRolesProject(Request $request, $project)
    {
        $rolesproject = $project->projectroles;
        foreach ($rolesproject as $key => $value){
            ProjectRole::destroy($value->id);
        }
        if (!empty($request['rolesubarea'])){
            $this->addRolesProject($request, $project->id);
        }
    }

    public function updateGoalsProject(Request $request, $project)
    {
        $goalsproject = $project->projectGoals;
        foreach ($goalsproject as $key => $value){
            ObjectiveProject::destroy($value->id);
        }
        if (!empty($request['goals'])){
            $this->addGoalsProject($request, $project->id);
        }
    }

    public function updateSubjectAreasProject(Request $request, $project)
    {
        foreach ($request['subject_areas'] as $key => $value){
            $subject_area_id[] = $value['subject_area_id'];
        }
        $project->subjectAreas()->sync($subject_area_id);
    }

    public function extractInputUpdate(Request $request): array
    {
        return $request->only([
            'name',
            'description',
            'start_date',
            'finish_date',
            'remuneration',
            'project_type_id',
        ]);
    }

    public function validateUpdate(array $input): MessageBag
    {
        $validator = validator($input, [
            'name'                      => 'required',
            'description'               => 'required',
            'start_date'                => 'required|date',
            'finish_date'               => 'required|date',
            'remuneration'              => 'required',
            'project_type_id'           => 'required',
        ]);

        $errors = $validator->getMessageBag();

        if ($errors->isNotEmpty()) response(compact('errors'), 422)->throwResponse();

        return $errors;
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $project = Project::find($id);
        $projectOwn = $user->projectsowner()->find($id);
        $checkRole = $user->hasAnyRole('moderator', 'admin');
        $statuses = $this->allStatuses();

        if ($projectOwn || $checkRole){
            if ($projectOwn){
                $last_status = $project->projestatuses()->latest()->first()->statusproject()->get('name')->first()->name;
                if ($last_status != "Черновик"){
                    return response()->json([
                        'success' => false,
                        'message' => 'Проект не может быть удалён!'
                    ], 422);
                } else {
                    $project->delete();
                    return response()->json(['message' => 'Проект успешно удалён!']);
                }
            }
            else{
                $project_status = new ProjectStatus();
                $project_status->project_id = $project->id;
                $project_status->status_id = $statuses[8];
                $project_status->datetime = Carbon::now();
                $project_status->save();
                return response()->json(['message' => 'Проект добавлен в архив!']);
            }
        }
        else
            return response()->json([
                'message' => 'Проект не найден'
            ], 422);
    }
}
