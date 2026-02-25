<?php
    use Carbon\Carbon;
        $testDate = Carbon::parse($test->test_date_time);

 $displayDate = $testDate->timezone('Asia/Kolkata')->format('d M Y');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Waiting for Test | Vaaga Academy</title> 
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="shortcut icon" href="https://vaagaacademy.com/newassets/img/favicon.png">

  <style>
    :root {
      --primary: #4a2a51;
      --primary-light: #6d3d7a;
      --primary-lighter: #f0e6f2;
      --dark: #1e293b;
      --light: #f8fafc;
      --gray: #94a3b8;
      --accent: #ff9800;
      --success: #28a745;
    }
    
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: #f0e6f2;
      overflow: hidden;
      position: relative;
    }
    
    .waiting-container {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 20px;
      position: relative;
      z-index: 2;
    }
    
    /* Background elements */
    .bg-element {
      position: absolute;
      opacity: 0.1;
      z-index: 1;
    }
    
    .bg-element-1 {
      top: 10%;
      left: 5%;
      width: 150px;
      height: 150px;
      background: var(--primary);
      border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
      animation: float 8s ease-in-out infinite;
    }
    
    .bg-element-2 {
      bottom: 15%;
      right: 8%;
      width: 200px;
      height: 200px;
      background: var(--accent);
      border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
      animation: float 10s ease-in-out infinite;
    }
    
    .bg-element-3 {
      top: 60%;
      left: 15%;
      width: 100px;
      height: 100px;
      background: var(--primary-light);
      border-radius: 50%;
      animation: pulse 4s ease infinite;
    }
    
    .bg-element-4 {
      top: 20%;
      right: 15%;
      width: 80px;
      height: 80px;
      background: var(--success);
      border-radius: 40% 60% 60% 40% / 70% 50% 50% 30%;
      animation: float 7s ease-in-out infinite reverse;
    }
    
    @keyframes float {
      0%, 100% {
        transform: translateY(0) rotate(0deg);
      }
      50% {
        transform: translateY(-20px) rotate(5deg);
      }
    }
    
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
        opacity: 0.1;
      }
      50% {
        transform: scale(1.2);
        opacity: 0.15;
      }
    }
    
    .info-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      padding: 32px;
      max-width: 420px;
      width: 100%;
      border: 1px solid rgba(0,0,0,0.03);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
      z-index: 2;
      backdrop-filter: blur(5px);
      background: rgba(255, 255, 255, 0.9);
    }
    
    .info-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    
    .info-card h4 {
      color: var(--primary);
      font-weight: 600;
      margin-bottom: 24px;
      position: relative;
    }
    
    .info-card h4::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background: var(--primary-light);
      border-radius: 3px;
    }
    
    .info-card p {
      margin-bottom: 12px;
      color: var(--dark);
      display: flex;
      justify-content: space-between;
    }
    
    .info-card p strong {
      color: var(--dark);
      font-weight: 500;
    }
    
    .info-card p span {
      color: var(--gray);
      text-align: right;
    }
    
    .loader-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      z-index: 2;
    }
    
    .loader-text {
      color: var(--dark);
      font-size: 1.1rem;
      margin-top: 24px;
      font-weight: 500;
    }
    
    .loader-dots {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .dot {
      width: 12px;
      height: 12px;
      background-color: var(--primary);
      border-radius: 50%;
      margin: 0 6px;
      animation: bounce 1.4s infinite ease-in-out;
    }
    
    .dot:nth-child(1) {
      animation-delay: -0.32s;
    }
    
    .dot:nth-child(2) {
      animation-delay: -0.16s;
    }
    
    @keyframes bounce {
      0%, 80%, 100% { 
        transform: translateY(0);
        opacity: 0.5;
      }
      40% {
        transform: translateY(-20px);
        opacity: 1;
      }
    }
    
    .brand {
      position: absolute;
      top: 30px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary);
      display: flex;
      align-items: center;
      z-index: 3;
    }
    
    .brand img {
      margin-right: 10px;
      height: 30px;
      width: auto;
    }
    
    .support-text {
      margin-top: 40px;
      color: var(--gray);
      font-size: 0.9rem;
    }
    
    .support-text a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }
    
    .support-text a:hover {
      color: var(--primary-light);
      text-decoration: underline;
    }
    
    /* Countdown timer */
    .countdown {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--primary);
      margin: 15px 0;
    }
    
    /* Test rules */
    .test-rules {
      text-align: left;
      margin-top: 20px;
      padding: 15px;
      background: var(--primary-lighter);
      border-radius: 8px;
    }
    
    .test-rules h6 {
      color: var(--primary);
      margin-bottom: 10px;
    }
    
    .test-rules ul {
      padding-left: 20px;
      margin-bottom: 0;
    }
    
    .test-rules li {
      margin-bottom: 8px;
      color: var(--dark);
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <!-- Background elements -->
  <div class="bg-element bg-element-1"></div>
  <div class="bg-element bg-element-2"></div>
  <div class="bg-element bg-element-3"></div>
  <div class="bg-element bg-element-4"></div>
  
  <div class="waiting-container animate__animated animate__fadeIn">
    <div class="brand">
      <img src="https://vaagaacademy.com/newassets/img/favicon.png" alt="Vaaga Academy Logo" /> 
   
    </div>
    
    <div class="info-card" style="margin-top:150px;">
      <h4>Test Details</h4>
 
      <p> 
        <strong><i class="far fa-calendar-alt me-2"></i>Date:</strong>
        <span id="local-date-display">{{$displayDate}} </span>
      </p>
      <p>
        <div id="timezone-info" class="small text-muted mb-2"></div>
      </p>
      
      <div class="test-rules">
        <h6><i class="fas fa-info-circle me-2"></i>Test Rules:</h6>
        <ul>
          <li>Test will automatically submit when time expires</li>
          <li>Don't refresh or close the test window</li>
        </ul>
      </div>
    </div>

    <div class="loader-container">
      <div class="countdown" id="countdown">
        Waiting for test date...
      </div>
      <div class="loader-dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
      <p class="loader-text" id="status-message">Preparing your test session...</p>
      <p class="support-text">Having issues? <a href="https://wa.me/919234459949" target="_blank">Contact support</a></p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
   // Test date from PHP (IST converted to Y-m-d format)
const displayDate = "{{ date('Y-m-d', strtotime($test->test_date_time)) }}"; 

function checkTestStatus() {
    const now = new Date();

    // Get user's local date in Y-m-d format
    const localDate = now.getFullYear() + "-" +
        String(now.getMonth() + 1).padStart(2, '0') + "-" +
        String(now.getDate()).padStart(2, '0');

    // Display user's local datetime + timezone
    const options = {
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    };
    const localDateTime = now.toLocaleString(undefined, options);
    const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    document.getElementById("timezone-info").innerHTML =
        `(Your local date & time: ${localDateTime} | Timezone: ${timeZone})`;

    if (localDate > displayDate) {
        // Date already passed
        document.getElementById("countdown").innerHTML = "Test expired";
        document.getElementById("status-message").innerHTML = "You can no longer access this test.";
    } 
    else if (localDate < displayDate) {
        // Date is in future
        document.getElementById("countdown").innerHTML = `Test will start on ${displayDate}`;
        document.getElementById("status-message").innerHTML = "Please check back on the test date.";
    } 
    else {
        // Today is test date → start test
        document.getElementById("countdown").innerHTML = "Test is available!";
        document.getElementById("status-message").innerHTML = "Redirecting to test platform...";
        startTest();
    }
}

function startTest() {
    window.location.href = `https://exam.vaagaacademy.com/autologin/{{$batch_id}}/{{$user_id->id}}/{{$test_id}}`;
}

// Initial check and periodic refresh
checkTestStatus();
setInterval(checkTestStatus, 60 * 1000); // check every minute


  </script>
</body>
</html>