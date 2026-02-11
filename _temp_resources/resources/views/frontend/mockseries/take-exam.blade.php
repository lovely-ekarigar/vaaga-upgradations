<?php

// Encode the data for JavaScript usage
$examDataJson = json_encode($examData);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Exam Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Hind:wght@400;500;600;700&display=swap');
    
    :root {
      --primary: #4f46e5;
      --primary-dark: #4338ca;
      --secondary: #7e22ce;
      --success: #16a34a;
      --warning: #eab308;
      --danger: #dc2626;
      --light: #f8fafc;
      --dark: #1e293b;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      color: #334155;
      height: 100vh;
      overflow: hidden;
      font-size: 14px;
    }
    #fullscreenOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #282828fa;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }

  #fullscreenBtn {
    padding: 20px 40px;
    font-size: 24px;
    color: #fff;
    background: #007bff;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    transition: background 0.3s;
  }

  #fullscreenBtn:hover {
    background: #0056b3;
  }
    .hindi {
      font-family: 'Hind', sans-serif;
      font-weight: 500;
    }
    
    .card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: all 0.2s ease;
    }
    
    .btn-primary {
      background: var(--primary);
      color: white;
      border-radius: 6px;
      padding: 8px 16px;
      font-weight: 500;
      transition: all 0.2s ease;
      font-size: 13px;
    }
    
    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-1px);
    }
    
    .btn-secondary {
      background: #f1f5f9;
      color: #475569;
      border-radius: 6px;
      padding: 8px 16px;
      font-weight: 500;
      transition: all 0.2s ease;
      font-size: 13px;
    }
    
    .btn-secondary:hover {
      background: #e2e8f0;
    }
    
    .option-container {
      /*border: 1.5px solid #e2e8f0;*/
      /*border-radius: 8px;*/
      padding: 4px 8px;
      text-align: left;
      transition: all 0.15s ease;
      background: white;
      font-size: 13px;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      cursor: pointer;
    }
    
    .option-container:hover {
      border-color: var(--primary);
      background: #f0f4ff;
    }
    
    .option-container.selected {
      border-color: var(--primary);
      background: #e6eeff;
    }
    
    .custom-radio {
      width: 18px;
      height: 18px;
      border: 2px solid #cbd5e1;
      border-radius: 50%;
      margin-right: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    
    .custom-radio::after {
      content: "";
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: var(--primary);
      display: none;
    }
    
    .option-container.selected .custom-radio::after {
      display: block;
    }
    
    .option-container.selected .custom-radio {
      border-color: var(--primary);
    }
    
    .palette-btn {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 500;
      transition: all 0.15s ease;
      background: #f1f5f9;
      color: #475569;
      font-size: 13px;
    }
    
    .palette-btn:hover {
      background: #e2e8f0;
    }
    
    .palette-btn.current {
      background: var(--primary);
      color: white;
      transform: scale(1.05);
    }
    
    .palette-btn.answered {
      background: var(--success);
      color: white;
    }
    
    .palette-btn.marked {
      background: var(--warning);
      color: white;
    }
    
    .progress-bar {
      height: 5px;
      border-radius: 3px;
      background: #e2e8f0;
      overflow: hidden;
      width: 100%;
    }
    
    .progress-fill {
      height: 100%;
      background: var(--primary);
      border-radius: 3px;
      transition: width 0.4s ease;
    }
    
    .timer-warning {
      color: var(--danger);
      animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
      0% { opacity: 1; }
      50% { opacity: 0.7; }
      100% { opacity: 1; }
    }
    
    .user-photo {
      width: 70px;
      height: 70px;
      border-radius: 8px;
      object-fit: fill;
      border: 2px solid #e2e8f0;
    }
    
    .palette-container {
      overflow-y: auto;
      max-height: 35vh;
      /*max-height: 10%;*/
    }
    
    .options-container {
      overflow-y: auto;
      max-height: 60%;
      padding-right: 5px;
    }
    
    .options-container::-webkit-scrollbar {
      width: 6px;
    }
    
    .options-container::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    
    .options-container::-webkit-scrollbar-thumb {
      background: #c5c5c5;
      border-radius: 10px;
    }
    
    .options-container::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
    
    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      background: white;
      border-left: 4px solid var(--danger);
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      transform: translateX(100%);
      transition: transform 0.3s ease;
      z-index: 1000;
      max-width: 350px;
    }
    
    .notification.show {
      transform: translateX(0);
    }
    
    .notification-content {
      display: flex;
      align-items: flex-start;
    }
    
    .notification-icon {
      color: var(--danger);
      font-size: 20px;
      margin-right: 12px;
      margin-top: 2px;
    }
    
    .timer-container {
      min-width: 85px;
      text-align: center;
    }
  </style>
