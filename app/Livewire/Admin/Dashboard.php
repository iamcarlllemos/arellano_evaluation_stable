<?php

namespace App\Livewire\Admin;

use App\Models\BranchModel;
use App\Models\CourseModel;
use App\Models\DepartmentModel;
use App\Models\FacultyModel;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use Livewire\Component;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class Dashboard extends Component
{

    public $data = [];


    public function generate_colors() {
        $response = Http::get('https://x-colors.yurace.pro/api/random?number=1');
        return $response->json()['hex'];
    }

    public function fetch_data() {

        $this->data['counts'] = [
            'branches' => [
                'count' => BranchModel::count(),
                'route' => 'admin.programs.branches'
            ],
            'courses' => [
                'count' => CourseModel::count(),
                'route' => 'admin.programs.courses'
            ],
            'departments' => [
                'count' => DepartmentModel::count(),
                'route' => 'admin.programs.departments'
            ],
            'subjects' => [
                'count' => SubjectModel::count(),
                'route' => 'admin.programs.subjects'
            ],
            'faculties' => [
                'count' => FacultyModel::count(),
                'route' => 'admin.accounts.faculty'
            ],
            'students' => [
                'count' => StudentModel::count(),
                'route' => 'admin.accounts.student'
            ],
        ];
    }


    public function render()
    {

        $time = Carbon::now();
        $mode = strtolower($time->format('H'));

        $mode_string = '';

        if ($mode >= 1 && $mode < 12) {
            $mode_string = "good morning";
        } elseif ($mode >= 12 && $mode < 18) {
            $mode_string = "good afternoon";
        } elseif ($mode >= 18 && $mode < 22) {
            $mode_string = "good evening";
        }

        $mode_string = ucwords($mode_string);
        $name = auth()->guard('admins')->user()->name;

        $message = $mode_string . '! ' . $name ;

        $this->data = [
            'message' => $message,
        ];

        $this->fetch_data();

        return view('livewire.admin.dashboard');
    }
}
