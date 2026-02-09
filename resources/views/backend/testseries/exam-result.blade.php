<?php

use App\Models\QuestionReport;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exam Result</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Prevent Tailwind preflight from overriding the site's existing theme styles
    tailwind.config = { corePlugins: { preflight: false } };
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body { font-size: 10pt; line-height: 1.3; margin: 0; padding: 0; background: white; color: #333; }
    h1 { font-size: 14pt; }
    h2 { font-size: 12pt; }
    .print-container { width: 100% !important; padding: 0 !important; margin: 0 !important; }
    .question-grid { display: grid; grid-template-columns: repeat(1, 1fr); gap: 10px; margin-bottom: 15px; }
    .question-item { border: 1px solid #ddd; padding: 8px; border-radius: 4px; font-size: 10pt; background: #fff; break-inside: avoid; page-break-inside: avoid; }
    .summary-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; }
    .score-circle { background: radial-gradient(closest-side, white 79%, transparent 80% 100%), conic-gradient(#4f46e5 calc(var(--score-percent) * 1%), #e5e7eb 0); }
    @media print {
      body { margin: 0; padding: 0; background: white; width: 100%; font-size: 10pt; }
      .no-print { display: none !important; }
      .print-shadow { box-shadow: none; border: 1px solid #ddd; }
      .print-container { width: 100%; margin: 0; padding: 0.25in; box-sizing: border-box; }
      .question-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
      .question-item { break-inside: avoid; page-break-inside: avoid; margin-bottom: 10px; }
      .question-item:nth-child(4n) { margin-bottom: 0; }
      .question-item > div { break-inside: avoid; }
    }
  </style>
</head>
<body class="min-h-screen">
  @php
    /**
     * SAFETY: Prevent "Undefined offset" / "Undefined index" errors when option mapping differs.
     * Some datasets store answers as 1..4 while correct_answer/options keys may be A/B/C/D (or vice-versa).
     */
    $optionMap = (isset($optionMap) && is_array($optionMap)) ? $optionMap : [];
    if (count($optionMap) < 2) {
      $optionMap = [
        'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4,
        'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4,
        1 => 1, 2 => 2, 3 => 3, 4 => 4,
      ];
    }

    /**
     * Normalize values to a 0-based option index (0..3) WITHOUT collisions.
     *
     * Some questions store option keys as 1..4, while others may use 0..3 or A-D.
     * If we blindly treat numeric "3" as 0-based and numeric "4" as 1-based, both become index 3,
     * causing "double correct tick". We fix this by detecting option key mode per question.
     */
    $coerceScalar = function ($value) {
      if ($value === null) return null;
      if (is_array($value)) $value = $value[0] ?? null;
      if ($value === null) return null;
      if (is_string($value) && str_contains($value, ',')) $value = trim(explode(',', $value)[0]);
      return $value;
    };

    $letterToIndex = function ($value) {
      if (!is_string($value)) return null;
      $v = strtoupper(trim($value));
      $letters = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
      return $letters[$v] ?? null;
    };

    $detectOptionKeyMode = function (array $opts) {
      $keys = array_keys($opts);
      $hasLetters = false;
      $nums = [];

      foreach ($keys as $k) {
        if (is_string($k)) {
          $t = trim($k);
          if (preg_match('/^[A-Da-d]$/', $t)) { $hasLetters = true; continue; }
          if (preg_match('/^\d+$/', $t)) { $nums[] = (int) $t; continue; }
        }
        if (is_int($k) || is_float($k)) $nums[] = (int) $k;
      }

      if ($hasLetters) return 'letter';
      if (empty($nums)) return 'unknown';

      $min = min($nums);
      $max = max($nums);
      if ($min >= 1 && $max <= 4) return 'one';   // 1..4
      if ($min >= 0 && $max <= 3) return 'zero';  // 0..3
      return ($min >= 1) ? 'one' : 'zero';
    };

    $normalizeUserAnswerIndex = function ($value) use ($coerceScalar, $letterToIndex, $optionMap) {
      $value = $coerceScalar($value);
      if ($value === null || $value === '') return null;

      if (is_numeric($value)) {
        $n = (int) $value;
        if ($n >= 0 && $n <= 3) return $n;       // current UI
        if ($n >= 1 && $n <= 4) return $n - 1;   // legacy
      }

      $li = $letterToIndex($value);
      if ($li !== null) return $li;

      if (is_string($value) && array_key_exists($value, $optionMap) && is_numeric($optionMap[$value])) {
        $m = (int) $optionMap[$value];
        if ($m >= 0 && $m <= 3) return $m;
        if ($m >= 1 && $m <= 4) return $m - 1;
      }

      return null;
    };

    $normalizeCorrectAnswerIndex = function ($value, string $mode) use ($coerceScalar, $letterToIndex, $optionMap) {
      $value = $coerceScalar($value);
      if ($value === null || $value === '') return null;

      $li = $letterToIndex($value);
      if ($li !== null) return $li;

      if (is_numeric($value)) {
        $n = (int) $value;
        if ($mode === 'one' && $n >= 1 && $n <= 4) return $n - 1;
        if ($mode === 'zero' && $n >= 0 && $n <= 3) return $n;
        if ($n >= 1 && $n <= 4) return $n - 1;
        if ($n >= 0 && $n <= 3) return $n;
      }

      if (is_string($value) && array_key_exists($value, $optionMap) && is_numeric($optionMap[$value])) {
        $m = (int) $optionMap[$value];
        if ($mode === 'one' && $m >= 1 && $m <= 4) return $m - 1;
        if ($mode === 'zero' && $m >= 0 && $m <= 3) return $m;
        if ($m >= 1 && $m <= 4) return $m - 1;
        if ($m >= 0 && $m <= 3) return $m;
      }

      return null;
    };

    $normalizeOptionKeyIndex = function ($key, string $mode) use ($letterToIndex) {
      $li = $letterToIndex($key);
      if ($li !== null) return $li;
      if (is_numeric($key)) {
        $n = (int) $key;
        return ($mode === 'one') ? ($n - 1) : $n;
      }
      return null;
    };
  @endphp
  <div class="no-print fixed top-4 right-4 z-10">
    <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center">
      Print Result
    </button>
  </div>

  <main class="max-w-6xl mx-auto py-4 px-3 space-y-5 print-container">
    <!-- Header -->
    <div class="text-center summary-section">
      <h1 class="text-xl font-bold text-indigo-800">EXAMINATION RESULT</h1>
      <div class="w-16 h-1 bg-indigo-600 mx-auto mt-1"></div>
    </div>

    <!-- Summary -->
    <div class="bg-white rounded-lg shadow p-3 print-shadow summary-section">
      <div class="flex flex-col md:flex-row items-center md:items-start gap-4">
        <div class="flex-1">
          <h1 class="text-base font-bold text-gray-900 mb-1">{{ $exam->exam->name ?? 'Exam Result' }}</h1>
          <p class="text-gray-600 text-sm"><span class="font-medium">Candidate:</span> {{ $exam->user->name ?? 'Student' }}</p>
          <p class="text-gray-600 text-sm"><span class="font-medium">Date:</span> {{ date('F j, Y', strtotime($exam->created_at ?? now())) }}</p>
          @php
            $hours   = floor($exam->time_spent / 3600);
            $minutes = floor(($exam->time_spent % 3600) / 60);
            $seconds = $exam->time_spent % 60;
          @endphp
          <p class="text-gray-600 text-sm">Time Spent : {{ $hours }}h {{ $minutes }}m {{ $seconds }}s</p>

          <div class="summary-grid mt-2 text-center">
            <div class="p-2 bg-green-50 rounded border border-green-200">
              <div class="text-base font-bold text-green-700">{{ $correct }}</div>
              <div class="text-xs text-gray-600">Correct</div>
            </div>
            <div class="p-2 bg-red-50 rounded border border-red-200">
              <div class="text-base font-bold text-red-700">{{ $wrong }}</div>
              <div class="text-xs text-gray-600">Wrong</div>
            </div>
            <div class="p-2 bg-yellow-50 rounded border border-yellow-200">
              <div class="text-base font-bold text-yellow-700">{{ $notAttempted }}</div>
              <div class="text-xs text-gray-600">Not Attempted</div>
            </div>
            <div class="p-2 bg-indigo-50 rounded border border-indigo-200">
              <div class="text-base font-bold text-indigo-700">{{ number_format(($score / max(1,$maxScore)) * 100, 2) }}%</div>
              <div class="text-xs text-gray-600">Percentage</div>
            </div>
          </div>
        </div>

        <!-- Score Circle -->
        <div class="flex flex-col items-center">
          <div class="w-24 h-24 rounded-full flex items-center justify-center score-circle"
               style="--score-percent: {{ ($score / max(1, $maxScore)) * 100 }}">
            <div class="text-center">
              <div class="text-lg font-bold text-indigo-700">{{ $score }}</div>
              <div class="text-[10px] text-gray-500">out of {{ $maxScore }}</div>
            </div>
          </div>
          <div class="mt-1 px-2 py-0.5 bg-indigo-100 rounded-full text-[10px] text-indigo-800 font-medium">
            @if(($score / max(1, $maxScore)) * 100 >= 80) Excellent
            @elseif(($score / max(1, $maxScore)) * 100 >= 60) Good
            @elseif(($score / max(1, $maxScore)) * 100 >= 40) Average
            @else Needs Improvement
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Detailed Questions -->
    <div class="bg-white rounded-lg shadow p-3 print-shadow">
      <h2 class="text-base font-semibold text-gray-900 mb-2 border-b pb-1">Detailed Question Analysis</h2>
      <div class="question-grid">
        @foreach($questions as $k=>$q)
        <div class="question-item">
          <div class="flex justify-between items-start">
            <h3 class="font-medium text-gray-900 text-sm">
              <span class="inline-flex items-center justify-center w-5 h-5 bg-indigo-100 text-indigo-800 rounded-full mr-1 text-xs">
                {{$loop->index+1}}
              </span>
              {!! json_decode($q->question_text,true)['en'] !!}
            </h3>
            <div class="ml-1 flex-shrink-0 text-[10px]">
              @php
                $userAnswerRaw = $answers[$q->id] ?? null;
                $correctAnswerRaw = $q->correct_answer ?? null;
                $opts = json_decode($q->options, true) ?? [];
                $optionKeyMode = $detectOptionKeyMode(is_array($opts) ? $opts : []);

                $userIndex = $normalizeUserAnswerIndex($userAnswerRaw);
                $correctIndex = $normalizeCorrectAnswerIndex($correctAnswerRaw, $optionKeyMode);
              @endphp
              @if(is_null($userAnswerRaw) || $userAnswerRaw === '')
                <span class="px-1.5 py-0.5 rounded-full bg-yellow-100 text-yellow-800">Not Attempted</span>
              @elseif(!is_null($userIndex) && !is_null($correctIndex) && $userIndex === $correctIndex)
                <span class="px-1.5 py-0.5 rounded-full bg-green-100 text-green-800">Correct</span>
              @else
                <span class="px-1.5 py-0.5 rounded-full bg-red-100 text-red-800">Incorrect</span>
              @endif
            </div>
          </div>

          <!-- Options -->
          <div class="mt-1 space-y-1">
            @foreach($opts as $key => $opt)
              @php
                $optionIndex = $normalizeOptionKeyIndex($key, $optionKeyMode);
                $isUser = !is_null($optionIndex) && !is_null($userIndex) && $userIndex === $optionIndex;
                $isCorrect = !is_null($optionIndex) && !is_null($correctIndex) && $correctIndex === $optionIndex;
                $optText = is_array($opt) ? ($opt['en'] ?? reset($opt)) : $opt;
              @endphp
              <div class="p-1.5 border rounded flex items-center text-xs
                  @if($isUser && $isCorrect) bg-green-50 border-green-400
                  @elseif($isUser && !$isCorrect) bg-red-50 border-red-400
                  @elseif($isCorrect) bg-green-50 border-green-300
                  @else bg-gray-50 border-gray-200
                  @endif">
                <div class="flex-shrink-0 w-4 h-4 rounded-full flex items-center justify-center mr-1 text-[9px]
                    @if($isUser && $isCorrect) bg-green-500 text-white
                    @elseif($isUser && !$isCorrect) bg-red-500 text-white
                    @elseif($isCorrect) bg-green-500 text-white
                    @else bg-gray-200 text-gray-700
                    @endif">
                  {{ $key }}
                </div>
                <div class="flex-1">
                  <span>{!! $optText !!}</span>
                </div>
                <div class="ml-1 text-[9px] font-medium">
                  @if($isUser && $isCorrect) ✅
                  @elseif($isUser) ❌
                  @elseif($isCorrect) ✅
                  @endif
                </div>
              </div>
            @endforeach
          </div>

          <!-- Solution -->
          @if(!empty($q->solution))
            <!--<div class="mt-1 p-1.5 bg-blue-50 border border-blue-200 rounded text-xs">-->
            <!--  <span class="font-medium text-blue-800">Explanation:</span>-->
            <!--  <div>{{ json_decode($q->solution,true)['en'] ?? 'No explanation available.' }}</div>-->
            <!--  @if(isset(json_decode($q->solution,true)['hi']))-->
            <!--    <div class="mt-1 text-gray-600">{{ json_decode($q->solution,true)['hi'] }}</div>-->
            <!--  @endif-->
            <!--</div>-->
          @endif

          <!-- Report Button -->
          <div class="flex justify-end">
              <?php $qrp = QuestionReport::where('exam_id',$exam->id)->where('question_id',$q->id)->first(); ?>
              @if($qrp)
              <div class=" text-[red] font-medium py-2 px-2 mt-2 rounded-lg shadow-md flex items-center w-28"> Reported</div>
              @else
              
              
            <button 
              class="report-btn bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-2 mt-2 rounded-lg shadow-md flex items-center w-28"
              data-question-id="{{ $q->id }}"
              data-question-no="{{ $loop->index+1 }}"
              data-question-text="{{ json_decode($q->question_text,true)['en'] }}">
              Report Error
            </button>
            @endif
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Footer -->
    <div class="text-center text-gray-500 text-xs mt-4 no-print">
      <p>This is a computer-generated report. No signature is required.</p>
    </div>
  </main>

  <!-- Report Error Modal -->
  <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-3">Report Error</h2>

      <!-- Question Display -->
      <div class="mb-3">
        <p class="text-sm text-gray-700 mb-1">
          <span class="font-semibold">Question <span class="text-indigo-600" id="modalQuestionNo"></span>:</span>
        </p>
        <div class="p-3 bg-gray-50 border rounded text-sm text-gray-800" id="modalQuestionText"></div>
      </div>

      <!-- Message -->
      <textarea rows="5" 
        id="reportMessage"
        class="w-full border rounded p-3 text-sm focus:ring-2 focus:ring-indigo-500"
        placeholder="Describe the issue..."></textarea>

      <!-- Buttons -->
      <div class="mt-4 flex justify-end gap-2">
        <button id="closeModal" class="px-4 py-2 text-sm rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
        <button id="submitReport" class="px-4 py-2 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700">Submit</button>
      </div>
    </div>
  </div>

<script>
$(document).ready(function() {
    let currentQuestionId = null;
    let currentQuestionNo = null;
    let currentQuestionText = null;

    // Open modal
    $(".report-btn").on("click", function() {
        currentQuestionId = $(this).data("question-id");
        currentQuestionNo = $(this).data("question-no");
        currentQuestionText = $(this).data("question-text");

        $("#modalQuestionNo").text(currentQuestionNo);
        $("#modalQuestionText").html(currentQuestionText);

        $("#reportModal").removeClass("hidden").addClass("flex");
    });

    // Close modal
    $("#closeModal").on("click", function() {
        $("#reportModal").addClass("hidden").removeClass("flex");
        $("#reportMessage").val("");
    });

    // Submit report
    $("#submitReport").on("click", function() {
        let message = $("#reportMessage").val().trim();
        if(message === "") {
            alert("Please enter a message before submitting.");
            return;
        }

        $.ajax({
            url: "/user/report-question",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                exam_id: "{{ $exam->id }}",
                question_id: currentQuestionId,
                question_no: currentQuestionNo,
                question_text: currentQuestionText,
                message: message
            },
            success: function(response){
                alert(response.message);
                $("#reportModal").addClass("hidden").removeClass("flex");
                $("#reportMessage").val("");
                $('button[data-question-id="'+currentQuestionId+'"]').parent().html('<div class=" text-[red] font-medium py-2 px-2 mt-2 rounded-lg shadow-md flex items-center w-28"> Reported</div>')
            },
            error: function(){
                alert("Something went wrong. Please try again.");
            }
        });
    });
});
</script>
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "sd7aqctqd1");
</script>
</body>
</html>
