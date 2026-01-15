<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Waiting for Tutor | Vaaga Academy</title>
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
    }
    
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: #ff98000f;
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
      background: var(--accent);
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
      margin-bottom: 40px;
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
    
    /* Floating particles */
    .particle {
      position: absolute;
      background: var(--primary-lighter);
      border-radius: 50%;
      pointer-events: none;
      z-index: 1;
    }
  </style>
</head>
<body>
  <!-- Background elements -->
  <div class="bg-element bg-element-1"></div>
  <div class="bg-element bg-element-2"></div>
  <div class="bg-element bg-element-3"></div>
  <div class="bg-element bg-element-4"></div>
  
  <!-- Floating particles will be added by JS -->
  
  <div class="waiting-container animate__animated animate__fadeIn">
    <div class="brand">
      <img src="https://vaagaacademy.com/newassets/img/favicon.png" alt="Vaaga Academy Logo" /> 
   
    </div>
    <br>
     
      <p>Welcome <strong>{{$user->name}}</strong></p>
    <div class="info-card">
      <h4>Demo Details</h4>
    
      <p>
        <strong><i class="fas fa-chalkboard-teacher me-2"></i>Tutor:</strong>
        <span>{{ucwords($teacher->name)}}</span>
      </p>
      <p>
        <strong><i class="far fa-calendar-alt me-2"></i>Date:</strong>
        <span>{{date("d M Y",strtotime($demo->demo_date_time))}} </span>
      </p>
       <p>
        <strong><i class="far fa-calendar-alt me-2"></i>Time:</strong>
        <span>{{date("h:i A",strtotime($demo->demo_date_time))}} IST</span>
      </p>
      <p>
        <strong><i class="fas fa-book me-2"></i>Subject:</strong>
        <span>{{$course->title}}</span>
      </p>
    </div>

    <div class="loader-container">
      <div class="loader-dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
      <p class="loader-text">Please wait while the tutor joins the session...</p>
      <p class="support-text">Having issues? <a href="https://wa.me/919234459949" target="_blank">Contact support</a></p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/newassets/js/jquery-3.5.1.min.js"></script>

  
  <script>
      
      var parent_api_class_id = "{{$demo->id}}";
      var batch_id ="{{$demo->id}}";
      
      function checkWaiting(){
          $.ajax({
              url:'/demo-check/'+parent_api_class_id,
              type:'GET',
              success:function(res){
                 if(res.can_join){
                     window.location.href="/user/getLaunch/"+batch_id+"/"+res.api_id;
                 }
              },
              error:function(){
                 
              }
          })
      }
      checkWaiting();
      setInterval(function(){checkWaiting()},5000)
      
      
      
  </script>
  
  <script>
    // Create floating particles
    document.addEventListener('DOMContentLoaded', function() {
      const colors = ['#4a2a51', '#6d3d7a', '#ff9800'];
      
      for (let i = 0; i < 15; i++) {
        createParticle();
      }
      
      function createParticle() {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        const size = Math.random() * 10 + 5;
        const color = colors[Math.floor(Math.random() * colors.length)];
        
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.background = color;
        particle.style.opacity = Math.random() * 0.2 + 0.05;
        
        // Random position
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        
        // Animation
        const duration = Math.random() * 20 + 10;
        const delay = Math.random() * 5;
        const xMovement = Math.random() * 100 - 50;
        const yMovement = Math.random() * 100 - 50;
        
        particle.style.animation = `floatParticle ${duration}s ease-in-out ${delay}s infinite`;
        
        document.body.appendChild(particle);
        
        // Add keyframes dynamically
        const style = document.createElement('style');
        style.innerHTML = `
          @keyframes floatParticle {
            0%, 100% {
              transform: translate(0, 0);
            }
            25% {
              transform: translate(${xMovement}px, ${yMovement}px);
            }
            50% {
              transform: translate(${xMovement * 1.5}px, ${yMovement * 1.5}px);
            }
            75% {
              transform: translate(${xMovement}px, ${yMovement}px);
            }
          }
        `;
        document.head.appendChild(style);
      }
    });
  </script>
</body>
</html>