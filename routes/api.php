<?php

use App\Http\Controllers\Api\V1\LeaveController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RegistrationController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\UserParameter\CompanyController;
use App\Http\Controllers\Api\V1\UserParameter\CurrentLeaveController;
use App\Http\Controllers\Api\V1\UserParameter\DepartmentController;
use App\Http\Controllers\Api\V1\UserParameter\DomainController;
use App\Http\Controllers\Api\V1\UserParameter\EmploymentTypeController;
use App\Http\Controllers\Api\V1\UserParameter\EntitledLeaveController;
use App\Http\Controllers\Api\V1\UserParameter\GenderController;
use App\Http\Controllers\Api\V1\UserParameter\RoleController;
use App\Http\Controllers\Api\V1\UserParameter\StaffStatusController;
use App\Http\Controllers\Api\V1\UserParameter\WorkingHourTypeController;
use App\Http\Controllers\Api\V1\UserParameter\WorkingStateController;
use App\Http\Controllers\Api\V1\UserParameter\WorkingYearsController;
use App\Http\Controllers\Api\V1\PublicHolidayController;
use App\Http\Controllers\Api\V1\SystemSettings\LeaveManagementController;
use App\Http\Controllers\Api\V1\SystemSettings\GeneralSettingController;
use App\Http\Controllers\Api\V1\SystemSettings\ApprovalWorkflowController;
use App\Http\Controllers\Api\V1\SystemSettings\LeaveTypeController;
use App\Http\Middleware\ActionLogging;

//Route: Other - no authentication
Route::middleware(ActionLogging::class)->prefix('v1')->group(function(){
    Route::post('login', [LoginController::class, 'login'])->name('login');
});

