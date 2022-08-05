<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Status;
use App\Models\ProjectStatus;
use App\Models\ProjectRole;
use App\Models\ObjectiveProject;
use App\Models\ProjectSubjectArea;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsumerController extends Controller
{
    // вывод проектов пользователя
    public function showProjectsOwner()
    {
        $projectsall = Project::with('owner', 'projectype')->get();

        $projects = auth()->user()->projectsowner()->get();
        foreach ($projects as $p){
            $id_projects[] = $p['id'];
        }
        foreach ($projectsall as $pall){
            foreach ($projects as $p){
                $id_projects[] = $p['id'];

            }
        }


//        $subset = $projects->map(function ($project) {
//            return collect($project->toArray())
//                ->only(['id', 'name'])
//                ->all();
//        });

        if (!$projects) {
            return response()->json([
                'success' => false,
                'message' => 'Проекты не найдены'
            ], 400);
        }

        return response()->json();
    }
}
