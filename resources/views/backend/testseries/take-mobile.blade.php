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
    
    * {
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      color: #334155;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      font-size: 14px;
    }

    /* Mobile First Approach */
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
      padding: 12px 16px;
      font-weight: 500;
      transition: all 0.2s ease;
      font-size: 14px;
      border: none;
      cursor: pointer;
      flex: 1;
      min-height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .btn-primary:hover {
      background: var(--primary-dark);
    }
    
    .btn-secondary {
      background: #f1f5f9;
      color: #475569;
      border-radius: 6px;
         padding: 9px 6px;
      font-weight: 500;
      transition: all 0.2s ease;
      font-size: 14px;
      border: none;
      cursor: pointer;
      flex: 1;
      min-height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .btn-secondary:hover {
      background: #e2e8f0;
    }
    
    .option-container {
      padding: 12px 16px;
      text-align: left;
      transition: all 0.15s ease;
      background: white;
      font-size: 14px;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      cursor: pointer;
      border: 1.5px solid #e2e8f0;
      border-radius: 8px;
      min-height: 50px;
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
      width: 20px;
      height: 20px;
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
      width: 12px;
      height: 12px;
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
      width: 44px;
      height: 44px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 500;
      transition: all 0.15s ease;
      background: #f1f5f9;
      color: #475569;
      font-size: 14px;
      border: none;
      cursor: pointer;
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
      height: 6px;
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
      width: 60px;
      height: 60px;
      border-radius: 8px;
      object-fit: cover;
      border: 2px solid #e2e8f0;
    }
    
    .palette-container {
      overflow-y: auto;
      max-height: 40vh;
    }
    
    .options-container {
      overflow-y: auto;
      max-height: 50vh;
      padding-right: 5px;
    }
    
    /* Custom scrollbars */
    .options-container::-webkit-scrollbar,
    .palette-container::-webkit-scrollbar {
      width: 6px;
    }
    
    .options-container::-webkit-scrollbar-track,
    .palette-container::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    
    .options-container::-webkit-scrollbar-thumb,
    .palette-container::-webkit-scrollbar-thumb {
      background: #c5c5c5;
      border-radius: 10px;
    }
    
    .options-container::-webkit-scrollbar-thumb:hover,
    .palette-container::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
    
    .notification {
      position: fixed;
      top: 20px;
      right: 0px;
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

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
      body {
        font-size: 16px;
        height: 100vh;
        overflow: hidden;
      }

      .container-mobile {
        display: flex;
        flex-direction: column;
        height: 100vh;
        padding: 0;
      }

      header {
        padding: 12px 16px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 100;
      }

      .header-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
      }

      .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .header-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
      }

      .logo-container img {
        height: 40px;
      }

      .user-photos {
        display: flex;
        gap: 8px;
      }

      .user-photo {
        width: 50px;
        height: 50px;
      }

      .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 12px;
        gap: 12px;
        overflow: hidden;
      }

      .question-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
      }

      .question-card {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
        padding: 16px;
      }

      .question-content {
        flex: 1;
        overflow-y: auto;
        padding-bottom: 80px;
      }

      .mobile-controls {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        padding: 12px 16px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 8px;
        z-index: 90;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      }

      .mobile-controls .btn {
        padding: 10px 8px;
        font-size: 12px;
        min-height: 44px;
      }

      .sidebar-mobile {
        display: none;
      }

      .sidebar-mobile.active {
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: white;
        z-index: 200;
        padding: 16px;
        overflow-y: auto;
      }

      .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
      }

      .close-sidebar {
        background: none;
        border: none;
        font-size: 20px;
        color: #3F51B5;
        cursor: pointer;
        padding: 8px;
      }

      .mobile-nav-buttons {
        display: flex;
        gap: 8px;
        margin-top: 16px;
      }

      .mobile-nav-buttons .btn {
        flex: 1;
      }

      .palette-container {
        max-height: 50vh;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
      }

      .palette-btn {
        width: 40px;
        height: 40px;
        font-size: 13px;
      }

      #question-en-mobile {
        font-size: 16px;
        line-height: 1.5;
        margin-bottom: 16px;
      }

      .option-text {
        font-size: 15px;
        line-height: 1.4;
      }

      .progress-info {
        font-size: 13px;
      }

      .subject-select-container {
        width: 100%;
        margin: 8px 0;
      }

      #subjectSelectMobile {
        width: 100%;
        padding: 10px 12px;
        font-size: 14px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: white;
      }
    }

    @media (min-width: 769px) {
      .mobile-only {
        display: none !important;
      }
    }

    /* Utility classes */
    .mobile-only {
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    .desktop-only {
      display: none;
    }

    /* Improved button states for mobile */
    .btn:active {
      transform: scale(0.98);
    }

    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    /* Better focus states for accessibility */
    .btn:focus-visible,
    .option-container:focus-visible,
    .palette-btn:focus-visible {
      outline: 2px solid var(--primary);
      outline-offset: 2px;
    }

    /* Loading states */
    .loading {
      opacity: 0.7;
      pointer-events: none;
    }

    /* Confirmation modal */
    .confirmation-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      padding: 20px;
    }

    .confirmation-content {
      background: white;
      padding: 24px;
      border-radius: 12px;
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .confirmation-buttons {
      display: flex;
      gap: 12px;
      margin-top: 20px;
    }

    .confirmation-buttons .btn {
      flex: 1;
    }
    .btn-danger{
            background: #F44336;
    color: #fffafa;
    padding: 0px 8px;
    border-radius: 5px;
    }
  </style>
</head>
<body>
  <!-- Mobile Layout -->
  <div class="container-mobile mobile-only">
    <!-- Header -->
    <header>
      <div class="header-content">
        <div class="header-top">
          <div class="logo-container">
            <img src="https://vaagaacademy.com/newassets/img/logo.webp" alt="Logo" />
          </div>
          <div class="flex items-center bg-red-50 text-red-700 py-1.5 px-3 text-lg rounded timer-container">
            <i class="fas fa-clock mr-1.5"></i>
            <span class="font-medium"><span id="timer-mobile">59:59</span></span>
          </div>
        </div>
        
        <div class="header-bottom">
          <div class="subject-select-container">
            <select id="subjectSelectMobile" class="subject-select">
              <?php 
              $sortedSubjects = [];
              foreach ($examData['subjects'] as $key => $subject) {
                  $sortedSubjects[$key] = [
                      'key' => $key,
                      'name' => $subject['name']
                  ];
              }
              ksort($sortedSubjects);
              
              foreach ($sortedSubjects as $subject): ?>
                <option value="<?= $subject['key'] ?>"><?= $subject['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div class="user-photos">
              
               <button id="paletteToggle" class="btn-primary">
          <!--<i class="fas fa-th-large mr-1"></i> -->
          Questions
        </button>
        <button id="btnSubmitExam" class=" btn-danger">
          Submit 
        </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Question Area -->
      <div class="question-area">
        <div class="card question-card">
          <!-- Progress bar -->
          <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-500 mb-1 progress-info">
              <span>Question <span id="current-num-mobile">1</span> of <span id="total-num-mobile">5</span></span>
              <span><span id="progress-percent-mobile">0</span>% Completed</span>
            </div>
            <div class="progress-bar">
              <div id="progress-fill-mobile" class="progress-fill" style="width: 0%"></div>
            </div>
          </div>
          
          <!-- Question Content -->
          <div class="question-content">
            <h2 id="question-en-mobile" class="font-semibold mb-4 text-gray-800">Question will appear here</h2>
            
            <!-- Options Container -->
            <div id="options-container-mobile" class="options-container">
              <!-- Options will be dynamically inserted here -->
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile Controls (Fixed at bottom) -->
      <div class="mobile-controls">
        <button id="prevBtnMobile" class="btn-secondary">
          <i class="fas fa-arrow-left mr-1"></i> Prev
        </button>
        
        <button id="clearBtnMobile" class="btn-secondary">
          <i class="fas fa-times-circle mr-1"></i> Clear
        </button>
        
        <button id="markBtnMobile" class="btn-secondary">
          <i class="far fa-flag mr-1"></i> Mark
        </button>
        
     
        
        <button id="nextBtnMobile" class="btn-primary">
          Next <i class="fas fa-arrow-right ml-1"></i>
        </button>
         
      </div>
     
    </div>

    <!-- Mobile Sidebar (Overlay) -->
    <div id="sidebarMobile" class="sidebar-mobile">
      <div class="sidebar-header">
        <h3 class="font-semibold text-lg">Exam Navigation</h3>
        <button class="close-sidebar">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <!-- User Info -->
      <div class="card p-3 mb-4">
        <div class="flex items-center">
          <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-md mr-3">
            <i class="fas fa-user"></i>
          </div>
          <div>
            <div class="font-semibold text-sm"><?= $examData['student']['name'] ?></div>
            <div class="text-xs text-gray-500">Exam in progress</div>
          </div>
        </div>
      </div>
      
      <!-- Question Palette -->
      <div class="card p-3 flex-1 flex flex-col mb-4">
        <div class="font-semibold text-gray-700 text-sm mb-3 flex items-center justify-between">
          <span>Questions Palette</span>
          <span class="text-xs font-normal text-gray-500" id="answered-count-mobile">0/5 Answered</span>
        </div>
        
        <div id="palette-mobile" class="palette-container grid grid-cols-5 gap-2">
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
              <span class="text-gray-500">Marked</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Actions -->
      <div class="mobile-nav-buttons">
        <button id="submitBtnMobile" class="btn-secondary">
          <i class="fas fa-paper-plane mr-1.5"></i> Submit
        </button>
        <button id="closeSidebar" class=" btn-primary">
          Continue Exam
        </button>
      </div>
    </div>
  </div>

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

  <!-- Confirmation Modal -->
  <div id="confirmationModal" class="confirmation-modal" style="display: none;">
    <div class="confirmation-content">
      <div class="text-xl font-semibold mb-3">Submit Exam?</div>
      <p class="text-gray-600 mb-4">Are you sure you want to submit your exam? This action cannot be undone.</p>
      <div class="confirmation-buttons">
        <button id="cancelSubmit" class="btn-secondary">Cancel</button>
        <button id="confirmSubmit" class="btn-primary">Submit Exam</button>
      </div>
    </div>
  </div>

  <script>
    // PHP data converted to JavaScript
    const examData = <?= $examDataJson ?>;
    
    // Sort subjects by ID in ascending order
    const sortedSubjects = {};
    Object.keys(examData.subjects)
        .map(key => ({ key, ...examData.subjects[key] }))
        .sort((a, b) => a.id - b.id)
        .forEach(subject => {
            sortedSubjects[subject.key] = subject;
        });

    let currentSubject = Object.keys(sortedSubjects)[0];
    let currentQuestion = 0;
    let userAnswers = {};
    let markedQuestions = {};
    let timerInterval;
    const totalTime = examData.exam_settings.total_time;
    let timeLeft = totalTime;
    let tabSwitchCount = 0;
    let examSubmitted = false;

    // DOM elements for mobile
    const subjectSelectMobile = document.getElementById("subjectSelectMobile");
    const questionEnMobile = document.getElementById("question-en-mobile");
    const optionsContainerMobile = document.getElementById("options-container-mobile");
    const prevBtnMobile = document.getElementById("prevBtnMobile");
    const nextBtnMobile = document.getElementById("nextBtnMobile");
    const clearBtnMobile = document.getElementById("clearBtnMobile");
    const markBtnMobile = document.getElementById("markBtnMobile");
    const paletteMobile = document.getElementById("palette-mobile");
    const timerMobile = document.getElementById("timer-mobile");
    const currentNumMobile = document.getElementById("current-num-mobile");
    const totalNumMobile = document.getElementById("total-num-mobile");
    const progressFillMobile = document.getElementById("progress-fill-mobile");
    const progressPercentMobile = document.getElementById("progress-percent-mobile");
    const answeredCountMobile = document.getElementById("answered-count-mobile");
    const submitBtnMobile = document.getElementById("submitBtnMobile");
      const btnSubmitExam = document.getElementById("btnSubmitExam");
    const paletteToggle = document.getElementById("paletteToggle");
    const sidebarMobile = document.getElementById("sidebarMobile");
    const closeSidebar = document.querySelector(".close-sidebar");
    const cheatNotification = document.getElementById("cheatNotification");
    const closeSidebarBottom = document.getElementById("closeSidebar");
    const confirmationModal = document.getElementById("confirmationModal");
    const cancelSubmit = document.getElementById("cancelSubmit");
    const confirmSubmit = document.getElementById("confirmSubmit");

    // Get sorted subject keys in ascending order of ID
    function getSortedSubjectKeys() {
        return Object.keys(sortedSubjects).sort((a, b) => sortedSubjects[a].id - sortedSubjects[b].id);
    }

    // Calculate total questions across all subjects
    function getTotalQuestions() {
      let total = 0;
      const subjectKeys = getSortedSubjectKeys();
      for (const subjectKey of subjectKeys) {
        total += sortedSubjects[subjectKey].questions.length;
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
        globalCount += sortedSubjects[subjectKey].questions.length;
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
        const questions = sortedSubjects[subjectKey].questions;
        if (globalIndex < currentIndex + questions.length) {
          return {
            subject: subjectKey,
            questionIndex: globalIndex - currentIndex,
            question: questions[globalIndex - currentIndex]
          };
        }
        currentIndex += questions.length;
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
        globalIndex += sortedSubjects[subjectKey].questions.length;
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
      return null;
    }

    // Get previous subject in order
    function getPreviousSubject(currentSubjectKey) {
      const subjectKeys = getSortedSubjectKeys();
      const currentIndex = subjectKeys.indexOf(currentSubjectKey);
      
      if (currentIndex > 0) {
        return subjectKeys[currentIndex - 1];
      }
      return null;
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
        
        subjectSelectMobile.value = currentSubject;
      } else {
        currentSubject = Object.keys(sortedSubjects)[0];
        subjectSelectMobile.value = currentSubject;
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
      renderQuestionMobile();
      renderPaletteMobile();
      updateProgressMobile();
      startTimerMobile(timeLeft);
      
      document.addEventListener('visibilitychange', handleVisibilityChange);
      window.addEventListener('beforeunload', saveProgress);
    }

    // Handle tab visibility change
    function handleVisibilityChange() {
      if (document.hidden && !examSubmitted) {
        tabSwitchCount++;
        showCheatNotification();
        
        if (tabSwitchCount >= examData.exam_settings.max_tab_switches) {
          setTimeout(() => {
            submitExam();
          }, 3000);
        }
      }
    }

    // Show anti-cheating notification
    function showCheatNotification() {
      cheatNotification.classList.add('show');
      
      setTimeout(() => {
        cheatNotification.classList.remove('show');
      }, 3000);
    }

    // Render current question for mobile
    function renderQuestionMobile() {
      const questions = sortedSubjects[currentSubject].questions;
      const question = questions[currentQuestion];
      const qid = question.id;
      const currentGlobalNumber = getCurrentGlobalQuestionNumber();
      
      questionEnMobile.innerHTML = `${currentGlobalNumber}. ${question.question_en}`;

      const totalQuestions = getTotalQuestions();
      currentNumMobile.textContent = currentGlobalNumber;
      totalNumMobile.textContent = totalQuestions;

      optionsContainerMobile.innerHTML = "";

      question.options_en.forEach((option, index) => {
        const optionElement = document.createElement("div");
        optionElement.className = "option-container";
        optionElement.innerHTML = `
          <div class="custom-radio"></div>
          <div class="option-text">
            <div>${option}</div>
          </div>
        `;
        optionElement.addEventListener("click", () => selectOptionMobile(qid, index));

        if (userAnswers[qid] === index) {
          optionElement.classList.add("selected");
        }

        optionsContainerMobile.appendChild(optionElement);
      });

      prevBtnMobile.disabled = currentQuestion === 0 && getPreviousSubject(currentSubject) === null;
    //   nextBtnMobile.disabled = isLastQuestion();

      saveProgress();
    }

    // Select an option for mobile
    function selectOptionMobile(qid, optionIndex) {
      const optionContainers = optionsContainerMobile.querySelectorAll(".option-container");
      optionContainers.forEach(container => container.classList.remove("selected"));

      optionContainers[optionIndex].classList.add("selected");
      userAnswers[qid] = optionIndex;

      renderPaletteMobile();
      updateProgressMobile();
      saveProgress();
    }

    // Clear selected option for mobile
    function clearSelectionMobile() {
      const questions = sortedSubjects[currentSubject].questions;
      const question = questions[currentQuestion];
      const qid = question.id;

      const optionContainers = optionsContainerMobile.querySelectorAll(".option-container");
      optionContainers.forEach(container => container.classList.remove("selected"));

      delete userAnswers[qid];

      renderPaletteMobile();
      updateProgressMobile();
      saveProgress();
    }

    // Mark question for review for mobile
    function markForReviewMobile() {
      const question = sortedSubjects[currentSubject].questions[currentQuestion];
      const qid = question.id;

      if (markedQuestions[qid]) {
        delete markedQuestions[qid];
        markBtnMobile.innerHTML = '<i class="far fa-flag mr-1"></i> Mark ';
      } else {
        markedQuestions[qid] = true;
        markBtnMobile.innerHTML = '<i class="fas fa-flag mr-1"></i> Unmark';
      }

      renderPaletteMobile();
      saveProgress();
    }

    // Render question palette for mobile
    function renderPaletteMobile() {
      paletteMobile.innerHTML = "";
      const totalQuestions = getTotalQuestions();
      const subjectKeys = getSortedSubjectKeys();
      
      let globalIndex = 0;
      for (const subjectKey of subjectKeys) {
        const questions = sortedSubjects[subjectKey].questions;
        
        questions.forEach((question, index) => {
          const qid = question.id;
          const button = document.createElement("button");
          button.textContent = globalIndex + 1;
          button.className = "palette-btn";
          
          const isCurrent = subjectKey === currentSubject && index === currentQuestion;
          if (isCurrent) button.classList.add("current");
          if (userAnswers[qid] !== undefined) button.classList.add("answered");
          if (markedQuestions[qid]) button.classList.add("marked");

          button.addEventListener("click", () => {
            currentSubject = subjectKey;
            currentQuestion = index;
            subjectSelectMobile.value = subjectKey;
            renderQuestionMobile();
            renderPaletteMobile();
            closeSidebarMobile();
          });

          paletteMobile.appendChild(button);
          globalIndex++;
        });
      }

      const answeredCount = getTotalAnswered();
      answeredCountMobile.textContent = `${answeredCount}/${totalQuestions} Answered`;
    }

    // Update progress bar for mobile
    function updateProgressMobile() {
      const totalQuestions = getTotalQuestions();
      const answeredCount = getTotalAnswered();
      const progressPercent = Math.round((answeredCount / totalQuestions) * 100);
      
      progressFillMobile.style.width = `${progressPercent}%`;
      progressPercentMobile.textContent = progressPercent;
    }

    // Start timer for mobile
    function startTimerMobile(seconds) {
      clearInterval(timerInterval);
      
      timeLeft = seconds;
      updateTimerDisplayMobile(timeLeft);
      
      timerInterval = setInterval(() => {
        timeLeft--;
        updateTimerDisplayMobile(timeLeft);
        
        if (timeLeft % 10 === 0) {
          saveProgress();
        }
        
        if (timeLeft <= 0) {
          clearInterval(timerInterval);
          submitExam();
        }
      }, 1000);
    }

    // Update timer display for mobile
    function updateTimerDisplayMobile(seconds) {
      const hours = Math.floor(seconds / 3600);
      const minutes = Math.floor((seconds % 3600) / 60);
      const remainingSeconds = seconds % 60;

      if (hours === 0 && minutes < 5) {
          timerMobile.classList.add("timer-warning");
      } else {
          timerMobile.classList.remove("timer-warning");
      }

      timerMobile.textContent = `${hours.toString().padStart(2, '0')}:` +
                            `${minutes.toString().padStart(2, '0')}:` +
                            `${remainingSeconds.toString().padStart(2, '0')}`;
    }

    // Submit exam via AJAX
    function submitExam() {
      if (examSubmitted) return;
confirmSubmit.innerHTML='Submitting...';
      examSubmitted = true;
      clearInterval(timerInterval);

      document.removeEventListener('visibilitychange', handleVisibilityChange);
      window.removeEventListener('beforeunload', saveProgress);

      const originalText = submitBtnMobile.innerHTML;
      submitBtnMobile.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Submitting...';
      submitBtnMobile.disabled = true;

      const formData = new FormData();
      formData.append('student_id', examData.student.my_exam_id);
      formData.append('answers', JSON.stringify(userAnswers));
      formData.append('time_spent', totalTime - timeLeft);

      fetch('/user/submit-exam', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': '<?= csrf_token() ?>'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            localStorage.removeItem(`examProgress_${examData.exam_id}`);
            window.location.href = "/user/exam/thank-you";
          } else {
            alert('Failed to submit exam. Please try again.');
            submitBtnMobile.innerHTML = originalText;
            submitBtnMobile.disabled = false;
            examSubmitted = false;
          }
        })
        .catch(error => {
          console.error('Error submitting exam:', error);
          alert('Something went wrong. Please check your connection and try again.');
          submitBtnMobile.innerHTML = originalText;
          submitBtnMobile.disabled = false;
          examSubmitted = false;
        });
    }

    // Navigate to previous question for mobile
    function goToPreviousQuestionMobile() {
      if (currentQuestion > 0) {
        currentQuestion--;
        renderQuestionMobile();
        renderPaletteMobile();
      } else {
        const previousSubject = getPreviousSubject(currentSubject);
        if (previousSubject) {
          currentSubject = previousSubject;
          currentQuestion = sortedSubjects[currentSubject].questions.length - 1;
          subjectSelectMobile.value = currentSubject;
          renderQuestionMobile();
          renderPaletteMobile();
          updateProgressMobile();
        }
      }
    }

    // Navigate to next question for mobile
    function goToNextQuestionMobile() {
      const questions = sortedSubjects[currentSubject].questions;
      if (currentQuestion < questions.length - 1) {
        currentQuestion++;
        renderQuestionMobile();
        renderPaletteMobile();
      } else {
        const nextSubject = getNextSubject(currentSubject);
        if (nextSubject) {
          currentSubject = nextSubject;
          currentQuestion = 0;
          subjectSelectMobile.value = currentSubject;
          renderQuestionMobile();
          renderPaletteMobile();
          updateProgressMobile();
        } else {
           
          if (isLastQuestion()) { 
            alert("You have completed all subjects. You can submit the exam now.");
          }
        }
      }
    }

    // Show sidebar
    function openSidebarMobile() {
  const sidebarMobile = document.getElementById('sidebarMobile');
    if (!sidebarMobile) {
        console.error("Sidebar element not found");
        return;
    }
    // alert("Hello");
    sidebarMobile.classList.add('active');
    renderPaletteMobile();
    }

    // Close sidebar
    function closeSidebarMobile() {
     //  alert("h");
      sidebarMobile.classList.remove('active');
    }

    // Show confirmation modal
    function showConfirmationModal() {
      confirmationModal.style.display = 'flex';
    }

    // Hide confirmation modal
    function hideConfirmationModal() {
      confirmationModal.style.display = 'none';
    }

    // Event listeners for mobile
    prevBtnMobile.addEventListener("click", goToPreviousQuestionMobile);
    nextBtnMobile.addEventListener("click", goToNextQuestionMobile);
    clearBtnMobile.addEventListener("click", clearSelectionMobile);
    markBtnMobile.addEventListener("click", markForReviewMobile);
    paletteToggle.addEventListener("click", openSidebarMobile);
    closeSidebar.addEventListener("click", closeSidebarMobile);
    closeSidebarBottom.addEventListener("click", closeSidebarMobile);
    
    
    submitBtnMobile.addEventListener("click", showConfirmationModal);
    
    btnSubmitExam.addEventListener("click", showConfirmationModal);
    
    
    cancelSubmit.addEventListener("click", hideConfirmationModal);
    confirmSubmit.addEventListener("click", submitExam);

    subjectSelectMobile.addEventListener("change", (e) => {
      currentSubject = e.target.value;
      currentQuestion = 0;
      renderQuestionMobile();
      renderPaletteMobile();
      updateProgressMobile();
    });

    // Close sidebar when clicking outside
    document.addEventListener('click', (e) => {
      if (sidebarMobile.classList.contains('active') && 
          !sidebarMobile.contains(e.target) && 
          e.target !== paletteToggle) {
        closeSidebarMobile();
      }
    });

    // Initialize the exam
    initExam();

    // Update mark button state on question render
    const originalRenderQuestionMobile = renderQuestionMobile;
    renderQuestionMobile = function() {
      originalRenderQuestionMobile();
      const question = sortedSubjects[currentSubject].questions[currentQuestion];
      const qid = question.id;
      
      if (markedQuestions[qid]) {
        markBtnMobile.innerHTML = '<i class="fas fa-flag mr-1"></i> Mark';
      } else {
        markBtnMobile.innerHTML = '<i class="far fa-flag mr-1"></i> Unmark';
      }
    };

    // Prevent zoom on double tap
    document.addEventListener('touchstart', function(e) {
      if (e.touches.length > 1) {
        e.preventDefault();
      }
    }, { passive: false });

    let lastTouchEnd = 0;
    document.addEventListener('touchend', function(e) {
      const now = (new Date()).getTime();
      if (now - lastTouchEnd <= 300) {
        e.preventDefault();
      }
      lastTouchEnd = now;
    }, false);
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