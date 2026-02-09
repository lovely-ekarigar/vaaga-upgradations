<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\MockTest;
use App\Models\MockTestSchedule;
use App\Models\MockTestQuestionReport;
use App\Models\Batch;
use App\Models\Question;
use App\Models\QuestionsOption;
use App\Models\TeacherBatch;
use App\Models\StudentTeacherBatch;
use App\Models\Auth\User;
use App\Events\Backend\MockTestRescheduled;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class MockTestController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    

    /**
     * Display a listing of Mock Tests.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('mocktest_access')) {
            return abort(401);
        }

        if (request('show_deleted') == 1) {
            if (! Gate::allows('mocktest_delete')) {
                return abort(401);
            }
            $mockTests = MockTest::onlyTrashed()->with(['courses'])->get();
        } else {
            $mockTests = MockTest::with(['courses'])->get();
        }
        
        $courses = Course::ofTeacher()->pluck('title','id')->prepend('Please select', '');
        
        if(auth()->user()->hasRole('administrator')){
            $batch_list = Batch::orderBy('id','desc')->get();
        } else if(auth()->user()->hasRole('teacher')){
            $bids = [];
            $batches = TeacherBatch::where("tid", Auth::user()->id)->get();
            foreach($batches as $b){
                $bids[] = $b->bid;
            }
            $batch_list = Batch::whereIn("id", $bids)->orderBy('id','desc')->get();
        }

        return view('backend.mocktests.index', compact('mockTests','courses','batch_list'));
    }

    /**
     * Display a listing of Mock Tests via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $mockTests = "";

        if ($request->course_id != "") {
            $mockTests = MockTest::whereHas('courses', function ($q) use ($request) {
                $q->where('courses.id', '=', $request->course_id);
            })->orderBy('created_at', 'desc')->get();
        }

        if (request('show_deleted') == 1) {
            if (!Gate::allows('mocktest_delete')) {
                return abort(401);
            }
            $mockTests = MockTest::onlyTrashed()->get();
        }

        if (auth()->user()->can('mocktest_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('mocktest_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('mocktest_delete')) {
            $has_delete = true;
        }

        return DataTables::of($mockTests)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.mocktests', 'label' => 'mocktest', 'value' => $q->id]);
                }
                
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.mocktests.show', ['mocktest' => $q->id])])->render();
                }
                
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.mocktests.edit', ['mocktest' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.mocktests.destroy', ['mocktest' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                $view .= '<a href="javascript:void(0)" data-id="'.$q->id.'" class="batch-init btn btn-outline-primary btn-sm mr-1"> Assign Batch</a>';
                $view .= '<a href="'.route('admin.questions.index', ['mock_test_id' => $q->id]).'" class="btn btn-success btn-sm mr-1"><i class="fa fa-question-circle"></i> Manage Questions</a>';

                return $view;
            })
            ->editColumn('questions', function ($q){
                if(count($q->questions) > 0){
                    return "<span>".count($q->questions)."</span><a class='btn btn-success btn-sm float-right' href='".route('admin.questions.index',['mock_test_id'=>$q->id])."'><i class='fa fa-arrow-circle-o-right'></i></a> ";
                }
                return count($q->questions);
            })
            ->editColumn('course', function ($q){
                return ($q->course) ? $q->course->title : "N/A";
            })
            ->editColumn('published', function ($q) {
                return ($q->published == 1) ? "Yes" : "No";
            })
            ->rawColumns(['actions','questions'])
            ->make();
    }

    /**
     * Show the form for creating new Mock Test.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('mocktest_create')) {
            return abort(401);
        }
        
        $courses = Course::ofTeacher()
            ->where('published', 1)
            ->orderBy('title')
            ->pluck('title', 'id');

        return view('backend.mocktests.create', compact('courses'));
    }

    /**
     * Store a newly created Mock Test in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'integer|exists:courses,id',
            'title' => 'required',
            'description' => 'required'
        ],['course_ids.required' => 'The class field is required']);

        if (! Gate::allows('mocktest_create')) {
            return abort(401);
        }

        $courseIds = array_values(array_unique(array_filter($request->input('course_ids', []))));

        $data = [
            'course_id' => $courseIds[0] ?? null,
            'title' => $request->title,
            'description' => $request->description,
            'published' => (int) ($request->published ?? 0),
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('mock_tests', 'status')) {
            $data['status'] = $request->status ?? ($data['published'] ? MockTest::STATUS_PUBLISHED : MockTest::STATUS_DRAFT);
        }
        $mockTest = MockTest::create($data);
        $mockTest->slug = str_slug($request->title);
        $mockTest->save();
        $mockTest->courses()->sync($courseIds);

        return redirect()->route('admin.mocktests.index')->withFlashSuccess('Mock Test created successfully');
    }

    /**
     * Show the form for editing Mock Test.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('mocktest_edit')) {
            return abort(401);
        }
        
        $courses = Course::ofTeacher()
            ->where('published', 1)
            ->orderBy('title')
            ->pluck('title', 'id');

        $mockTest = MockTest::with(['courses', 'questions'])->findOrFail($id);
        $selectedCourseIds = $mockTest->courses->pluck('id')->toArray();

        return view('backend.mocktests.edit', compact('mockTest', 'courses', 'selectedCourseIds'));
    }

    /**
     * Detach a question from a mock test (does NOT delete the question).
     */
    public function detachQuestion($mockTestId, $questionId)
    {
        if (! Gate::allows('mocktest_edit')) {
            return abort(401);
        }

        $mockTest = MockTest::findOrFail($mockTestId);
        $mockTest->questions()->detach($questionId);

        return redirect()
            ->back()
            ->withFlashSuccess('Question removed from the mock test.');
    }

    /**
     * Update Mock Test in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! Gate::allows('mocktest_edit')) {
            return abort(401);
        }
        
        $mockTest = MockTest::findOrFail($id);
        $this->validate($request,[
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'integer|exists:courses,id',
            'title' => 'required',
            'description' => 'required'
        ]);

        $courseIds = array_values(array_unique(array_filter($request->input('course_ids', []))));

        $updateData = [
            'course_id' => $courseIds[0] ?? null,
            'title' => $request->title,
            'description' => $request->description,
            'published' => (int) ($request->published ?? 0),
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('mock_tests', 'status')) {
            $updateData['status'] = $request->status ?? ($updateData['published'] ? MockTest::STATUS_PUBLISHED : MockTest::STATUS_DRAFT);
        }
        $mockTest->update($updateData);
        $mockTest->slug = str_slug($request->title);
        $mockTest->save();
        $mockTest->courses()->sync($courseIds);

        return redirect()->route('admin.mocktests.index')->withFlashSuccess('Mock Test updated successfully');
    }

    /**
     * Display Mock Test.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('mocktest_view')) {
            return abort(401);
        }
        
        $mockTest = MockTest::with(['courses'])->findOrFail($id);

        return view('backend.mocktests.show', compact('mockTest'));
    }

    /**
     * Remove Mock Test from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('mocktest_delete')) {
            return abort(401);
        }
        
        $mockTest = MockTest::findOrFail($id);
        $mockTest->delete();

        return back()->withFlashSuccess('Mock Test deleted successfully');
    }

    /**
     * Assign batch to mock test
     */
    public function assignBatch(Request $request)
    {
        $this->validate($request, [
            'mock_test_id' => 'required|exists:mock_tests,id',
            'batch_id' => 'required|exists:batches,id',
            'scheduled_date' => 'nullable|date',
            'timezone' => 'nullable|string|max:100',
        ]);

        $mockTest = MockTest::findOrFail($request->mock_test_id);

        $canAssign = \Illuminate\Support\Facades\Schema::hasColumn('mock_tests', 'status')
            ? $mockTest->status === MockTest::STATUS_PUBLISHED
            : (bool) $mockTest->published;
        if (!$canAssign) {
            return redirect()->back()->withFlashDanger('Only published mock tests can be assigned to a batch. Please publish the mock test first.');
        }
        
        // Check if already scheduled for this batch
        $existingSchedule = MockTestSchedule::where('mock_test_id', $request->mock_test_id)
            ->where('batch_id', $request->batch_id)
            ->first();

        if ($existingSchedule) {
            return redirect()->back()->withFlashWarning('This mock test is already assigned to the selected batch.');
        }

        $scheduledDate = $request->scheduled_date ? \Carbon\Carbon::parse($request->scheduled_date)->toDateString() : now()->toDateString();
        $timezone = $request->timezone ?: (auth()->user()->timezone ?: 'Asia/Kolkata');

        MockTestSchedule::create([
            'mock_test_id' => $request->mock_test_id,
            'batch_id' => $request->batch_id,
            'scheduled_date' => $scheduledDate,
            'timezone' => $timezone,
            'status' => 'scheduled',
        ]);

        // Send notification to students
        $this->notificationService->sendMockTestAssignment($mockTest, $request->batch_id);

        return redirect()->back()->withFlashSuccess('Mock test has been assigned to batch. Students have been notified.');
    }

    /**
     * View all schedules/results for a mock test
     */
    public function viewSchedules($id)
    {
        if (! Gate::allows('mocktest_view')) {
            return abort(401);
        }

        $mockTest = MockTest::findOrFail($id);
        $schedules = MockTestSchedule::where('mock_test_id', $id)
            ->with(['batch', 'assignedBy', 'results'])
            ->get();

        if(auth()->user()->hasRole('administrator')){
            $batch_list = Batch::orderBy('id','desc')->get();
        } else if(auth()->user()->hasRole('teacher')){
            $bids = [];
            $batches = TeacherBatch::where("tid", Auth::user()->id)->get();
            foreach($batches as $b){
                $bids[] = $b->bid;
            }
            $batch_list = Batch::whereIn("id", $bids)->orderBy('id','desc')->get();
        } else {
            $batch_list = collect();
        }

        return view('backend.mocktests.schedules', compact('mockTest', 'schedules', 'batch_list'));
    }

    /**
     * Show reschedule form for a schedule (Admin).
     */
    public function rescheduleForm($scheduleId)
    {
        if (! Gate::allows('mocktest_edit')) {
            return abort(401);
        }
        $schedule = MockTestSchedule::with(['mockTest', 'batch'])->findOrFail($scheduleId);
        return view('backend.mocktests.reschedule', compact('schedule'));
    }

    /**
     * Reschedule a mock test (Admin).
     */
    public function reschedule(Request $request)
    {
        if (! Gate::allows('mocktest_edit')) {
            return abort(401);
        }
        $this->validate($request, [
            'schedule_id' => 'required|exists:mock_test_schedules,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'reschedule_reason' => 'required|string|max:500',
        ]);
        $schedule = MockTestSchedule::findOrFail($request->schedule_id);
        if ($schedule->results()->count() > 0) {
            return redirect()->back()->withFlashWarning('Cannot reschedule. Some students have already attempted this test.');
        }
        $schedule->scheduled_date = $request->scheduled_date;
        $schedule->rescheduled_at = now();
        $schedule->rescheduled_by = Auth::id();
        $schedule->reschedule_reason = $request->reschedule_reason;
        $schedule->save();
        event(new MockTestRescheduled($schedule, $request->reschedule_reason, false));
        return redirect()->route('admin.mocktests.schedules', $schedule->mock_test_id)->withFlashSuccess('Mock test rescheduled successfully. Students have been notified.');
    }

    /**
     * View results for a specific schedule
     */
    public function viewResults($scheduleId)
    {
        if (! Gate::allows('mocktest_view')) {
            return abort(401);
        }

        $schedule = MockTestSchedule::findOrFail($scheduleId);
        $mockTest = $schedule->mockTest;
        $batch = $schedule->batch;

        $uids = [];
        $stbs = StudentTeacherBatch::where('bid', $batch->id)->get();
        
        foreach($stbs as $s){
            $uids[] = $s->uid;
        }

        $students = User::whereIn("id", $uids)->get();
        $users = [];
        
        foreach($students as $st){ 
            $result = $schedule->results()->where('student_id', $st->id)->first();
            
            if ($result) {
                $st->isAttempted = true;
                $st->totalQuestion = $result->total_questions;
                $st->totalCorrect = $result->total_correct;
                $st->totalIncorrect = $result->total_incorrect;
                $st->totalUnattempted = $result->total_unattempted;
                $st->score = $result->score;
                $st->percentage = $result->percentage;
                $st->result_id = $result->id;
            } else {
                $st->isAttempted = false;
                $st->totalQuestion = 0;
                $st->totalCorrect = 0;
                $st->totalIncorrect = 0;
                $st->totalUnattempted = 0;
                $st->score = 0;
                $st->percentage = 0;
            }

            $users[] = $st;
        }

        return view('backend.mocktests.result', compact('mockTest', 'schedule', 'users'));
    }

    /**
     * View detailed analysis for a specific student's attempt
     */
    public function testAnalysis($scheduleId, $studentId)
    {
        $schedule = MockTestSchedule::findOrFail($scheduleId);
        $mockTest = $schedule->mockTest;
        $user = User::findOrFail($studentId);

        $responses = $schedule->responses()
            ->where('student_id', $studentId)
            ->with(['question.options'])
            ->get();

        if(count($responses) == 0){
            return abort(404);
        }

        return view('backend.mocktests.analysis', compact('mockTest', 'schedule', 'responses', 'user'));
    }

    /**
     * View question reports
     */
    public function questionReports()
    {
        if (! Gate::allows('mocktest_access')) {
            return abort(401);
        }

        $reports = MockTestQuestionReport::with(['question', 'reporter', 'resolver'])
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.mocktests.question-reports', compact('reports'));
    }

    /**
     * Resolve a question report
     */
    public function resolveReport(Request $request, $id)
    {
        if (! Gate::allows('mocktest_edit')) {
            return abort(401);
        }

        $report = MockTestQuestionReport::findOrFail($id);
        
        if ($request->action === 'resolve') {
            $report->markAsResolved($request->admin_notes);
            return redirect()->back()->withFlashSuccess('Question report marked as resolved.');
        } elseif ($request->action === 'reject') {
            $report->markAsRejected($request->admin_notes);
            return redirect()->back()->withFlashSuccess('Question report rejected.');
        }

        return redirect()->back()->withFlashDanger('Invalid action.');
    }

    /**
     * Delete all selected Mock Tests at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('mocktest_delete')) {
            return abort(401);
        }
        
        if ($request->input('ids')) {
            $entries = MockTest::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    /**
     * Restore Mock Test from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('mocktest_delete')) {
            return abort(401);
        }
        
        $mockTest = MockTest::onlyTrashed()->findOrFail($id);
        $mockTest->restore();

        return back()->withFlashSuccess('Mock Test restored successfully');
    }

    /**
     * Permanently delete Mock Test from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('mocktest_delete')) {
            return abort(401);
        }
        
        $mockTest = MockTest::onlyTrashed()->findOrFail($id);
        $mockTest->forceDelete();

        return back()->withFlashSuccess('Mock Test permanently deleted');
    }
}
