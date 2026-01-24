<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockTestQuestionReport;
use Illuminate\Http\Request;

class QuestionReportController extends Controller
{
    public function index(Request $request)
    {
        $query = MockTestQuestionReport::query()
            ->with(['reporter'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(15)->withQueryString();

        return view('admin.reports', compact('reports'));
    }
}
