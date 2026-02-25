<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mock Exam Adventure Awaits!</title> 
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

  <!-- Center Card - Wider with two columns -->
  <div class="w-full max-w-4xl bg-white shadow-2xl rounded-2xl p-6 text-center relative z-10 flex flex-col md:flex-row gap-6">
    
    <!-- Left Column: Logo and Countdown -->
    <div class="w-full md:w-2/5 flex flex-col items-center justify-center p-4 bg-indigo-50 rounded-xl">
      <!-- Logo with Animation -->
      <div class="flex justify-center mb-0">
        <div class="">
          <img src="https://vaagaacademy.com/newassets/img/logo.webp" alt="Logo" class="w-32 h-32 object-contain">
        </div>
      </div>

      <!-- Heading -->
      <h1 class="text-2xl font-extrabold text-gray-900 mb-2 animate-fadeIn">Mock Exam Adventure Awaits!</h1>
      <p class="text-gray-600 text-sm mb-6 animate-fadeIn delay-200">Your knowledge quest begins soon! Stay put, champion! 🚀</p>

      <!-- Circular Progress Countdown -->
      <div class="relative w-28 h-28 mx-auto mb-6">
        <svg class="w-full h-full" viewBox="0 0 100 100">
          <circle class="text-indigo-200 stroke-current" stroke-width="8" cx="50" cy="50" r="40" fill="transparent"></circle>
          <circle class="text-indigo-600 progress-ring__circle stroke-current" stroke-width="8" stroke-linecap="round" cx="50" cy="50" r="40" fill="transparent"
                  stroke-dasharray="251.2"
                  stroke-dashoffset="251.2"></circle>
        </svg>
        <div class="absolute inset-0 flex items-center justify-center">
          <span id="countdown" class="text-xl font-bold text-indigo-700"></span>
        </div>
      </div>

      <!-- Instructions -->
      <div class="text-gray-500 text-left text-sm space-y-2 animate-fadeIn delay-800">
        <h2 class="font-semibold text-gray-700 text-center">Your Mission Briefing:</h2>
        <ul class="list-disc list-inside space-y-1">
          <li>Ensure your internet connection is stable ⚡</li>
          <li>No page refreshing during the mission 🔄</li>
          <li>Answer all questions before submitting ✅</li>
          <li>Stay calm and focused - you've got this! 😊</li>
        </ul>
      </div>

      <!-- Motivational Quote -->
      <div class="mt-6 text-xs text-gray-400 italic">
        "The expert in anything was once a beginner."
      </div>
    </div>
    
    <!-- Right Column: Questions and Fun Facts -->
    <div class="w-full md:w-3/5 flex flex-col gap-6">
      <!-- Mini-game: Quick Brain Teaser -->
      <div class="p-5 bg-indigo-50 rounded-xl animate-fadeIn delay-400 shadow-md">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg flex items-center justify-center gap-2">
          <i class="fas fa-brain text-indigo-600"></i> Quick Brain Warm-up 🧠
        </h3>
        <p id="brain-teaser" class="text-md text-gray-700 mb-3 font-medium">What has keys but can't open locks?</p>
        <button onclick="revealAnswer()" class="text-sm bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full hover:bg-indigo-200 transition shadow-sm">
          Reveal Answer
        </button>
        <p id="answer" class="text-sm text-indigo-600 mt-3 hidden font-semibold">A piano! 🎹</p>
      </div>

      <!-- Fun Facts Carousel -->
      <div class="p-5 bg-indigo-50 rounded-xl animate-fadeIn delay-600 shadow-md">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg flex items-center justify-center gap-2">
          <i class="fas fa-lightbulb text-yellow-500"></i> Did You Know? 🌟
        </h3>
        <div id="fun-fact" class="text-md text-gray-700">
          The world's longest exam lasted 11 hours and had 100 questions!
        </div>
      </div>
      
      <!-- Progress Indicator -->
      <div class="p-4 bg-white rounded-xl border border-indigo-100 shadow-sm d-none" style="display:none;">
        <div class="flex items-center justify-between mb-2">
          <span class="text-sm text-gray-600">Next question in:</span>
          <span id="question-timer" class="text-sm font-medium text-indigo-600">15s</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div id="question-progress" class="bg-indigo-600 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
        </div>
        
        <div class="flex items-center justify-between mt-4 mb-2">
          <span class="text-sm text-gray-600">Next fact in:</span>
          <span id="fact-timer" class="text-sm font-medium text-indigo-600">10s</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div id="fact-progress" class="bg-yellow-500 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Celebration Animation Container -->
  <div id="celebration" class="fixed inset-0 flex justify-center items-center pointer-events-none z-50 opacity-0 transition-opacity duration-500">
    <div class="text-6xl">🎉</div>
  </div>

  <!-- Interactive Script -->
  <script>
    // Countdown functionality
    let remainingSeconds = {{$secondsUntilStart}}; 
    const countdownElement = document.getElementById('countdown');
    const totalSeconds = remainingSeconds;
    const circle = document.querySelector('.progress-ring__circle');
    const radius = circle.r.baseVal.value;
    const circumference = 2 * Math.PI * radius;
    
    circle.style.strokeDasharray = `${circumference} ${circumference}`;
    circle.style.strokeDashoffset = `${circumference}`;
    
    // Question bank with 20 questions
    const brainTeasers = [
      { question: "What has keys but can't open locks?", answer: "A piano! 🎹" },
      { question: "What gets wet while drying?", answer: "A towel! 🧻" },
      { question: "What has a heart that doesn't beat?", answer: "An artichoke! 🍃" },
      { question: "The more of me you take, the more you leave behind. What am I?", answer: "Footsteps! 👣" },
      { question: "I speak without a mouth and hear without ears. What am I?", answer: "An echo! 📢" },
      { question: "What has cities, but no houses; forests, but no trees; and water, but no fish?", answer: "A map! 🗺️" },
      { question: "What is always in front of you but can't be seen?", answer: "The future! ⏱️" },
      { question: "What goes up but never comes down?", answer: "Your age! 🎂" },
      { question: "What can you break without touching it?", answer: "A promise! 🤝" },
      { question: "What has a thumb and four fingers but is not alive?", answer: "A glove! 🧤" },
      { question: "What has a head and a tail but no body?", answer: "A coin! 💰" },
      { question: "What can travel around the world while staying in a corner?", answer: "A stamp! 📬" },
      { question: "What has many needles but doesn't sew?", answer: "A Christmas tree! 🎄" },
      { question: "What has hands but can't clap?", answer: "A clock! ⏰" },
      { question: "What has an eye but cannot see?", answer: "A needle! 🧵" },
      { question: "What gets bigger the more you take away?", answer: "A hole! 🕳️" },
      { question: "What is full of holes but still holds water?", answer: "A sponge! 🧽" },
      { question: "What has a neck but no head?", answer: "A bottle! 🍾" },
      { question: "What can you catch but not throw?", answer: "A cold! 🤧" },
      { question: "What begins with T, ends with T, and has T in it?", answer: "A teapot! 🫖" }
    ];
    
    // Fun facts array
    const funFacts = [
      "The world's longest exam lasted 11 hours and had 100 questions!",
      "The word 'exam' comes from the Latin word 'examinare', meaning 'to weigh or test'.",
      "The first known standardized tests were used in ancient China for civil service positions.",
      "Studies show that chewing gum during exams can improve concentration and performance.",
      "The fear of exams is called 'testophobia'.",
      "The average student will take approximately 112 exams throughout their education.",
      "The SAT exam was originally called the Scholastic Aptitude Test.",
      "In Finland, students rarely take exams until they are in their late teens.",
      "The concept of multiple-choice questions was invented in 1914 by Frederick J. Kelly.",
      "The longest exam marathon lasted 51 hours and 6 minutes!",
      "The term 'blue book' for exams comes from the color of booklets used for essays.",
      "The first written exams were used in Ancient China over 2,000 years ago.",
      "Some universities in the UK used to have 'open book' exams where students could bring any materials.",
      "The highest possible score on the ACT exam is 36.",
      "In Japan, exam season is so stressful that there are special chocolates sold to help students focus.",
      "The GRE exam has a analytical writing section that is scored on a scale of 0 to 6.",
      "The MCAT for medical school is over 7 hours long!",
      "The bar exam for lawyers is typically taken over two days.",
      "Some exams use 'negative marking' where wrong answers deduct points.",
      "The term 'cramming' for last-minute studying dates back to the 19th century."
    ];
    
    // Initialize
    let currentTeaserIndex = 0;
    let currentFactIndex = 0;
    const brainTeaserElement = document.getElementById('brain-teaser');
    const answerElement = document.getElementById('answer');
    const funFactElement = document.getElementById('fun-fact');
    
    // Timer variables
    let questionTimeLeft = 15;
    let factTimeLeft = 10;
    const questionProgress = document.getElementById('question-progress');
    const factProgress = document.getElementById('fact-progress');
    const questionTimer = document.getElementById('question-timer');
    const factTimer = document.getElementById('fact-timer');
    
    // Set initial brain teaser and fun fact
    brainTeaserElement.textContent = brainTeasers[currentTeaserIndex].question;
    answerElement.textContent = brainTeasers[currentTeaserIndex].answer;
    funFactElement.textContent = funFacts[currentFactIndex];
    
    // Update progress circle
    function setProgress(percent) {
      const offset = circumference - (percent / 100) * circumference;
      circle.style.strokeDashoffset = offset;
    }
    
    // Update fun fact every 10 seconds
    const factInterval = setInterval(() => {
      factTimeLeft = 10;
      rotateFunFact();
    }, 10000);
    
    // Update brain teaser every 15 seconds
    const questionInterval = setInterval(() => {
      questionTimeLeft = 15;
      rotateBrainTeaser();
    }, 15000);
    
    // Update timers every second
    const timerInterval = setInterval(() => {
      questionTimeLeft--;
      factTimeLeft--;
      
      questionTimer.textContent = `${questionTimeLeft}s`;
      factTimer.textContent = `${factTimeLeft}s`;
      
      questionProgress.style.width = `${(questionTimeLeft / 15) * 100}%`;
      factProgress.style.width = `${(factTimeLeft / 10) * 100}%`;
    }, 1000);
    
    const mainInterval = setInterval(() => {
      if (remainingSeconds <= 0) {
        clearInterval(mainInterval);
        clearInterval(questionInterval);
        clearInterval(factInterval);
        clearInterval(timerInterval);
        
        countdownElement.innerText = "Starting...";
        // Show celebration animation
        document.getElementById('celebration').classList.remove('opacity-0');
        document.getElementById('celebration').classList.add('opacity-100');
        
        // Redirect to mock exam attempt after a brief delay
        setTimeout(() => {
          window.location.href = "{{ route('myMockSeries.myAttempt', ['mock_id' => $mock_id, 'batch_mock_test_id' => $batch_mock_test_id]) }}";
        }, 1500);
        return;
      }

      const minutes = Math.floor(remainingSeconds / 60);
      const seconds = remainingSeconds % 60;
      countdownElement.innerText = `${minutes}m ${seconds}s`;
      
      // Update progress circle
      const progressPercentage = 100 - (remainingSeconds / totalSeconds * 100);
      setProgress(progressPercentage);
      
      // Change progress color when close to completion
      if (remainingSeconds < 30) {
        circle.classList.remove('text-indigo-600');
        circle.classList.add('text-green-500');
      }

      remainingSeconds--;
    }, 1000);
    
    function rotateFunFact() {
      currentFactIndex = (currentFactIndex + 1) % funFacts.length;
      
      // Fade out, change content, fade in
      funFactElement.style.opacity = 0;
      setTimeout(() => {
        funFactElement.textContent = funFacts[currentFactIndex];
        funFactElement.style.opacity = 1;
      }, 500);
    }
    
    function rotateBrainTeaser() {
      currentTeaserIndex = (currentTeaserIndex + 1) % brainTeasers.length;
      
      // Hide answer if showing
      answerElement.classList.add('hidden');
      
      // Fade out, change content, fade in
      brainTeaserElement.style.opacity = 0;
      setTimeout(() => {
        brainTeaserElement.textContent = brainTeasers[currentTeaserIndex].question;
        answerElement.textContent = brainTeasers[currentTeaserIndex].answer;
        brainTeaserElement.style.opacity = 1;
      }, 500);
    }
    
    function revealAnswer() {
      answerElement.classList.toggle('hidden');
    }
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
