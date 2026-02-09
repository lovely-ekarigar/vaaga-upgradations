<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Question;
use App\Models\QuestionsOption;
use App\Models\Test;
use App\Models\MockTest;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuestionsRequest;
use App\Http\Requests\Admin\UpdateQuestionsRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

class QuestionsController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Question.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Gate::allows('question_access')) {
            return abort(401);
        }

        if (request('show_deleted') == 1) {
            if (!Gate::allows('question_delete')) {
                return abort(401);
            }
            // SoftDeletes removed - return empty collection for deleted items
            $questions = collect(); // No soft deleted questions available
        } else {
            // Question model doesn't use SoftDeletes, so query normally
            // The global 'filter' scope will be applied automatically for teachers
            $questions = Question::orderBy('created_at', 'desc')->get();
        }

        $tests = Test::where('published','=',1)->pluck('title','id')->prepend('Please select', '');
        $mockTests = MockTest::orderByDesc('id')->pluck('title', 'id')->prepend('Please select', '');

        return view('backend.questions.index', compact('questions','tests','mockTests'));
    }


    /**
     * Display a listing of Questions via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;

        /*TODO:: Show All questions if Admin, Show related if  Teacher*/
        $questions = Question::query()->orderBy('created_at', 'desc');

        if ($request->filled('test_id')) {
            $test_id = $request->test_id;
            $questions->whereHas('tests', function ($q) use ($test_id) {
                $q->where('tests.id', $test_id);
            });
        }

        if (!auth()->user()->hasRole('administrator')) {
            $questions->where('user_id', '=', auth()->user()->id);
        }

        if ($request->show_deleted == 1) {
            if (!Gate::allows('question_delete')) {
                return abort(401);
            }
            // SoftDeletes removed - return empty query for deleted items
            $questions->whereRaw('1 = 0'); // Return no results
        }


        if (auth()->user()->can('question_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('question_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('question_delete')) {
            $has_delete = true;
        }

        return DataTables::of($questions)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.questions', 'label' => 'question', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.questions.show', ['question' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.questions.edit', ['question' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.questions.destroy', ['question' => $q->id, 'test_id' => $request->test_id??''])])
                        ->render();
                    $view .= $delete;
                }
                return $view;

            })
            ->editColumn('question', function ($q) {
                // UI expects Editor.js JSON; use question_json when available, fallback to question text.
                return $q->question_json ?: $q->question;
            })
            ->editColumn('question_image', function ($q) {
                return ($q->question_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->question_image) . '">' : 'N/A';
            })
            ->rawColumns(['question_image', 'actions'])
            ->make();
    }

    /**
     * Show the form for creating new Question.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('question_create')) {
            return abort(401);
        }
        $tests = \App\Models\Test::get()->pluck('title', 'id');
        $mockTests = MockTest::orderByDesc('id')->pluck('title', 'id');
        return view('backend.questions.create', compact('tests', 'mockTests'));
    }

    /**
     * Store a newly created Question in storage.
     *
     * @param  \App\Http\Requests\StoreQuestionsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionsRequest $request)
    {

        if (!Gate::allows('question_create')) {
            return abort(401);
        }
        $request = $this->saveFiles($request);
        $question = Question::create($request->all());
        $question->user_id = auth()->user()->id;
        $question->save();
        $question->tests()->sync(array_filter((array)$request->input('tests')));
        $question->mockTests()->sync(array_filter((array)$request->input('mock_tests')));

        for ($q = 1; $q <= 4; $q++) {
            $option = $request->input('option_text_' . $q, '');
            $explanation = $request->input('explanation_' . $q, '');
            if ($option != '') {
                QuestionsOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option,
                    'explanation' => $explanation,
                    'correct' => $request->input('correct_' . $q)
                ]);
            }
        }

        return redirect()->route('admin.questions.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }


    /**
     * Show the form for editing Question.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('question_edit')) {
            return abort(401);
        }
        $question = Question::findOrFail($id);
        $tests = \App\Models\Test::get()->pluck('title', 'id');

        $mockTests = MockTest::orderByDesc('id')->pluck('title', 'id');
        return view('backend.questions.edit', compact('question', 'tests', 'mockTests'));
    }

    /**
     * Update Question in storage.
     *
     * @param  \App\Http\Requests\UpdateQuestionsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionsRequest $request, $id)
    {
        if (!Gate::allows('question_edit')) {
            return abort(401);
        }
        $request = $this->saveFiles($request);
        $question = Question::findOrFail($id);
        $question->update($request->all());
        $question->user_id = auth()->user()->id;
        $question->save();
        $question->tests()->sync(array_filter((array)$request->input('tests')));
        $question->mockTests()->sync(array_filter((array)$request->input('mock_tests')));

        for ($q = 1; $q <= 4; $q++) {
            $option = $request->input('option_text_' . $q, '');
            $explanation = $request->input('explanation_' . $q, '');
            $option_id = $request->input('option_id_' . $q, '');
            $correct = ($request->input('correct_' . $q) == 1) ? 1 : 0;
            if ($option != '') {
                $option_data = QuestionsOption::find($option_id);
                if ($option_data) {
                    $option_data->question_id = $question->id;
                    $option_data->option_text = $option;
                    $option_data->explanation = $explanation;
                    $option_data->correct = $correct;
                    $option_data->save();
                }
            }
        }

        return redirect()->route('admin.questions.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    /**
     * Display Question.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('question_view')) {
            return abort(401);
        }
        $questions_options = \App\Models\QuestionsOption::where('question_id', $id)->get();
        $tests = \App\Models\Test::whereHas('questions',
            function ($query) use ($id) {
                $query->where('id', $id);
            })->get();

        $question = Question::findOrFail($id);

        return view('backend.questions.show', compact('question', 'questions_options', 'tests'));
    }


    /**
     * Remove Question from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('question_delete')) {
            return abort(401);
        }
        $question = Question::findOrFail($id);
        if(request()->get('test_id'))
            \DB::table('question_test')->where('question_id', $id)->where('test_id', request()->get('test_id'))->delete();
        else
            \DB::table('question_test')->where('question_id', $id)->delete();

        $question->delete();

        return redirect()->route('admin.questions.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Question at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('question_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Question::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    public function bulkAssignMockTests(Request $request)
    {
        if (!Gate::allows('question_edit')) {
            return abort(401);
        }

        $this->validate($request, [
            'mock_test_id' => 'required|integer|exists:mock_tests,id',
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:questions,id',
        ]);

        $mockTestId = (int) $request->mock_test_id;

        $query = Question::whereIn('id', $request->input('ids', []));
        if (!auth()->user()->hasRole('administrator')) {
            $query->where('user_id', auth()->user()->id);
        }

        $questions = $query->get();

        foreach ($questions as $question) {
            $question->mockTests()->syncWithoutDetaching([$mockTestId]);
        }

        return redirect()
            ->back()
            ->withFlashSuccess('Selected questions assigned to mock test.');
    }


    /**
     * Restore Question from storage.
     * NOTE: SoftDeletes removed - restore functionality disabled
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('question_delete')) {
            return abort(401);
        }
        // SoftDeletes removed - restore not available
        return redirect()->route('admin.questions.index')->withFlashWarning('Restore functionality is not available - soft deletes are disabled.');
    }

    /**
     * Permanently delete Question from storage.
     * NOTE: SoftDeletes removed - this now does regular delete
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('question_delete')) {
            return abort(401);
        }
        // SoftDeletes removed - just do regular delete
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('admin.questions.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }
}
