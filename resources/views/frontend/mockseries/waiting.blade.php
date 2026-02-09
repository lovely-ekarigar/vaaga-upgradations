<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mock Exam - Get Ready!</title>
  @php $mockExamUrl = url('user/mock-exam/' . $exam->id); @endphp
  <meta http-equiv="refresh" content="3;url={{ $mockExamUrl }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @keyframes fadeIn {
      0% {opacity: 0; transform: translateY(10px);}
      100% {opacity: 1; transform: translateY(0);}
    }
    .animate-fadeIn { 
      animation: fadeIn 1s ease forwards; 
      opacity: 0;
    }
    .delay-200 { animation-delay: 0.2s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-600 { animation-delay: 0.6s; }
    .delay-800 { animation-delay: 0.8s; }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    .animate-spin-slow { 
      animation: spin 5s linear infinite; 
    }

    @keyframes pulse {
      0%, 100% { opacity: 0.2; }
      50% { opacity: 0.3; }
    }
    .animate-pulse-slow { 
      animation: pulse 6s ease-in-out infinite; 
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow {
      animation: bounce 3s ease-in-out infinite;
    }

    .confetti {
      position: absolute;
      font-size: 1.5rem;
      z-index: 1;
      animation: float 8s ease-in-out infinite;
      opacity: 0.7;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(20deg); }
    }
    
    .progress-ring__circle {
      transition: stroke-dashoffset 0.5s ease;
      transform: rotate(-90deg);
      transform-origin: 50% 50%;
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-100 flex items-center justify-center px-4 overflow-hidden relative">

  <!-- Floating Background Shapes -->
  <div class="absolute top-0 left-0 w-60 h-60 bg-indigo-200 rounded-full opacity-20 animate-pulse-slow -translate-x-20 -translate-y-20"></div>
  <div class="absolute bottom-0 right-0 w-72 h-72 bg-pink-200 rounded-full opacity-20 animate-pulse-slow translate-x-20 translate-y-20"></div>
  
  <!-- Confetti Elements -->
  <div class="confetti" style="top: 10%; left: 20%">🎉</div>
  <div class="confetti" style="top: 30%; left: 80%">✨</div>
  <div class="confetti" style="top: 70%; left: 40%">🌟</div>
  <div class="confetti" style="top: 20%; left: 60%">🎯</div>

  <!-- Center Card -->
  <div class="w-full max-w-4xl bg-white shadow-2xl rounded-2xl p-6 text-center relative z-10 flex flex-col md:flex-row gap-6">
    
    <!-- Left Column: Logo and Countdown -->
    <div class="w-full md:w-2/5 flex flex-col items-center justify-center p-4 bg-indigo-50 rounded-xl">
      <!-- Logo -->
      <div class="flex justify-center mb-0">
        <div class="">
          <img src="https://vaagaacademy.com/newassets/img/logo.webp" alt="Logo" class="w-32 h-32 object-contain">
        </div>
      </div>

      <!-- Heading -->
      <h1 class="text-2xl font-extrabold text-gray-900 mb-2 animate-fadeIn">Mock Test Ready!</h1>
      <p class="text-gray-600 text-sm mb-6 animate-fadeIn delay-200">Your mock exam will begin shortly! 🚀</p>

      <!-- Circular Progress Countdown -->
      <div class="relative w-28 h-28 mx-auto mb-6">
        <svg class="w-full h-full" viewBox="0 0 100 100">
          <circle class="text-indigo-200 stroke-current" stroke-width="8" cx="50" cy="50" r="40" fill="transparent"></circle>
          <circle id="progress-circle" class="text-indigo-600 progress-ring__circle stroke-current" stroke-width="8" stroke-linecap="round" cx="50" cy="50" r="40" fill="transparent"
                  stroke-dasharray="251.2"
                  stroke-dashoffset="0"></circle>
        </svg>
        <div class="absolute inset-0 flex items-center justify-center">
          <span id="countdown-timer" class="text-3xl font-bold text-indigo-600">{{ $secondsUntilStart }}</span>
        </div>
      </div>

      <p class="text-gray-500 text-xs animate-fadeIn delay-400">Redirecting to exam...</p>
      <a href="{{ $mockExamUrl }}" id="startExamLink" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 text-sm no-underline">Start exam now &rarr;</a>
      <p class="text-gray-400 text-xs mt-2">Click the button above if the page does not redirect.</p>
    </div>

    <!-- Right Column: Instructions -->
    <div class="w-full md:w-3/5 text-left p-4">
      <h2 class="text-xl font-bold text-indigo-700 mb-4 animate-fadeIn delay-200">
        <i class="fas fa-clipboard-check mr-2"></i>Exam Instructions
      </h2>
      <ul class="space-y-3 text-gray-700 text-sm">
        <li class="animate-fadeIn delay-400 flex items-start">
          <i class="fas fa-circle-check text-green-500 mt-1 mr-3"></i>
          <span>Read each question carefully before answering.</span>
        </li>
        <li class="animate-fadeIn delay-600 flex items-start">
          <i class="fas fa-circle-check text-green-500 mt-1 mr-3"></i>
          <span>You can navigate between questions using the question palette.</span>
        </li>
        <li class="animate-fadeIn delay-800 flex items-start">
          <i class="fas fa-circle-check text-green-500 mt-1 mr-3"></i>
          <span>Mark questions for review if you want to revisit them later.</span>
        </li>
        <li class="animate-fadeIn delay-800 flex items-start">
          <i class="fas fa-circle-check text-green-500 mt-1 mr-3"></i>
          <span>Keep an eye on the timer displayed on your screen.</span>
        </li>
        <li class="animate-fadeIn delay-800 flex items-start">
          <i class="fas fa-circle-exclamation text-orange-500 mt-1 mr-3"></i>
          <span><strong>Do not refresh</strong> the page during the exam.</span>
        </li>
        <li class="animate-fadeIn delay-800 flex items-start">
          <i class="fas fa-circle-exclamation text-red-500 mt-1 mr-3"></i>
          <span>Ensure you have a stable internet connection.</span>
        </li>
      </ul>

      <div class="mt-6 p-4 bg-amber-50 border-l-4 border-amber-400 rounded animate-fadeIn delay-800">
        <p class="text-sm text-amber-800">
          <i class="fas fa-exclamation-triangle mr-2"></i>
          <strong>Important:</strong> Once you start, the timer cannot be paused. Make sure you're ready!
        </p>
      </div>

      <div class="mt-4 text-center">
        <p class="text-gray-600 text-sm">
          <i class="fas fa-users mr-2"></i>Good luck! You've got this! 💪
        </p>
      </div>
    </div>

  </div>

  <script>
    (function() {
      const takeExamUrl = "{{ $mockExamUrl }}".replace(/&amp;/g, '&');
      let timeLeft = parseInt({{ $secondsUntilStart ?? 2 }}, 10);
      if (!Number.isFinite(timeLeft) || timeLeft < 0) timeLeft = 2;
      const totalTime = Math.max(1, timeLeft);
      const timerEl = document.getElementById('countdown-timer');
      const progressCircle = document.getElementById('progress-circle');
      const circumference = 2 * Math.PI * 40;

      function goToExam() {
        window.location.href = takeExamUrl;
      }

      if (progressCircle) progressCircle.style.strokeDashoffset = 0;

      var interval = setInterval(function() {
        timeLeft--;
        if (timerEl) timerEl.textContent = Math.max(0, timeLeft);
        if (progressCircle && totalTime > 0) {
          var p = Math.max(0, timeLeft) / totalTime;
          progressCircle.style.strokeDashoffset = circumference * (1 - p);
        }
        if (timeLeft <= 0) {
          clearInterval(interval);
          goToExam();
        }
      }, 1000);

      setTimeout(function() { goToExam(); }, 5000);
    })();
  </script>

</body>
</html>
