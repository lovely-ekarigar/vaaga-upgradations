<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Exam;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\SubjectImport;
use App\Models\ExamSubject;
use App\Models\ExamQuestion;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionsImport;
use App\Jobs\GenerateQuestionsJob;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
class QuestionController extends Controller
{
    
    
   public function getPendingVerification(Request $request)
    {
        $courseId = $request->get('course_id');
        
        $query = Question::where('verification_status', 'pending');
        
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        
        $questions = $query
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($question) {
                return [
                    'id' => $question->id,
                    'question' => Str::limit(strip_tags(json_decode($question->question_text, true)['en'] ?? '', 100)),
                    'course_id' => $question->course_id
                ];
            });

        $courseName = $courseId ? Course::find($courseId)->title ?? 'Unknown Course' : 'All Courses';

        return response()->json([
            'questions' => $questions,
            'count' => $questions->count(),
            'course_name' => $courseName
        ]);
    }

    /**
     * Preview question for verification
     */
   public function preview($id)
{
    $question = Question::findOrFail($id);

    $questionText = json_decode($question->question_text, true)['en'] ?? 'No question text available';
    $options = json_decode($question->options, true) ?? [];
    $solution = json_decode($question->solution, true)['en'] ?? 'No solution available';
    
    $course = Course::find($question->course_id);
    $chapters = Lesson::where("course_id", $question->course_id)->get();

    $html = "
    <div class='question-content'>
        <h6 class='font-weight-bold'>Question:</h6>
        <div class='mb-3 p-2 bg-light rounded'>$questionText</div>
        
        <h6 class='font-weight-bold'>Options:</h6>
        <ul class='list-unstyled'>";

    foreach ($options as $key => $option) {
        $optionText = $option['en'] ?? '';
        $isCorrect = in_array($key, (array)$question->correct_answer) ? 'text-success font-weight-bold' : '';
        $html .= "<li class='mb-2 p-2 border rounded $isCorrect'><strong>Option $key:</strong> $optionText</li>";
    }

    $html .= "</ul>";

    // ✅ Chapter select dropdown
    $html .= "
        <h6 class='font-weight-bold'>Chapter:</h6>
        <select class='form-control form-select mb-3' id='currentCid' >
            <option value=''>-- Select Chapter --</option>";
    foreach ($chapters as $chapter) {
        $selected = $chapter->id == $question->chapter_id ? 'selected' : '';
        $html .= "<option value='{$chapter->id}' $selected>{$chapter->title}</option>";
    }
    $html .= "</select>";

    $html .= "
        <h6 class='font-weight-bold'>Solution:</h6>
        <div class='mb-2 p-2 bg-light rounded'>$solution</div>
        
        <div class='question-meta mt-3 p-2 bg-secondary text-white rounded'>
            <small>
                <strong>Marks:</strong> {$question->marks} | 
                <strong>Difficulty:</strong> " . ucfirst($question->difficulty) . " | 
                <strong>Created:</strong> " . $question->created_at->format('M d, Y') . "
            </small>
        </div>
    </div>";

    return response()->json(['html' => $html]);
}

    /**
     * Verify question
     */
    public function verify(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string|max:500',
            'chapter_id' => 'nullable'
        ]);

        try {
            $question = Question::findOrFail($request->question_id);

$question->verification_status = $request->status;
$question->verification_remarks = $request->remarks;
$question->chapter_id = $request->chapter_id;
$question->verified_by = auth()->id();
$question->verified_at = now();

$question->update();


            return response()->json([
                'success' => true,
                'message' => 'Question ' . $request->status . ' successfully!',
                'course_id' => $question->course_id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating question: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function importNow(Request $request){
        
        // dd($request->subject_id);
         $letters = ['1'=>'A', '2'=>'B', '3'=>'C', '4'=>'D'];
         SubjectImport::create(['subject_id'=>$request->subject_id]);

        $questions = ExamQuestion::where("subject_id",$request->subject_id)->get();
        $ab=['A'=>1,'B'=>2,'C'=>3,'D'=>4,'E'=>5];
        foreach ($questions as $q){
            
            $old = Question::where("old",$q->id)->first();
            
            if(!$old){
            $json = json_decode($q->options,true);
            $options=[];
          
$formatted = [];

foreach ($json['en'] as $numKey => $value) {
    
    if(isset($letters[$numKey])) {
       
        $letter = $letters[$numKey];
       
        $formatted[$letter] = [
            'en' => $value
        ];
        
        //  dd($formatted);
    }
}


$formattedJson = json_encode($formatted, JSON_UNESCAPED_UNICODE);
// dd($q);
            $que= new Question();
            $que->question_text= $q->question;
            $que->course_id = $request->course_id;
            $que->solution = $q->solution;
            $que->difficulty = $request->difficuly;
            $que->options =$formattedJson;
            $que->correct_answer =$q->answer ? $letters[json_decode($q->answer,true)[0]] : null;
            $que->old=$q->id;
            $que->save();
            // dd($que->id);
        }
        }
        return redirect()->back()->with("success","Questions import successful.");
    }
    
    public function import(Request $request)
{
  
  $subjects = ExamSubject::withCount('questions')->get();
//  $questions = ExamQuestion::all();
$courses = Course::where('published','1')->get();
//  dd($courses);
  
    return view('admin.questions.import',compact('subjects','courses'));
}


    public function generate(Request $request)
{
    $request->validate([
        'count' => 'required|integer|min:1|max:300',
        'subject_id' => 'required|exists:courses,id',
        'chapter_id' => 'required|exists:lessons,id',
        'difficulty' => 'required|string',
        'marks' => 'required|integer|min:1|max:100',
    ]);

    $total = $request->count;
    $batchSize = 10;

//     $course = Course::find($request->subject_id);
//     $chapter = Chapter::find($request->chapter_id);
//     $apiKey = env('OPENAI_API_KEY');
// $fullPrompt = "Generate {$total} multiple-choice questions in both English and Hindi for course {$course->name} and chapter {$chapter->name} with {$request->difficulty} difficulty"
//         . ($request->prompt ? " based on: {$request->prompt}" : "") . ".
//     Format the output strictly as JSON in the following structure:
//     {
//       \"questions\": [
//         {
//           \"question_text\": {\"en\":\"English text\",\"hi\":\"Hindi text\"},
//           \"options\": {
//             \"A\": {\"en\":\"English Option A\",\"hi\":\"Hindi Option A\"},
//             \"B\": {\"en\":\"English Option B\",\"hi\":\"Hindi Option B\"},
//             \"C\": {\"en\":\"English Option C\",\"hi\":\"Hindi Option C\"},
//             \"D\": {\"en\":\"English Option D\",\"hi\":\"Hindi Option D\"}
//           },
//           \"correct_answer\": [\"A\"],
//           \"solution\": {\"en\":\"English solution\",\"hi\":\"Hindi solution\"},
//           \"marks\": 1
//         }
//       ]
//     }
//     Do not include any markdown or code fences in your response.";
// $response = Http::withToken($apiKey)
//             ->timeout(180)
//             ->post("https://api.openai.com/v1/chat/completions", [
//                 "model" => "gpt-4o-mini",
//                 "messages" => [
//                     ["role" => "system", "content" => "You are an assistant that generates bilingual (English + Hindi) exam questions."],
//                     ["role" => "user", "content" => $fullPrompt],
//                 ],
//                 "temperature" => 0.8,
//                  "response_format" => [
//                 "type" => "json_schema",
//                 "json_schema" => [
//                     "name" => "questions_schema",
//                     "schema" => [
//                         "type" => "object",
//                         "properties" => [
//                             "questions" => [
//                                 "type" => "array",
//                                 "items" => [
//                                     "type" => "object",
//                                     "properties" => [
//                                         "question_text" => [
//                                             "type" => "object",
//                                             "properties" => [
//                                                 "en" => ["type" => "string"],
//                                                 "hi" => ["type" => "string"]
//                                             ],
//                                             "required" => ["en", "hi"]
//                                         ],
//                                         "options" => [
//                                             "type" => "object",
//                                             "properties" => [
//                                                 "A" => ["type" => "object",
//                                                     "properties" => [
//                                                         "en" => ["type" => "string"],
//                                                         "hi" => ["type" => "string"]
//                                                     ],
//                                                     "required" => ["en","hi"]
//                                                 ],
//                                                 "B" => ["type" => "object",
//                                                     "properties" => [
//                                                         "en" => ["type" => "string"],
//                                                         "hi" => ["type" => "string"]
//                                                     ],
//                                                     "required" => ["en","hi"]
//                                                 ],
//                                                 "C" => ["type" => "object",
//                                                     "properties" => [
//                                                         "en" => ["type" => "string"],
//                                                         "hi" => ["type" => "string"]
//                                                     ],
//                                                     "required" => ["en","hi"]
//                                                 ],
//                                                 "D" => ["type" => "object",
//                                                     "properties" => [
//                                                         "en" => ["type" => "string"],
//                                                         "hi" => ["type" => "string"]
//                                                     ],
//                                                     "required" => ["en","hi"]
//                                                 ]
//                                             ],
//                                             "required" => ["A","B","C","D"]
//                                         ],
//                                         "correct_answer" => [
//                                             "type" => "array",
//                                             "items" => ["type" => "string"]
//                                         ],
//                                         "solution" => [
//                                             "type" => "object",
//                                             "properties" => [
//                                                 "en" => ["type" => "string"],
//                                                 "hi" => ["type" => "string"]
//                                             ],
//                                             "required" => ["en","hi"]
//                                         ],
//                                         "marks" => ["type" => "integer"]
//                                     ],
//                                     "required" => ["question_text","options","correct_answer","solution","marks"]
//                                 ]
//                             ]
//                         ],
//                         "required" => ["questions"]
//                     ]
//                 ]
//             ]
//             ]);
            
//                 $data = $response->json();
//              $aiContent = $data['choices'][0]['message']['content'] ?? null;
//                 $questions = json_decode(trim($aiContent), true);
//                   foreach ($questions['questions'] as $q) {
//         try {
//             Question::create([
//                 'question_text'   => json_encode($q['question_text']),
//                 'options'         => json_encode($q['options']),
//                 'correct_answer'  => is_array($q['correct_answer'])
//                                         ? implode(',', $q['correct_answer'])
//                                         : $q['correct_answer'],
//                 'marks'           => $request->marks,
//                 'solution'        => json_encode($q['solution']),
//                 'difficulty'      => $request->difficulty,
//                 'subject_id'      => $request->subject_id,
//                 'chapter_id'      => $request->chapter_id,
//             ]);
//         } catch (\Throwable $e) {
//           dd($e->getMessage());
//         }
//     }
    
//  dd( $questions);           
    for ($i = 0; $i < $total; $i += $batchSize) {
        $count = min($batchSize, $total - $i);
        GenerateQuestionsJob::dispatch(
            $request->subject_id,
            $request->chapter_id,
            $count,
            $request->difficulty,
            $request->marks,
            $request->prompt
        );
    }

    return response()->json([
        'success' => true,
        'message' => "We are generating {$total} questions in the background. "
                   . "You can continue using other features meanwhile."
    ]);
}

  public function generatex(Request $request)
{
    $request->validate([
       
        'count' => 'required|integer|min:1|max:300',
    ]);
    
    
    
    $course = Course::find($request->subject_id);
    $chapter = Chapter::find($request->chapter_id);
// dd($course);
    $difficulty = $request->difficulty;
    $apiKey = env('OPENAI_API_KEY');
    $prompt = $request->prompt;
    $count = $request->count;

    $fullPrompt = "Generate {$count} multiple-choice questions in both English and Hindi for course {$course->name} and chapter {$chapter->name} with {$difficulty} difficulty based on: {$prompt}.
    Format the output strictly as JSON in the following structure:
    {
      \"questions\": [
        {
          \"question_text\": {\"en\":\"English text\",\"hi\":\"Hindi text\"},
          \"options\": {
            \"A\": {\"en\":\"English Option A\",\"hi\":\"Hindi Option A\"},
            \"B\": {\"en\":\"English Option B\",\"hi\":\"Hindi Option B\"},
            \"C\": {\"en\":\"English Option C\",\"hi\":\"Hindi Option C\"},
            \"D\": {\"en\":\"English Option D\",\"hi\":\"Hindi Option D\"}
          },
          \"correct_answer\": [\"A\"],
          \"solution\": {\"en\":\"English solution\",\"hi\":\"Hindi solution\"},
          \"marks\": 1
        }
      ]
    }";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer {$apiKey}",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS => json_encode([
            "model" => "gpt-4o-mini",
            "messages" => [
                ["role" => "system", "content" => "You are an assistant that generates bilingual (English + Hindi) exam questions."],
                ["role" => "user", "content" => $fullPrompt],
            ],
            "temperature" => 0.7,
            "response_format" => [
                "type" => "json_schema",
                "json_schema" => [
                    "name" => "questions_schema",
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "questions" => [
                                "type" => "array",
                                "items" => [
                                    "type" => "object",
                                    "properties" => [
                                        "question_text" => [
                                            "type" => "object",
                                            "properties" => [
                                                "en" => ["type" => "string"],
                                                "hi" => ["type" => "string"]
                                            ],
                                            "required" => ["en", "hi"]
                                        ],
                                        "options" => [
                                            "type" => "object",
                                            "properties" => [
                                                "A" => ["type" => "object",
                                                    "properties" => [
                                                        "en" => ["type" => "string"],
                                                        "hi" => ["type" => "string"]
                                                    ],
                                                    "required" => ["en","hi"]
                                                ],
                                                "B" => ["type" => "object",
                                                    "properties" => [
                                                        "en" => ["type" => "string"],
                                                        "hi" => ["type" => "string"]
                                                    ],
                                                    "required" => ["en","hi"]
                                                ],
                                                "C" => ["type" => "object",
                                                    "properties" => [
                                                        "en" => ["type" => "string"],
                                                        "hi" => ["type" => "string"]
                                                    ],
                                                    "required" => ["en","hi"]
                                                ],
                                                "D" => ["type" => "object",
                                                    "properties" => [
                                                        "en" => ["type" => "string"],
                                                        "hi" => ["type" => "string"]
                                                    ],
                                                    "required" => ["en","hi"]
                                                ]
                                            ],
                                            "required" => ["A","B","C","D"]
                                        ],
                                        "correct_answer" => [
                                            "type" => "array",
                                            "items" => ["type" => "string"]
                                        ],
                                        "solution" => [
                                            "type" => "object",
                                            "properties" => [
                                                "en" => ["type" => "string"],
                                                "hi" => ["type" => "string"]
                                            ],
                                            "required" => ["en","hi"]
                                        ],
                                        "marks" => ["type" => "integer"]
                                    ],
                                    "required" => ["question_text","options","correct_answer","solution","marks"]
                                ]
                            ]
                        ],
                        "required" => ["questions"]
                    ]
                ]
            ]
        ])
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (!isset($data['choices'][0]['message']['content'])) {
        return response()->json(['error' => 'Failed to generate questions'], 500);
    }

    $aiContent = $data['choices'][0]['message']['content'];
    $questions = json_decode($aiContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return response()->json(['error' => 'AI response not in JSON format', 'raw' => $aiContent], 500);
    }

    foreach ($questions['questions'] as $q) {
        $questionData = [
            'question_text'   => json_encode($q['question_text']),
            'options'         => json_encode($q['options']),
            'correct_answer'  => isset($q['correct_answer'])
                                    ? (is_array($q['correct_answer'])
                                        ? implode(',', $q['correct_answer'])
                                        : $q['correct_answer'])
                                    : '',
            'marks'           => $request->marks,
            'solution'        => json_encode($q['solution']),
            'difficulty'=>$difficulty,
            'subject_id' => $course->id,
             'chapter_id' => $chapter->id
        ];

        Question::create($questionData);
    }

    return response()->json(['success' => true, 'message' => 'Bilingual questions generated successfully']);
}



