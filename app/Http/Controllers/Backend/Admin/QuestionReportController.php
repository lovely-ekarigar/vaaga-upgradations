<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionReportController extends Controller
{
    public function index(Request $request)
    {
        // Get question reports from database
        // Assuming there's a question_reports table
        $reports = collect([]);
        
        // Try to get from database if table exists
        try {
            if (DB::getSchemaBuilder()->hasTable('question_reports')) {
                $reports = DB::table('question_reports')
                    ->join('users', 'question_reports.user_id', '=', 'users.id')
                    ->select('question_reports.*', 'users.name as user_name', 'users.email')
                    ->orderBy('question_reports.created_at', 'desc')
                    ->paginate(15);
            }
        } catch (\Exception $e) {
            // Table doesn't exist, return empty collection
            $reports = collect([])->paginate(15);
        }

        return view('admin.reports', compact('reports'));
    }
}
