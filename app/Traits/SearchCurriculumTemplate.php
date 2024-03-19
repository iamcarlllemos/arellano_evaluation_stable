<?php

namespace App\Traits;

use App\Models\CurriculumTemplateModel;

trait SearchCurriculumTemplate {

    public function find(array $data) {

        $course = $data['course'];
        $year_level = $data['year_level'];
        $semester = $data['semester'];

        $dirty = $this->run_query($course, $year_level, $semester);
        $cleaned = $this->organize($dirty);

        $search = [
            'keywords' => [
                'course' => $course,
                'year_level' => $year_level,
                'semester' => $semester
            ],
            'results' => $cleaned
        ];

        return $search;
    }

    public function run_query( $course = null,  $year_level = null,  $semester = null) {

        $data = CurriculumTemplateModel::with(['departments.branches', 'courses', 'subjects'])
        ->when($course, function ($query) use ($course) {
            return $query->where('course_id', $course);
        })
        ->when($year_level, function ($query) use ($year_level) {
            return $query->where('year_level', $year_level);
        })
        ->when($semester, function ($query) use ($semester) {
            return $query->where('subject_sem', $semester);
        })
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_id' => $item->departments->branches->id,
                'branch_name' => $item->departments->branches->name,
                'department_id' => $item->departments->id,
                'department_name' => $item->departments->name,
                'course_id' => $item->courses->id,
                'course_code' => $item->courses->code,
                'course_name' => $item->courses->name,
                'subject_code' => $item->subjects->code,
                'subject_name' => $item->subjects->name,
                'year_level' => $item->year_level,
                'semester' => $item->subject_sem,
            ];
        })
        ->toArray();

        return $data;
    }

    public function organize($data) {
        $templates = [];

        foreach ($data as $item) {
            $branch_key = $item['branch_id'];
            $department_key = $item['department_id'];
            $course_key = $item['course_id'];
            $year_level = $item['year_level'];
            $semester = $item['semester'];

            // Initialize branch level if not exists
            if (!isset($templates[$branch_key])) {
                $templates[$branch_key] = [
                    'name' => $item['branch_name'],
                    'departments' => []
                ];
            }

            // Initialize department level if not exists
            if (!isset($templates[$branch_key]['departments'][$department_key])) {
                $templates[$branch_key]['departments'][$department_key] = [
                    'name' => $item['department_name'],
                    'years' => []
                ];
            }

            // Initialize year level if not exists
            if (!isset($templates[$branch_key]['departments'][$department_key]['years'][$year_level])) {
                $templates[$branch_key]['departments'][$department_key]['years'][$year_level] = [
                    'name' => to_ordinal($year_level,  'year'),
                    'courses' => []
                ];
            }

            // Initialize course if not exists
            if (!isset($templates[$branch_key]['departments'][$department_key]['years'][$year_level]['courses'][$course_key])) {
                $templates[$branch_key]['departments'][$department_key]['years'][$year_level]['courses'][$course_key] = [
                    'code' => $item['course_code'],
                    'name' => $item['course_name'],
                    'semesters' => []
                ];
            }

            // Initialize semester if not exists
            if (!isset($templates[$branch_key]['departments'][$department_key]['years'][$year_level]['courses'][$course_key]['semesters'][$semester])) {
                $templates[$branch_key]['departments'][$department_key]['years'][$year_level]['courses'][$course_key]['semesters'][$semester] = [
                    'name' => to_ordinal($semester, 'semester'),
                    'subjects' => []
                ];
            }

            // Add subject to the structure
            $templates[$branch_key]['departments'][$department_key]['years'][$year_level]['courses'][$course_key]['semesters'][$semester]['subjects'][] = [
                'id' => $item['id'],
                'code' => $item['subject_code'],
                'name' => $item['subject_name'],

            ];
        }

        // Apply array values to reindex the array
        $templates = array_values($templates);
        foreach ($templates as &$branch) {
            $branch['departments'] = array_values($branch['departments']);
            foreach ($branch['departments'] as &$department) {
                $department['years'] = array_values($department['years']);
                foreach ($department['years'] as &$year) {
                    $year['courses'] = array_values($year['courses']);
                    foreach ($year['courses'] as &$course) {
                        $course['semesters'] = array_values($course['semesters']);
                        foreach ($course['semesters'] as &$semester) {
                            $semester['subjects'] = array_values($semester['subjects']);
                        }
                    }
                }
            }
        }

        return $templates;

    }



}
