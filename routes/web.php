<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\QuestionnaireController;
use App\Http\Controllers\Admin\QuestionnaireItemController;
use App\Http\Controllers\Admin\FacultyTemplateController;
use App\Http\Controllers\Admin\ResultsController;


use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\AdministratorController;
use App\Http\Controllers\Admin\CurriculumTemplateController;
use App\Http\Controllers\Admin\SmtpController;
use App\Http\Controllers\Admin\ValidateResponsesController;
use App\Http\Controllers\Student\LoginController as StudentLoginController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\SubjectController as StudentSubjectController;
use App\Http\Controllers\Student\EvaluateController as StudentEvaluateController;

use App\Http\Controllers\Faculty\LoginController as FacultyLoginController;
use App\Http\Controllers\Faculty\DashboardController as FacultyDashboardController;
use App\Http\Controllers\Faculty\SubjectController as FacultySubjectController;
use App\Http\Controllers\Faculty\EvaluateController as FacultyEvaluateController;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

require_once __DIR__ . '/fortify.php';
require_once __DIR__ . '/jetstream.php';

// ADMIN


Route::prefix('admin')->middleware('admins')->group(function() {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::middleware('role:admin,superadmin')->prefix('programs')->group(function() {
        Route::get('/departments', [DepartmentController::class, 'index'])->name('admin.programs.departments');
        Route::get('/courses', [CourseController::class, 'index'])->name('admin.programs.courses');
        Route::get('/subjects', [SubjectController::class, 'index'])->name('admin.programs.subjects');
    });

    Route::middleware('role:superadmin')->prefix('programs')->group(function() {
        Route::get('/branches', [BranchController::class, 'index'])->name('admin.programs.branches');
        Route::get('/school-year', [SchoolYearController::class, 'index'])->name('admin.programs.school-year');
        Route::get('/school-year/results', [ResultsController::class, 'index'])->name('admin.programs.results');
        Route::get('/validate/response', [ValidateResponsesController::class, 'index'])->name('admin.programs.validate-responses');
        Route::get('/criteria', [CriteriaController::class, 'index'])->name('admin.programs.criteria');
        Route::get('/questionnaire', [QuestionnaireController::class, 'index'])->name('admin.programs.questionnaire');
        Route::get('/questionnaire/{slug}', [QuestionnaireItemController::class, 'index'])->name('admin.programs.questionnaire.item');
    });

    Route::middleware('role:admin,superadmin')->prefix('linking')->group(function() {
        Route::get('/curriculum-template', [CurriculumTemplateController::class, 'index'])->name('admin.linking.curriculum-template');
        Route::get('/faculty-template', [FacultyTemplateController::class, 'index'])->name('admin.linking.faculty-template');
    });

    Route::middleware('role:superadmin')->prefix('settings')->group(function() {
        Route::get('/smtp', [SmtpController::class, 'index'])->name('admin.settings.smtp');
    });

    Route::prefix('accounts')->group(function() {
        Route::get('/students', [StudentController::class, 'index'])->name('admin.accounts.student')->middleware('role:admin,superadmin');
        Route::get('/faculty', [FacultyController::class, 'index'])->name('admin.accounts.faculty')->middleware('role:admin,superadmin');
        Route::get('/administrators', [AdministratorController::class, 'index'])->name('admin.accounts.administrator')->middleware('role:superadmin');
    });
});

Route::prefix('student')->group(function() {
    Route::get('login', [StudentLoginController::class, 'index'])->name('student.index');
    Route::post('login', [StudentLoginController::class, 'login'])->name('student.login');
    Route::any('logout', [StudentLoginController::class, 'logout'])->name('student.logout');

    Route::middleware('students')->group(function() {
        Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
        Route::get('subject', [StudentSubjectController::class, 'index'])->name('student.subject');
        Route::get('subject/evaluate/start', [StudentEvaluateController::class, 'index'])->name('student.evaluate');
    });
});

Route::prefix('faculty')->group(function() {
    Route::get('login', [FacultyLoginController::class, 'index'])->name('faculty.index');
    Route::post('login', [FacultyLoginController::class, 'login'])->name('faculty.login');
    Route::any('logout', [FacultyLoginController::class, 'logout'])->name('faculty.logout');

    Route::middleware('faculty')->group(function() {
        Route::get('dashboard', [FacultyDashboardController::class, 'index'])->name('faculty.dashboard');
        Route::get('subject', [FacultySubjectController::class, 'index'])->name('faculty.subject');
        Route::get('subject/evaluate', [FacultyEvaluateController::class, 'index'])->name('faculty.evaluation-results');
    });
});


#