public function updateQuestionChapter(Request $request)
{
    // Validate input
    $request->validate([
        'question_id' => 'required|exists:questions,id',
        'chapter_id' => 'required', // nullable in case user deselects
    ]);

    // Find the question
    $question = Question::find($request->question_id);

    if (!$question) {
        return response()->json([
            'success' => false,
            'message' => 'Question not found.'
        ], 404);
    }

    // Update chapter
    $question->chapter_id = $request->chapter_id;
    $question->save();

    return response()->json([
        'success' => true,
        'message' => 'Chapter updated successfully.'
    ]);
}

public function add(){
    
  $courses = Course::where('published','1')->orderBy("sort_order",'asc')->get();  
    
      return view('admin.questions.add', compact('courses'));
}


public function saveQuestion(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'chapter_id' => 'required|exists:lessons,id',
        'question_text.en' => 'required|string',
      
        'marks' => 'required|integer|min:1',
        'options' => 'required|array|min:2',
        'correct_answer' => 'required|array|min:1',
    ]);

    $question = new Question();
    $question->course_id = $request->course_id;
    $question->is_prev_year = $request->is_prev_year;
    $question->chapter_id = $request->chapter_id;
    $question->question_text = json_encode($request->question_text);
    $question->options = json_encode($request->options);
    $question->solution = json_encode($request->solution);
    $question->marks = $request->marks;
    $question->difficulty = $request->difficulty;
    $question->correct_answer = implode(',', $request->correct_answer);
    $question->save();

    return redirect()->route('admin.exams.questions.index')->with('success', 'Question added successfully!');
}


  public function index(Request $request)
{
     $query = Question::orderBy("id","desc");
if($request->subject_id){
  

    $query->where("course_id",$request->subject_id);
}
 if ($request->chapter_id && $request->chapter_id!='all') {
        $query->where("chapter_id", $request->chapter_id);
    }
    if($request->difficulty){    
         $query->where("difficulty", $request->difficulty);
    }
    
      if($request->verification_status){    
         $query->where("verification_status", $request->verification_status);
    }
      if($request->key){    
         $query->where("question_text","like", "%".$request->key."%");
    }
    
//     if($request->subject_id){
  
// }else{
//     $questions=collect();
// }

  $questions = $query->paginate(25);
    // totals (respecting filter)
    $totalQuestions = $questions->count();
    $totalMarks     = $questions->sum('marks');
 
$subjects = Course::where('published','1')->orderBy("sort_order",'asc')->get();
// dd($questions[0]);
    return view('admin.questions.index', compact( 'questions', 'totalQuestions', 'totalMarks','subjects'));
}


    public function create($examId)
    {
        $exam = Exam::findOrFail($examId);
        return view('admin.questions.create', compact('exam'));
    }

    public function store(Request $request, $examId)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string',
            'marks' => 'nullable|integer|min:1'
        ]);

        $questionData = $request->only('question_text', 'options', 'correct_answer', 'marks');
        $questionData['exam_id'] = $examId;

        Question::create($questionData);

        return redirect()->route('admin.exams.questions.index', $examId)->with('success', 'Question added successfully.');
    }
    public function getBySubject($subjectId)
{
    $chapters = Lesson::where('course_id', $subjectId)->get();
    return response()->json($chapters);
}
    
     public function edit( $questionId)
    {
        
        $question = Question::findOrFail($questionId);

        return view('admin.questions.edit', compact( 'question'));
    }

    // Update a question
   public function update(Request $request, $questionId)
{
    $question = Question::findOrFail($questionId);
    
    // dd($request->all());
    // Validate the request data
    $validated = $request->validate([
        'question_text.en' => 'required|string',
       
        'options' => 'required|array|min:2',
        'options.*.en' => 'required|string',
        'correct_answer' => 'required|array|min:1',
        'marks' => 'required|integer|min:1',
    ]);

    // Prepare the data for update
    $data = [
        'question_text' => json_encode($request['question_text']),
        'solution' => json_encode($request['solution']),
        'options' => json_encode($request['options']),
        'correct_answer' => $request['correct_answer'][0],
        'marks' => $request['marks'],
           'is_prev_year' => $request['is_prev_year'],
         'difficulty' => $request['difficulty'],
         'verification_status' => $request['verification_status'],
         'chapter_id'=>$request->chapter_id
        
    ];

    // Update the question
    $question->update($data);

    return redirect()->back()
                    ->with('success', 'Question updated successfully.');
}

    // Delete a question
    public function destroy($questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->delete();

        return redirect()->back()->with('success', 'Question deleted successfully.');
    }

    public function showImportForm($examId)
{
    $exam = Exam::findOrFail($examId);
    return view('admin.questions.import', compact('exam'));
}


}
