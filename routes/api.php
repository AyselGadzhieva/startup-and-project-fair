<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubjectAreaController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\CuratorController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('profile', 'profile');
});


//публичные
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project_id}', [ProjectController::class, 'showProject']);
Route::get('/subarea', [SubjectAreaController::class, 'showsubarea']);
Route::get('/rolesubarea', [SubjectAreaController::class, 'showrolesubarea']);


Route::group(['middleware' => ['role:admin|partner|student|moderator']], function () {
    Route::put('/update/{project_id}', [ProjectController::class, 'update']);
});


Route::group(['middleware' => ['role:admin|partner|student|curator']], function () {
    Route::post('/create_draft', [ProjectController::class, 'createDraft']);
    Route::post('/create_send_moderation', [ProjectController::class, 'createSendModeration']);
    Route::post('/send_moderation/{project_id}', [ProjectController::class, 'sendModeration']);
    Route::delete('/destroy/{project_id}', [ProjectController::class, 'destroy']);

    //для модератора
    Route::get('/for_moderation', [ModeratorController::class, 'forModeration']);
    Route::post('/take_moderation/{project_id}', [ModeratorController::class, 'takeForModeration']);
    Route::post('/reject_project/{project_id}', [ModeratorController::class, 'rejectProject']);
    Route::get('/show_projects_moderator', [ModeratorController::class, 'showProjectsModerator']);
    Route::post('/send_examination/{project_id}', [ModeratorController::class, 'sendExamination']);
    Route::get('/get_experts/{project_id}', [ModeratorController::class, 'getExperts']);
    Route::post('/send_to_curators/{project_id}', [ModeratorController::class, 'sendToCurators']);

    //эксперт
    Route::get('/incoming', [ExpertController::class, 'incomingApplications']);
    Route::get('/evaluated', [ExpertController::class, 'evaluatedApplications']);
    Route::post('/send_feedback/{id_exp}', [ExpertController::class, 'sendFeedback']);//!!!

    //куратор
    Route::get('/for_curators', [CuratorController::class, 'poolForCurators']);

    //тест
    Route::get('/show_projects_owner', [ConsumerController::class, 'showProjectsOwner']);
    Route::post('/take_on_curation/{project_id}', [CuratorController::class, 'takeOnCuration']);

});

Route::middleware(['auth', 'role:partner'])->prefix('partner')->group(function () {
});


Route::middleware('auth')->prefix('employee')->group(function () {

    Route::middleware('role:moderator')->prefix('moderator')->group(function () {
        Route::get('/for_moderation', [ModeratorController::class, 'forModeration']);
        Route::post('/take_moderation/{project_id}', [ModeratorController::class, 'takeForModeration']);
        Route::post('/reject_project/{project_id}', [ModeratorController::class, 'rejectProject']);
        Route::get('/show_projects_moderator', [ModeratorController::class, 'showProjectsModerator']);
        Route::post('/send_examination/{project_id}', [ModeratorController::class, 'sendExamination']);
        Route::get('/get_experts/{project_id}', [ModeratorController::class, 'getExperts']);
        Route::post('/send_to_curators/{project_id}', [ModeratorController::class, 'sendToCurators']);

    //тест

    });


    Route::middleware('role:expert')->prefix('expert')->group(function () {
        Route::get('/incoming', [ExpertController::class, 'incomingApplications']);
        Route::get('/evaluated', [ExpertController::class, 'evaluatedApplications']);
        Route::post('/send_feedback/{project_id}', [ExpertController::class, 'sendFeedback']);

        //тест

    });


    Route::middleware('role:curator')->prefix('curator')->group(function () {
        Route::get('/for_curators', [CuratorController::class, 'poolForCurators']);

        //тест

    });

});


Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'users']);
});

























// -из примеров-

//Route::middleware(['auth', 'role:moderator'])->prefix('employee')->group(function () {
//
//});
//
//
//Route::middleware(['auth', 'role:expert'])->prefix('employee')->group(function () {
//
//});
//
//
//Route::middleware(['auth', 'role:curator'])->prefix('employee')->group(function () {
//
//});




//Route::middleware(['auth:api', 'role:customer'])->prefix('employee')->group(function () {
//    Route::controller(CustomerController::class)->group(function () {
//
//    });
//});

//Route::group(['middleware' => ['role:customer']], function () {
//    Route::get('/customer2', [CustomerController::class, 'show2']);
//});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});  // тоже профиль

//Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
//    Route::get('/', [IndexController::class, 'index'])->name('index');
//    Route::resource('/roles', RoleController::class);
//    Route::post('/roles/{role}/permissions', [RoleController::class, 'givePermission'])->name('roles.permissions');
//    Route::delete('/roles/{role}/permissions/{permission}', [RoleController::class, 'revokePermission'])->name('roles.permissions.revoke');
//    Route::resource('/permissions', PermissionController::class);
//    Route::post('/permissions/{permission}/roles', [PermissionController::class, 'assignRole'])->name('permissions.roles');
//    Route::delete('/permissions/{permission}/roles/{role}', [PermissionController::class, 'removeRole'])->name('permissions.roles.remove');
//    Route::get('/users', [UserController::class, 'index'])->name('users.index');
//    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
//    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
//    Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])->name('users.roles');
//    Route::delete('/users/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('users.roles.remove');
//    Route::post('/users/{user}/permissions', [UserController::class, 'givePermission'])->name('users.permissions');
//    Route::delete('/users/{user}/permissions/{permission}', [UserController::class, 'revokePermission'])->name('users.permissions.revoke');
//});
