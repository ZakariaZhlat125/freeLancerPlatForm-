<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
     ////////////////////show report in dashboard///////////

     public function showAll()
     {
         $reports =  Report::select(
             'reports.id',
             'reports.user_id',
             'reports.provider_id',
             'reports.type_report',
             'reports.massege',
             'reportesr.name as reporter',
             'reporteds.name as reported',
         )
             ->join('profiles as reportesr', 'reportesr.user_id', '=', 'reports.user_id')
             ->join('profiles as reporteds', 'reporteds.user_id', '=', 'reports.provider_id')
             ->where('reports.is_active', 1)
             ->where('project_id', '=', null)
             ->get();

         $reports_project =  Report::select(
             'reports.id as report_id',
             'reports.user_id',
             'reports.post_id',
             'reports.provider_id',
             'reports.type_report',
             'reports.created_at',
             'reports.project_id as project_id',
             'reportesr.name as reporter',
             'posts.title'
         )
             ->join('profiles as reportesr', 'reportesr.user_id', '=', 'reports.user_id')
             ->join('projects', 'projects.id', '=', 'reports.project_id')
             ->join('posts', 'posts.id', '=', 'projects.post_id')
             ->where('project_id', '!=', null)

             ->where('reports.is_active', 1)
             ->groupBy('posts.id')
             ->get();

         $reports_post =  Report::select(
             'reports.id',
             'reports.user_id',
             'reports.type_report',
             'reports.massege',
             'reportesr.name as reporter',
             'posts.title'
         )
             ->join('profiles as reportesr', 'reportesr.user_id', '=', 'reports.user_id')
             ->join('posts', 'posts.id', '=', 'reports.post_id')
             ->where('project_id', '=', null)
             ->where('reports.is_active', 1)
             ->get();

         // return response()->json($reports_project);
         return view('admin.report.index')->with(['reports' => $reports, 'reports_project' => $reports_project, 'reports_post' =>  $reports_post]);
     }
}