//Route: User Parameter
Route::middleware(['auth:api'])->prefix('v1')->group(function(){
    Route::middleware(ActionLogging::class)->group(function() {
        Route::prefix('company')->group(function () {
            Route::get('/list', [CompanyController::class, 'list']);
            Route::get('/list/{id}', [CompanyController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [CompanyController::class, 'insert']);
            Route::put('/update/{id}', [CompanyController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[CompanyController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('currentleave')->group(function () {
            Route::get('/list/{year}', [CurrentLeaveController::class, 'list']);
            Route::get('/list/{year}/{userid}', [CurrentLeaveController::class, 'list']);
            Route::post('/insert', [CurrentLeaveController::class, 'insert']);
            Route::put('/update/{id}', [CurrentLeaveController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[CurrentLeaveController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('department')->group(function () {
            Route::get('/list', [DepartmentController::class, 'list']);
            Route::get('/list/{id}', [DepartmentController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [DepartmentController::class, 'insert']);
            Route::put('/update/{id}', [DepartmentController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[DepartmentController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('employmenttype')->group(function () {
            Route::get('/list', [EmploymentTypeController::class, 'list']);
            Route::get('/list/{id}', [EmploymentTypeController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [EmploymentTypeController::class, 'insert']);
            Route::put('/update/{id}', [EmploymentTypeController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[EmploymentTypeController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('entitledleave')->group(function () {
            Route::get('/list', [EntitledLeaveController::class, 'list']);
            Route::get('/list/{id}', [EntitledLeaveController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [EntitledLeaveController::class, 'insert']);
            Route::put('/update/{id}', [EntitledLeaveController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[EntitledLeaveController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('gender')->group(function () {
            Route::get('/list', [GenderController::class, 'list']);
            Route::get('/list/{id}', [GenderController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [GenderController::class, 'insert']);
            Route::put('/update/{id}', [GenderController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[GenderController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('staffstatus')->group(function () {
            Route::get('/list', [StaffStatusController::class, 'list']);
            Route::get('/list/{id}', [StaffStatusController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [StaffStatusController::class, 'insert']);
            Route::put('/update/{id}', [StaffStatusController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[StaffStatusController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('workinghourtype')->group(function () {
            Route::get('/list', [WorkingHourTypeController::class, 'list']);
            Route::get('/list/{id}', [WorkingHourTypeController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [WorkingHourTypeController::class, 'insert']);
            Route::put('/update/{id}', [WorkingHourTypeController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[WorkingHourTypeController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('workingstate')->group(function () {
            Route::get('/list', [WorkingStateController::class, 'list']);
            Route::get('/list/{id}', [WorkingStateController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [WorkingStateController::class, 'insert']);
            Route::put('/update/{id}', [WorkingStateController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[WorkingStateController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('workingyears')->group(function () {
            Route::get('/list', [WorkingYearsController::class, 'list']);
            Route::get('/list/{id}', [WorkingYearsController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [WorkingYearsController::class, 'insert']);
            Route::put('/update/{id}', [WorkingYearsController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[WorkingYearsController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('domain')->group(function () {
            Route::get('/list', [DomainController::class, 'list']);
            Route::get('/list/{id}', [DomainController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [DomainController::class, 'insert']);
            Route::put('/update/{id}', [DomainController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[DomainController::class, 'delete'])->whereNumber('id');
        });
        Route::prefix('role')->group(function () {
            Route::get('/list', [RoleController::class, 'list']);
            Route::get('/list/{id}', [RoleController::class, 'list'])->whereNumber('id');
            Route::post('/insert', [RoleController::class, 'insert']);
            Route::put('/update/{id}', [RoleController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[RoleController::class, 'delete'])->whereNumber('id');
        });

        Route::prefix('publicholiday')->group(function () {
            Route::get('/list/{year}', [PublicHolidayController::class, 'list']);
            Route::get('/list/{year}/{month}', [PublicHolidayController::class, 'list']);
            Route::post('/list/range', [PublicHolidayController::class, 'listByRange']);
            Route::post('/insert', [PublicHolidayController::class, 'insert']);
            Route::put('/update/{id}', [PublicHolidayController::class, 'update'])->whereNumber('id');
            Route::delete('/delete/{id}',[PublicHolidayController::class, 'delete'])->whereNumber('id');
        });
    });
});

//Route: System Setting
Route::middleware(['auth:api'])->prefix('v1')->group(function(){
    Route::middleware(ActionLogging::class)->group(function(){
        Route::prefix('setting')->group(function () {
            Route::post('/general/configure', [GeneralSettingController::class, 'configure']);
            Route::get('/general/{company_id}', [GeneralSettingController::class, 'list']);

            Route::prefix('leave')->group(function () {
                Route::get('/{id}', [LeaveManagementController::class, 'list'])->whereNumber('id');
                Route::post('/configure', [LeaveManagementController::class, 'configure']);
                Route::post('/insert', [LeaveTypeController::class, 'insert']);
                Route::delete('/delete/{id}', [LeaveTypeController::class, 'delete'])->whereNumber('id');
                Route::get('/', [LeaveTypeController::class, 'listAll']);
            });

            Route::prefix('approval/workflow')->group(function () {
                Route::post('/', [ApprovalWorkflowController::class, 'configure']); //insert
                Route::put('/{id}', [ApprovalWorkflowController::class, 'configure'])->whereNumber('id'); //update
                Route::delete('/{id}', [ApprovalWorkflowController::class, 'delete'])->whereNumber('id');
                Route::get('/', [ApprovalWorkflowController::class, 'list']);
            });
        });
    });
});

//Route: Leave application
Route::middleware(['auth:api'])->prefix('v1')->group(function(){
    Route::middleware(ActionLogging::class)->group(function(){
        Route::prefix('leave')->group(function () {
            Route::post('/apply', [LeaveController::class, 'applyLeave']);
            Route::post('/approve', [LeaveController::class, 'approveLeave']);
            Route::post('/reject', [LeaveController::class, 'rejectLeave']);
        });
    });
});

//Route: Other - with authentication
Route::middleware(['auth:api'])->prefix('v1')->group(function(){
    Route::middleware(ActionLogging::class)->group(function(){
        Route::get('/user', function (Request $request) {return $request->user();});
        Route::post('register', [RegistrationController::class, 'register'])->name('register');
        Route::post('register/check/{type}', [RegistrationController::class, 'check']);
    });
});

