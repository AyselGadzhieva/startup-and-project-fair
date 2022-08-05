<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\SubjectArea;
use App\Models\ProjectRole;
use App\Models\ObjectiveProject;
use App\Models\RoleSubjectArea;
use Illuminate\Support\Facades\DB;


class SubjectAreaController extends Controller
{
    // вывод всех предметных областей
    public function showsubarea()
    {
        $subarea = SubjectArea::get(['id', 'name']);
        return response()->json(compact('subarea'));
    }

    // вывод всех ролей
    public function showrolesubarea()
    {
        $rolesubarea = RoleSubjectArea::get(['id', 'name','subject_area_id']);
        return response()->json(compact('rolesubarea'));
    }

}
