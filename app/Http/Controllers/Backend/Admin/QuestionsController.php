<?php

namespace App\Http\Controllers\Backend\Admin;  // ✅ Correct namespace

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionsController extends Controller  // ✅ Class name matches filename
{
    use FileUploadTrait;

    /**
     * Display a listing of the questions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Question::query();

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('key')) {
            $search = $request->key;
            $query->where(function ($q) use ($search) {
                $q->where('question_text', 'like', '%' . $search . '%')
                  ->orWhere('id', $search);
            });
        }

        $questions = $query->orderBy('id', 'desc')->paginate(25);

        $totalQuestions = Question::count();
        $totalMarks = Question::sum('marks');
        $pendingVerificationCount = Question::where('verification_status', 'pending')->count();
        $approvedCount = Question::where('verification_status', 'approved')->count();
        $rejectedCount = Question::where('verification_status', 'rejected')->count();

        $subjects = Course::where('published', 1)->get(['id', 'title']);
        $chapters = collect();

        return view('backend.questions.index', compact(
            'questions',
            'totalQuestions',
            'totalMarks',
            'pendingVerificationCount',
            'approvedCount',
            'rejectedCount',
            'subjects',
            'chapters'
        ));
    }

    /**
     * Show the form for creating a new question.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::where('published', 1)->get(['id', 'title']);
        $subjects = Subject::all(['id', 'title']);

        return view('backend.questions.create', compact('courses', 'subjects'));
    }

    /**
     * Store a newly created question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id'      => 'required|exists:courses,id',
            'chapter_id'     => 'required|exists:lessons,id',
            'question_text'  => 'required|array',
            'question_text.en' => 'required|string',
            'options'        => 'required|array|min:4|max:4',
            'options.*.en'   => 'required|string',
            'correct_answer' => 'required|array|min:1',
            'solution'       => 'nullable|array',
            'solution.en'    => 'nullable|string',
            'marks'          => 'required|integer|min:1',
            'difficulty'     => 'required|in:easy,medium,hard',
            'is_prev_year'   => 'required|boolean',
            'status'         => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request = $this->saveFiles($request);

        $data = $request->except(['_token', 'question_text', 'options', 'solution', 'correct_answer']);
        $data['question_text'] = json_encode($request->question_text);
        $data['options']       = json_encode($request->options);
        $data['solution']      = $request->filled('solution.en') ? json_encode($request->solution) : null;
        $data['correct_answer'] = implode(',', $request->correct_answer);
        $data['verification_status'] = $request->status;

        $question = Question::create($data);

        return redirect()->route('admin.exams.questions.index')
                         ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::findOrFail($id);
        return view('backend.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $chapters = Lesson::where('course_id', $question->course_id)->get(['id', 'title']);

        return view('backend.questions.edit', compact('question', 'chapters'));
    }

    /**
     * Update the specified question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'course_id'      => 'required|exists:courses,id',
            'chapter_id'     => 'required|exists:lessons,id',
            'question_text'  => 'required|array',
            'question_text.en' => 'required|string',
            'options'        => 'required|array|min:4|max:4',
            'options.*.en'   => 'required|string',
            'correct_answer' => 'required|array|min:1',
            'solution'       => 'nullable|array',
            'solution.en'    => 'nullable|string',
            'marks'          => 'required|integer|min:1',
            'difficulty'     => 'required|in:easy,medium,hard',
            'is_prev_year'   => 'required|boolean',
            'status'         => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('question_image')) {
            $request = $this->saveFiles($request);
        }

        $data = $request->except(['_token', '_method', 'question_text', 'options', 'solution', 'correct_answer']);
        $data['question_text'] = json_encode($request->question_text);
        $data['options']       = json_encode($request->options);
        $data['solution']      = $request->filled('solution.en') ? json_encode($request->solution) : null;
        $data['correct_answer'] = implode(',', $request->correct_answer);
        $data['verification_status'] = $request->status;

        $question->update($data);

        if ($request->status != 'pending') {
            $question->verified_at = now();
            $question->verified_by = auth()->id();
            $question->save();
        }

        return redirect()->route('admin.exams.questions.index')
                         ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('admin.exams.questions.index')
                         ->with('success', 'Question deleted successfully.');
    }

    /**
     * Load chapters for a given course (AJAX).
     *
     * @param  int  $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function chapters($courseId)
    {
        $chapters = Lesson::where('course_id', $courseId)->get(['id', 'title']);
        return response()->json($chapters);
    }

    /**
     * Get pending questions for verification (AJAX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pending(Request $request)
    {
        $courseId = $request->input('course_id');
        $query = Question::where('verification_status', 'pending');

        if ($courseId) {
            $query->where('course_id', $courseId);
        }

        $questions = $query->get(['id']);
        $pendingCount = $query->count();
        $approvedCount = Question::where('verification_status', 'approved')->count();
        $rejectedCount = Question::where('verification_status', 'rejected')->count();
        $totalCount = Question::count();

        return response()->json([
            'questions'       => $questions,
            'pending_count'   => $pendingCount,
            'approved_count'  => $approvedCount,
            'rejected_count'  => $rejectedCount,
            'total_count'     => $totalCount,
        ]);
    }

    /**
     * Preview a question (AJAX for verification modal).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function preview($id)
    {
        $question = Question::findOrFail($id);
        return view('backend.questions.preview', compact('question'));
    }

    /**
     * Verify a question (approve/reject) via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'status'      => 'required|in:approved,rejected',
            'remarks'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $question = Question::find($request->question_id);
        $question->verification_status = $request->status;
        $question->verification_remarks = $request->remarks;
        $question->verified_at = now();
        $question->verified_by = auth()->id();
        $question->save();

        return response()->json(['success' => true, 'message' => 'Question ' . $request->status]);
    }

    /**
     * Update chapter via AJAX (from index page).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateChapter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'chapter_id'  => 'required|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $question = Question::find($request->question_id);
        $question->chapter_id = $request->chapter_id;
        $question->save();

        return response()->json(['success' => true, 'message' => 'Chapter updated.']);
    }
}
