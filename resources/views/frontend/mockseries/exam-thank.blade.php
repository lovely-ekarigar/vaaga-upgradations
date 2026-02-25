<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" /> 
  <title>Thank You — Exam</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(180deg,#eef2ff 0%, #f8fafc 100%);
      overflow: hidden;
    }
    .bubble {
      position: absolute;
      bottom: -100px;
      background: rgba(99,102,241,0.15);
      border-radius: 50%;
      animation: rise 15s linear infinite;
    }
    @keyframes rise {
      0% { transform: translateY(0) scale(1); opacity: 1; }
      100% { transform: translateY(-120vh) scale(1.5); opacity: 0; }
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 relative">

  <!-- bubbles -->
  <div class="bubble w-10 h-10 left-10" style="animation-delay:0s; animation-duration:12s;"></div>
  <div class="bubble w-6 h-6 left-1/3" style="animation-delay:2s; animation-duration:10s;"></div>
  <div class="bubble w-12 h-12 left-1/2" style="animation-delay:4s; animation-duration:16s;"></div>
  <div class="bubble w-8 h-8 left-2/3" style="animation-delay:6s; animation-duration:14s;"></div>
  <div class="bubble w-14 h-14 left-3/4" style="animation-delay:8s; animation-duration:18s;"></div>

  <!-- content -->
  <main class="relative z-10 text-center animate-fadeIn">
    <div class="bg-white/80 backdrop-blur-lg shadow-lg rounded-2xl px-10 py-12 max-w-md mx-auto">
      <svg class="w-16 h-16 text-indigo-600 mx-auto animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" stroke-opacity="0.15"/>
        <path d="M7 12.5l2.8 2.8L17 8" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <h1 class="mt-6 text-3xl font-bold text-gray-900">Thank You!</h1>
      <p class="mt-2 text-gray-600">Your exam has been submitted successfully.</p>
      <a href="/user/dashboard" class="mt-6 inline-block px-6 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition">
        Return to Dashboard
      </a>
    </div>
  </main>

  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
      animation: fadeIn 0.8s ease-out forwards;
    }
  </style>
  
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "sd7aqctqd1");
</script>
</body>
</html>