</head>
<body class="flex flex-col ">
    
  
  <!-- Anti-Cheating Notification -->
  <div id="cheatNotification" class="notification">
    <div class="notification-content">
      <div class="notification-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div>
        <div class="font-semibold">Anti-Cheating Alert!</div>
        <div class="text-sm mt-1">Tab switching detected. This incident has been recorded.</div>
      </div>
    </div>
  </div>

  <!-- Header -->
  <header class="bg-white shadow-sm py-3 px-5">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="font-bold text-lg text-indigo-600 flex items-center">
          <img src="https://vaagaacademy.com/newassets/img/logo.webp" style="height:60px;" />
        </div>
        <div class="relative">
          <select id="subjectSelect" class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-1.5 px-3 pr-7 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 text-sm font-medium">
            <?php 
            // Sort subjects by ID in ascending order
            $sortedSubjects = [];
            foreach ($examData['subjects'] as $key => $subject) {
                $sortedSubjects[$key] = [
                    'key' => $key,
                    'name' => $subject['name']
                ];
            }
            ksort($sortedSubjects); // Sort by subject ID
            
            foreach ($sortedSubjects as $subject): ?>
              <option value="<?= $subject['key'] ?>"><?= $subject['name'] ?></option>
            <?php endforeach; ?>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <i class="fas fa-chevron-down text-xs"></i>
          </div>
        </div>
      </div>
      
      <div class="flex items-center gap-4">
          
        <div class="flex items-center bg-red-50 text-red-700 py-1.5 px-3 text-lg rounded timer-container">
          <i class="fas fa-clock mr-1.5"></i>
          <span class="font-medium"><span id="timer">59:59</span></span>
        </div>
      </div>
      
      <div class="flex items-center gap-4">
          <img src="<?= $examData['student']['admit_card_photo'] ?>" class="user-photo" alt="Admit Card Photo" title="Admit Card Photo">
            <?php if($examData['student']['database_photo']): ?>
            <img src="<?= $examData['student']['database_photo'] ?>" class="user-photo" alt="Database Photo" title="Database Photo">
            <?php endif; ?>
       
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <div class="flex flex-1  p-3 gap-3" style="overflow:scroll;">
    <!-- Question Area -->
    <main class="flex-1 flex flex-col">
      <div class="card p-4 flex-1 flex flex-col">
        <!-- Progress bar -->
        <div class="mb-4">
          <div class="flex justify-between text-xs text-gray-500 mb-1">
            <span>Question <span id="current-num">1</span> of <span id="total-num">5</span></span>
            <span><span id="progress-percent">0</span>% Completed</span>
          </div>
          <div class="progress-bar">
            <div id="progress-fill" class="progress-fill" style="width: 0%"></div>
          </div>
        </div>
        
        <!-- Question -->
        <h2 id="question-en" class="text-[16px] font-semibold mb-2 text-gray-800">Question will appear here</h2>
        
        <!-- Options Container (Scrollable) -->
        <div id="options-container" class="options-container" style="margin-bottom:140px;">
          <!-- Options will be dynamically inserted here -->
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-10 flex justify-between pt-3 border-t border-gray-100" style="position: absolute;
    bottom: 35px;
    display: flex;
    width: 70%;">
          <button id="prevBtn" class="btn-secondary">
            <i class="fas fa-arrow-left mr-1.5"></i> Previous
          </button>
          
          <button id="clearBtn" class="btn-secondary">
            <i class="fas fa-times-circle mr-1.5"></i> Clear Response
          </button>
          
          <button id="markBtn" class="btn-secondary">
            <i class="far fa-flag mr-1.5"></i> Mark for Review
          </button>
          
          <button id="nextBtn" class="btn-primary">
            Next <i class="fas fa-arrow-right ml-1.5"></i>
          </button>
        </div>
      </div>
    </main>

    <!-- Sidebar -->
    <aside class="w-72 flex flex-col gap-3">
      <!-- User Info Card with Photos -->
      <div class="card p-3">
        <div class="flex items-center justify-between ">
          <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-md mr-2">
              <i class="fas fa-user"></i>
            </div>
            <div>
              <div class="font-semibold text-sm"><?= $examData['student']['name'] ?></div>
            </div>
          </div>
        </div>
        
        
      </div>
      
      <!-- Question Palette -->
      <div class="card p-3 flex-1 flex flex-col">
        <div class="font-semibold text-gray-700 text-sm mb-3 flex items-center justify-between">
          <span>Questions Palette</span>
          <span class="text-xs font-normal text-gray-500" id="answered-count">0/5 Answered</span>
        </div>
        
        <div id="palette" class="palette-container grid grid-cols-5 gap-2">
          <!-- Palette buttons will be generated here -->
        </div>
        
        <div class="mt-4 pt-3 border-t border-gray-100">
          <div class="grid grid-cols-2 gap-2 text-xs">
            <div class="flex items-center">
              <div class="w-3 h-3 rounded-full bg-indigo-500 mr-1.5"></div>
              <span class="text-gray-500">Current</span>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 rounded-full bg-green-500 mr-1.5"></div>
              <span class="text-gray-500">Answered</span>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 rounded-full bg-gray-200 mr-1.5"></div>
              <span class="text-gray-500">Unanswered</span>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 rounded-full bg-yellow-400 mr-1.5"></div>
              <span class="text-gray-500">Marked for Review</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Actions -->
      <div class="card p-3">
        <button id="submitBtn" class="w-full py-2 bg-red-500 text-white rounded font-semibold text-sm hover:bg-red-600 transition-all">
          <i class="fas fa-paper-plane mr-1.5"></i> Submit Exam
        </button>
      </div>
    </aside>
  </div>

  <script>
    // PHP data converted to JavaScript
    const examData = <?= $examDataJson ?>;
    
    // Ensure subjects exists and each subject has a questions array
    if (!examData.subjects || typeof examData.subjects !== 'object') {
      examData.subjects = {};
    }
    Object.keys(examData.subjects).forEach(key => {
      if (!Array.isArray(examData.subjects[key].questions)) {
        examData.subjects[key].questions = [];
      }
    });
    
    // Sort subjects by ID in ascending order
    const sortedSubjects = {};
    Object.keys(examData.subjects)
        .map(key => ({ key, ...examData.subjects[key] }))
        .filter(s => s && (Array.isArray(s.questions) ? s.questions : []))
        .sort((a, b) => (a.id || 0) - (b.id || 0))
        .forEach(subject => {
            sortedSubjects[subject.key] = { ...subject, questions: subject.questions || [] };
        });

    const subjectKeys = Object.keys(sortedSubjects);
    if (subjectKeys.length === 0) {
      document.body.innerHTML = '<div class="p-8 text-center"><p class="text-red-600 font-medium">No questions available for this exam. Please contact support.</p><a href="/user/my-mock-series" class="text-indigo-600 underline mt-4 inline-block">Back to Mock Series</a></div>';
      throw new Error('No subjects with questions');
    }
    
    let currentSubject = subjectKeys[0];
    let currentQuestion = 0;
    let userAnswers = {};
    let markedQuestions = {};
    let timerInterval;
    const totalTime = examData.exam_settings.total_time;
    let timeLeft = totalTime;
    let tabSwitchCount = 0;
    let examSubmitted = false;

    // DOM elements
    const subjectSelect = document.getElementById("subjectSelect");
    const questionEnEl = document.getElementById("question-en");
    const optionsContainer = document.getElementById("options-container");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const clearBtn = document.getElementById("clearBtn");
    const markBtn = document.getElementById("markBtn");
    const paletteEl = document.getElementById("palette");
    const timerEl = document.getElementById("timer");
    const currentNumEl = document.getElementById("current-num");
    const totalNumEl = document.getElementById("total-num");
    const progressFillEl = document.getElementById("progress-fill");
    const progressPercentEl = document.getElementById("progress-percent");
    const answeredCountEl = document.getElementById("answered-count");
    const submitBtn = document.getElementById("submitBtn");
    const cheatNotification = document.getElementById("cheatNotification");

    // Get sorted subject keys in ascending order of ID
    function getSortedSubjectKeys() {
        return Object.keys(sortedSubjects).sort((a, b) => (sortedSubjects[a] && sortedSubjects[b] ? (sortedSubjects[a].id || 0) - (sortedSubjects[b].id || 0) : 0));
    }

    // Calculate total questions across all subjects
    function getTotalQuestions() {
      let total = 0;
      const subjectKeys = getSortedSubjectKeys();
      for (const subjectKey of subjectKeys) {
        const q = sortedSubjects[subjectKey] && sortedSubjects[subjectKey].questions;
        total += Array.isArray(q) ? q.length : 0;
      }
      return total;
    }

    // Calculate total answered questions across all subjects
    function getTotalAnswered() {
      return Object.keys(userAnswers).length;
    }

    // Get current global question number (across all subjects)
    function getCurrentGlobalQuestionNumber() {
      let globalCount = 0;
      const subjectKeys = getSortedSubjectKeys();
      
      for (const subjectKey of subjectKeys) {
        if (subjectKey === currentSubject) {
          return globalCount + currentQuestion + 1;
        }
        const q = sortedSubjects[subjectKey] && sortedSubjects[subjectKey].questions;
        globalCount += Array.isArray(q) ? q.length : 0;
      }
      return 0;
    }

    // Check if current question is the last question in the entire exam
    function isLastQuestion() {
      const totalQuestions = getTotalQuestions();
      const currentGlobalNumber = getCurrentGlobalQuestionNumber();
      return currentGlobalNumber === totalQuestions;
    }

    // Get question by global index (across all subjects)
    function getQuestionByGlobalIndex(globalIndex) {
      let currentIndex = 0;
      const subjectKeys = getSortedSubjectKeys();
      
      for (const subjectKey of subjectKeys) {
        const questions = sortedSubjects[subjectKey] && sortedSubjects[subjectKey].questions;
        const len = Array.isArray(questions) ? questions.length : 0;
        if (globalIndex < currentIndex + len) {
          return {
            subject: subjectKey,
            questionIndex: globalIndex - currentIndex,
            question: questions ? questions[globalIndex - currentIndex] : null
          };
        }
        currentIndex += len;
      }
      return null;
    }

    // Get global index from subject and question index
    function getGlobalIndex(subject, questionIndex) {
      let globalIndex = 0;
      const subjectKeys = getSortedSubjectKeys();
      
      for (const subjectKey of subjectKeys) {
        if (subjectKey === subject) {
          return globalIndex + questionIndex;
        }
        const q = sortedSubjects[subjectKey] && sortedSubjects[subjectKey].questions;
        globalIndex += Array.isArray(q) ? q.length : 0;
      }
      return -1;
    }

    // Get next subject in order
    function getNextSubject(currentSubjectKey) {
      const subjectKeys = getSortedSubjectKeys();
      const currentIndex = subjectKeys.indexOf(currentSubjectKey);
      
      if (currentIndex < subjectKeys.length - 1) {
        return subjectKeys[currentIndex + 1];
      }
      return null; // No next subject
    }

    // Get previous subject in order
    function getPreviousSubject(currentSubjectKey) {
      const subjectKeys = getSortedSubjectKeys();
      const currentIndex = subjectKeys.indexOf(currentSubjectKey);
      
      if (currentIndex > 0) {
        return subjectKeys[currentIndex - 1];
      }
      return null; // No previous subject
    }

    // Load saved progress from localStorage
    function loadProgress() {
      const savedProgress = localStorage.getItem(`examProgress_${examData.exam_id}`);
      if (savedProgress) {
        const progress = JSON.parse(savedProgress);
        userAnswers = progress.userAnswers || {};
        markedQuestions = progress.markedQuestions || {};
        currentQuestion = progress.currentQuestion || 0;
        currentSubject = progress.currentSubject || Object.keys(sortedSubjects)[0];
        timeLeft = progress.timeLeft || totalTime;
        
        // Set the subject select value
        subjectSelect.value = currentSubject;
      } else {
        // If no saved progress, ensure first subject is selected
        currentSubject = Object.keys(sortedSubjects)[0];
        subjectSelect.value = currentSubject;
      }
    }

    // Save progress to localStorage
    function saveProgress() {
      const progress = {
        userAnswers,
        markedQuestions,
        currentQuestion,
        currentSubject,
        timeLeft
      };
      localStorage.setItem(`examProgress_${examData.exam_id}`, JSON.stringify(progress));
    }

    // Initialize the exam
    function initExam() {
      loadProgress();
      renderQuestion();
      renderPalette();
      updateProgress();
      startTimer(timeLeft);
      
      // Set up visibility change detection for anti-cheating
      document.addEventListener('visibilitychange', handleVisibilityChange);
      
      // Set up beforeunload to save progress when user leaves
      window.addEventListener('beforeunload', saveProgress);
    }

    // Handle tab visibility change
    function handleVisibilityChange() {
      if (document.hidden && !examSubmitted) {
        tabSwitchCount++;
        showCheatNotification();
        
        if (tabSwitchCount >= examData.exam_settings.max_tab_switches) {
          // Submit exam after max tab switches
          setTimeout(() => {
            submitExam();
          }, 3000);
        }
      }
    }

    // Show anti-cheating notification
    function showCheatNotification() {
      cheatNotification.classList.add('show');
      
      // Hide notification after 3 seconds
      setTimeout(() => {
        cheatNotification.classList.remove('show');
      }, 3000);
    }

    // Render current question
    function renderQuestion() {
      const subjectData = sortedSubjects[currentSubject];
      if (!subjectData || !Array.isArray(subjectData.questions)) {
        questionEnEl.innerHTML = 'No questions available.';
        return;
      }
      const questions = subjectData.questions;
      const question = questions[currentQuestion];
      if (!question) {
        questionEnEl.innerHTML = 'Question not found.';
        return;
      }
      const qid = question.id;
      const currentGlobalNumber = getCurrentGlobalQuestionNumber();
      questionEnEl.innerHTML = `${currentGlobalNumber}. ${question.question_en || ''}`;

      // Update question numbers with global totals
    //   const currentGlobalNumber = getCurrentGlobalQuestionNumber();
      const totalQuestions = getTotalQuestions();
      currentNumEl.textContent = currentGlobalNumber;
      totalNumEl.textContent = totalQuestions;

      optionsContainer.innerHTML = "";
      const options = Array.isArray(question.options_en) ? question.options_en : [];
      options.forEach((option, index) => {
        const optionElement = document.createElement("div");
        optionElement.className = "option-container";
        optionElement.innerHTML = `
          <div class="custom-radio"></div>
          <div class="option-text text-[15px]">
            <div>${option}</div>
          </div>
        `;
        optionElement.addEventListener("click", () => selectOption(qid, index));

        // Restore saved answer
        if (userAnswers[qid] === index) {
          optionElement.classList.add("selected");
        }

        optionsContainer.appendChild(optionElement);
      });

      // Update previous button state
      prevBtn.disabled = currentQuestion === 0 && getPreviousSubject(currentSubject) === null;

      saveProgress();
    }

    // Select an option
    function selectOption(qid, optionIndex) {
      const optionContainers = optionsContainer.querySelectorAll(".option-container");
      optionContainers.forEach(container => container.classList.remove("selected"));

      optionContainers[optionIndex].classList.add("selected");

      // Save answer against real question id
      userAnswers[qid] = optionIndex;

      renderPalette();
      updateProgress();
      saveProgress();
    }

    // Clear selected option
    function clearSelection() {
      const questions = sortedSubjects[currentSubject] && sortedSubjects[currentSubject].questions;
      const question = Array.isArray(questions) ? questions[currentQuestion] : null;
      if (!question) return;
      const qid = question.id;

      const optionContainers = optionsContainer.querySelectorAll(".option-container");
      optionContainers.forEach(container => container.classList.remove("selected"));

      delete userAnswers[qid];

      renderPalette();
      updateProgress();
      saveProgress();
    }

    // Mark question for review
    function markForReview() {
      const questions = sortedSubjects[currentSubject] && sortedSubjects[currentSubject].questions;
      const question = Array.isArray(questions) ? questions[currentQuestion] : null;
      if (!question) return;
      const qid = question.id;

      if (markedQuestions[qid]) {
        delete markedQuestions[qid];
      } else {
        markedQuestions[qid] = true;
      }

      renderPalette();
      saveProgress();
    }

    // Render question palette (all questions from all subjects in order)
    function renderPalette() {
      paletteEl.innerHTML = "";
      const totalQuestions = getTotalQuestions();
      const subjectKeys = getSortedSubjectKeys();
      
      let globalIndex = 0;
      for (const subjectKey of subjectKeys) {
        const questions = sortedSubjects[subjectKey] && sortedSubjects[subjectKey].questions;
        if (!Array.isArray(questions)) continue;
        
        questions.forEach((question, index) => {
          const qid = question.id;
          const button = document.createElement("button");
          button.textContent = globalIndex + 1;
          button.className = "palette-btn";
          
          // Check if this is the current question
          const isCurrent = subjectKey === currentSubject && index === currentQuestion;
          if (isCurrent) button.classList.add("current");
          if (userAnswers[qid] !== undefined) button.classList.add("answered");
          if (markedQuestions[qid]) button.classList.add("marked");

          button.addEventListener("click", () => {
            currentSubject = subjectKey;
            currentQuestion = index;
            subjectSelect.value = subjectKey;
            renderQuestion();
            renderPalette();
          });

          paletteEl.appendChild(button);
          globalIndex++;
        });
      }

      const answeredCount = getTotalAnswered();
      answeredCountEl.textContent = `${answeredCount}/${totalQuestions} Answered`;
    }

    // Update progress bar (total across all subjects)
    function updateProgress() {
      const totalQuestions = getTotalQuestions();
      const answeredCount = getTotalAnswered();
      const progressPercent = Math.round((answeredCount / totalQuestions) * 100);
      
      progressFillEl.style.width = `${progressPercent}%`;
      progressPercentEl.textContent = progressPercent;
    }

    // Start timer
    function startTimer(seconds) {
      clearInterval(timerInterval);
      
      timeLeft = seconds;
      updateTimerDisplay(timeLeft);
      
      timerInterval = setInterval(() => {
        timeLeft--;
        updateTimerDisplay(timeLeft);
        
        // Save progress every 10 seconds
        if (timeLeft % 10 === 0) {
          saveProgress();
        }
        
        if (timeLeft <= 0) {
          clearInterval(timerInterval);
          submitExam();
        }
      }, 1000);
    }

    // Update timer display
    function updateTimerDisplay(seconds) {
      const hours = Math.floor(seconds / 3600);
      const minutes = Math.floor((seconds % 3600) / 60);
      const remainingSeconds = seconds % 60;

      // Add warning class when time is running low (e.g., less than 5 minutes)
      if (hours === 0 && minutes < 5) {
          timerEl.classList.add("timer-warning");
      } else {
          timerEl.classList.remove("timer-warning");
      }

      timerEl.textContent = `${hours.toString().padStart(2, '0')}:` +
                            `${minutes.toString().padStart(2, '0')}:` +
                            `${remainingSeconds.toString().padStart(2, '0')}`;
    }

    // Submit exam via AJAX
    function submitExam() {
      if (examSubmitted) return;

      examSubmitted = true;
      clearInterval(timerInterval);

      // Remove event listeners
      document.removeEventListener('visibilitychange', handleVisibilityChange);
      window.removeEventListener('beforeunload', saveProgress);

      // Show submitting message
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Submitting...';
      submitBtn.disabled = true;

      // Prepare data
      const formData = new FormData();
      formData.append('student_id', examData.student.my_exam_id);
      formData.append('answers', JSON.stringify(userAnswers));
      formData.append('time_spent', totalTime - timeLeft);

      // AJAX submission
      fetch('/user/submit-mock-exam', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': '<?= csrf_token() ?>' // Laravel CSRF
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            localStorage.removeItem(`examProgress_${examData.exam_id}`);

            // Redirect or show message
            window.location.href = "/user/mock-thank-you";
          } else {
            alert('Failed to submit exam. Please try again.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            examSubmitted = false;
          }
        })
        .catch(error => {
          console.error('Error submitting exam:', error);
          alert('Something went wrong. Please check your connection and try again.');
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
          examSubmitted = false;
        });
    }

    // Navigate to previous question
    function goToPreviousQuestion() {
      if (currentQuestion > 0) {
        currentQuestion--;
        renderQuestion();
        renderPalette();
      } else {
        // Go to previous subject
        const previousSubject = getPreviousSubject(currentSubject);
        if (previousSubject) {
          currentSubject = previousSubject;
          const qs = sortedSubjects[currentSubject] && sortedSubjects[currentSubject].questions;
          currentQuestion = Array.isArray(qs) && qs.length > 0 ? qs.length - 1 : 0;
          subjectSelect.value = currentSubject;
          renderQuestion();
          renderPalette();
          updateProgress();
        }
      }
    }

    // Navigate to next question
    function goToNextQuestion() {
      const questions = sortedSubjects[currentSubject] && sortedSubjects[currentSubject].questions;
      const qLen = Array.isArray(questions) ? questions.length : 0;
      if (currentQuestion < qLen - 1) {
        currentQuestion++;
        renderQuestion();
        renderPalette();
      } else {
        // Go to next subject
        const nextSubject = getNextSubject(currentSubject);
        if (nextSubject) {
          currentSubject = nextSubject;
          currentQuestion = 0;
          subjectSelect.value = currentSubject;
          renderQuestion();
          renderPalette();
          updateProgress();
        } else {
          // Only show completion message if this is truly the last question
          if (isLastQuestion()) {
            alert("You have completed all subjects. You can submit the exam now.");
          }
        }
      }
    }

    // Event listeners
    prevBtn.addEventListener("click", goToPreviousQuestion);

    nextBtn.addEventListener("click", goToNextQuestion);

    clearBtn.addEventListener("click", clearSelection);

    markBtn.addEventListener("click", markForReview);

    subjectSelect.addEventListener("change", (e) => {
      currentSubject = e.target.value;
      currentQuestion = 0;
      renderQuestion();
      renderPalette();
      updateProgress();
    });

    submitBtn.addEventListener("click", () => {
      if (confirm("Are you sure you want to submit the exam?")) {
        submitExam();
      }
    });

    // Initialize the exam
    initExam();
    
    // Live tracking - Ping server every 10 seconds with current progress
    setInterval(function() {
      if (!examSubmitted) {
        const globalQuestionNumber = getCurrentGlobalQuestionNumber();
        
        fetch('{{ route("exam.pingPong") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            eid: examData.student.my_exam_id,
            qn: globalQuestionNumber
          })
        })
        .then(response => response.json())
        .then(data => {
          console.log('Ping successful:', data);
        })
        .catch(error => {
          console.error('Ping failed:', error);
        });
      }
    }, 10000); // Ping every 10 seconds
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