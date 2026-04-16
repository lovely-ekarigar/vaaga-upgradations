<?php 

use App\Models\TestSeries;
use App\Models\TestSeriesPurchase;
?>
@extends('frontend.layout.sub-master')
@section('title')

<title>Olympiad Test Series for Grade 2–8 - VaaGa Academy | {{env('APP_NAME')}}</title>

<meta name="description" content="VaaGa Academy offers comprehensive Olympiad Test Series for Grade 2–8 students. Prepare for IMO, NSO & IEO exams with mock tests, performance tracking, and personalized feedback.">

<meta name="keywords" content="Olympiad test series, IMO test series, NSO test series, IEO test series, Olympiad mock tests for grade 2-8, online Olympiad practice tests, VaaGa Academy Olympiad preparation">

<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Olympiad Test Series for Grade 2–8 - VaaGa Academy | {{env('APP_NAME')}}" />
<meta property="og:description" content="VaaGa Academy offers comprehensive Olympiad Test Series for Grade 2–8 students. Prepare for IMO, NSO & IEO exams with mock tests, performance tracking, and personalized feedback." />
<meta property="og:url" content="{{URL::to('/test-series')}}" />
<meta property="og:site_name" content="{{env('APP_NAME')}}" />
<meta property="article:published_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
<meta property="article:modified_time" content="{{date('Y-m-d H:i:s',strtotime('-7 days',time()))}}" />
<meta property="og:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{env('TWITTER_HANDLE')}}" />
<meta name="twitter:title" content="Olympiad Test Series for Grade 2–8 - VaaGa Academy | {{env('APP_NAME')}}" />
<meta name="twitter:description" content="VaaGa Academy offers comprehensive Olympiad Test Series for Grade 2–8 students. Prepare for IMO, NSO & IEO exams with mock tests, performance tracking, and personalized feedback." />
<meta name="twitter:image" content="https://www.vaagaacademy.com/newassets/img/logo.webp" />

<link rel="canonical" href="{{URL::to('/test-series')}}">

@stop

@section('page_css')
<style type="text/css">

  .test-series-page {
    margin-top: 60px;
    padding: 20px;
    min-height: calc(100vh - 80px);
    background: #f8f9fa;
  } 
  
  .layout-container {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 25px;
    max-width: 1200px;
    margin: 0 auto;
    align-items: start;
  }
  
  /* Promotion Sidebar */
  .promotion-sidebar {
    background: white;
    border-radius: 8px;
    /*padding: 20px;*/
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: sticky;
    top: 80px;
  }
  
  .promo-header {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
  }
  
  .promo-features {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .promo-features li {
    padding: 8px 0;
    font-size: 0.85rem;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  
  .promo-features li:before {
    content: "✓";
    color: #28a745;
    font-weight: bold;
    font-size: 0.9rem;
  }
  
  .stats-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 15px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
  }
  
  .stat-item {
    text-align: center;
  }
  
  .stat-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: #007bff;
  }
  
  .stat-label {
    font-size: 0.7rem;
    color: #6c757d;
    margin-top: 2px;
  }
  
  /* Course Selection */
  .course-selection {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
  }
  
  .selection-header {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 12px;
  }
  
  .course-dropdown {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.9rem;
    background: white;
  }
  
  /* Test Series Grid */
 .test-series-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 equal columns */
    gap: 15px; /* space between items */
}

  
  .test-series-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
  }
  
  .test-header {
    margin-bottom: 12px;
  }
  
  .test-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
    line-height: 1.3;
  }
  
  .test-meta {
    display: flex;
    gap: 10px;
    font-size: 0.75rem;
    color: #6c757d;
  }
  
  .test-features {
    margin: 12px 0;
  }
  
  .feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .feature-list li {
    padding: 4px 0;
    padding-left: 18px;
    font-size: 0.8rem;
    color: #495057;
    position: relative;
  }
  
  .feature-list li:before {
    content: "•";
    color: #007bff;
    position: absolute;
    left: 8px;
    font-weight: bold;
  }
  
  .pricing {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 6px;
    margin: 12px 0;
  }
  
  .price-main {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 3px;
  }
  
  .current-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #28a745;
  }
  
  .original-price {
    font-size: 0.85rem;
    color: #6c757d;
    text-decoration: line-through;
  }
  
  .discount {
    background: #dc3545;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.7rem;
    font-weight: 600;
  }
  
  .price-note {
    font-size: 0.7rem;
    color: #6c757d;
  }
  
  .action-buttons {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
  }
  
  .btn-cart {
    background: #6c757d;
    color: white;
    border: none;
    padding: 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
  }
  
  .btn-buy {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
  }
  
  .btn-cart:hover { background: #5a6268; }
  .btn-buy:hover { background: #218838; }

  /* Mobile Responsive */
  @media (max-width: 968px) {
    .layout-container {
      grid-template-columns: 1fr;
      gap: 15px;
    }
    
    .promotion-sidebar {
      position: static;
      order: 2;
    }
    
    .test-series-grid {
      grid-template-columns: 1fr;
    }
  }
  
  @media (max-width: 480px) {
    .test-series-page {
      margin-top: 50px;
      padding: 15px;
    }
    
    .course-selection,
    .promotion-sidebar,
    .test-series-card {
      padding: 12px;
    }
    
    .stats-container {
      grid-template-columns: 1fr;
      gap: 8px;
    }
  }
  
  .promotion-sidebar {
  position: sticky;
  top: 80px;
}

.promo-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  border: 1px solid #e9ecef;
  text-align: center;
}

.promo-badge {
  background: linear-gradient(135deg, #ff6b6b, #ee5a24);
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.7rem;
  font-weight: 600;
  display: inline-block;
  margin-bottom: 15px;
}

.promo-main {
  margin-bottom: 20px;
}

.promo-icon {
  font-size: 2.5rem;
  margin-bottom: 10px;
}

.promo-main h4 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #2c3e50;
  margin: 0 0 5px 0;
}

.promo-main p {
  font-size: 0.85rem;
  color: #6c757d;
  margin: 0;
}

.key-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin: 20px 0;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 10px;
}

.stat {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.stat-value {
  font-size: 1.3rem;
  font-weight: 700;
  color: #28a745;
}

.stat-label {
  font-size: 0.75rem;
  color: #6c757d;
  margin-top: 2px;
}

.quick-features {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin: 15px 0;
}

.feature {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px;
  background: #f8f9fa;
  border-radius: 6px;
  font-size: 0.8rem;
  color: #495057;
}

.feature-icon {
  font-size: 1rem;
}

.trust-badge {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px;
  background: #e8f5e8;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 600;
  color: #2e7d32;
  margin-top: 15px;
}

.trust-icon {
  font-size: 1rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .promo-card {
    padding: 15px;
  }
  
  .promo-icon {
    font-size: 2rem;
  }
  
  .promo-main h4 {
    font-size: 1rem;
  }
  
  .key-stats {
    gap: 10px;
    padding: 12px;
  }
  
  .stat-value {
    font-size: 1.1rem;
  }
}

.course-selection-not-found {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    /*background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);*/
    border-radius: 6px;
    padding: 40px;
    text-align: center;
    /*border: 1px solid rgba(79, 189, 216, 0.2);*/
    box-shadow: 
        0 10px 30px rgba(79, 189, 216, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

/* Animated background elements */
.course-selection-not-found::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(79, 189, 216, 0.05) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

.course-selection-not-found::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 20px;
    /*padding: 2px;*/
    background: linear-gradient(135deg, rgba(79, 189, 216, 0.3), rgba(79, 189, 216, 0.1));
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
}

.course-selection-not-found svg {
    width: 80px;
    height: 80px;
    margin-bottom: 20px;
    filter: drop-shadow(0 5px 15px rgba(79, 189, 216, 0.3));
    animation: bounce 2s ease-in-out infinite;
    position: relative;
    z-index: 2;
}

.course-selection-not-found p {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    font-size: 1.4rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    position: relative;
    z-index: 2;
    background: linear-gradient(135deg, #4FBDD8 0%, #2D8BAB 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Subtle pulsing dot */
.course-selection-not-found .pulse-dot {
    width: 8px;
    height: 8px;
    background: #4FBDD8;
    border-radius: 50%;
    margin-top: 15px;
    animation: pulse 2s ease-in-out infinite;
    position: relative;
    z-index: 2;
}

/* Animations */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.2);
    }
}

@keyframes float {
    0%, 100% {
        transform: translate(0, 0) rotate(0deg);
    }
    33% {
        transform: translate(30px, -30px) rotate(120deg);
    }
    66% {
        transform: translate(-20px, 20px) rotate(240deg);
    }
}

/* Hover effects */
.course-selection-not-found:hover {
    transform: translateY(-2px);
    box-shadow: 
        0 15px 40px rgba(79, 189, 216, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
}

/* Responsive design */
@media (max-width: 768px) {
    .course-selection-not-found {
        min-height: 250px;
        padding: 30px 20px;
        border-radius: 16px;
    }
    
    .course-selection-not-found svg {
        width: 60px;
        height: 60px;
    }
    
    .course-selection-not-found p {
        font-size: 1.2rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .course-selection-not-found {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
        border-color: rgba(79, 189, 216, 0.3);
    }
    
    .course-selection-not-found p {
        background: linear-gradient(135deg, #4FBDD8 0%, #63d3f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
}

 :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
            --gradient: linear-gradient(135deg, #4A2A51 0%, #4A2A51 100%);
            --shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            --radius: 16px;
        }

        .modal-content {
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            background: white;
            position: relative;
        }

        .modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
        }

        .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            /*padding: 1.5rem 1.5rem 0.5rem;*/
            background: #e9e6e6;
            position: relative;
            padding: 10px 20px;
        }

        .modal-title {
            font-weight: 700;
            color: var(--dark);
            font-size: 1rem;
            background: #000;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-close {
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 1rem 1.5rem 1.5rem;
        }

        .nav-tabs {
            border: none;
            margin-bottom: 1.5rem;
            position: relative;
            background: #f1f5f9;
            border-radius: 12px;
            padding: 4px;
        }

        .nav-tabs .nav-item {
            flex: 1;
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--gray);
            font-weight: 600;
            /*padding: 0.6rem 1rem;*/
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            background: transparent;
                padding: 4px 0px;
        }

        .nav-tabs .nav-link.active {
            color: white;
            background: var(--gradient);
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }

        .nav-tabs .nav-link:not(.active):hover {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }

        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-control-sm {
            padding: 0.65rem 0.9rem;
        }

     
     


        .tab-pane {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom form styling */
        .form-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-label {
            position: absolute;
            top: -8px;
            left: 12px;
            background: white;
            padding: 0 4px;
            font-size: 0.8rem;
            color: var(--primary);
            font-weight: 600;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            opacity: 1;
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-content {
                border-radius: 12px;
            }
            
            .nav-tabs .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
        }
        
        
      .promotional-section {
    background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);
    padding: 50px 20px;
    margin-top: 40px;
    border-top: 1px solid #d1e0ff;
}

.promo-container {
    max-width: 1200px;
    margin: 0 auto;
}

.promo-header {
    text-align: center;
    margin-bottom: 40px;
}

.promo-header h2 {
    font-size: 2.2rem;
    color: #1e293b;
    margin-bottom: 10px;
    font-weight: 700;
    background: linear-gradient(135deg, #1e40af 0%, #4A2A51 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.promo-header .subtitle {
    font-size: 1.1rem;
    color: #475569;
    max-width: 600px;
    margin: 0 auto;
    font-weight: 500;
}

.countdown-banner {
    background: linear-gradient(135deg, #1e40af 0%, #4A2A51 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 40px;
    color: white;
    box-shadow: 0 10px 30px rgba(30, 64, 175, 0.3);
}

.countdown-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.countdown-icon {
    font-size: 2.5rem;
}

.countdown-text h3 {
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.countdown-timer {
    display: flex;
    gap: 15px;
}

.time-unit {
    text-align: center;
    background: rgba(255, 255, 255, 0.2);
    padding: 10px 15px;
    border-radius: 8px;
    min-width: 70px;
    backdrop-filter: blur(10px);
}

.time-value {
    font-size: 1.8rem;
    font-weight: 700;
    display: block;
}

.time-label {
    font-size: 0.8rem;
    opacity: 0.9;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-bottom: 50px;
}

.benefit-card {
    background: white;
    border-radius: 12px;
    padding: 25px 20px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-top: 4px solid #1e40af;
}

.benefit-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(30, 64, 175, 0.15);
}

.benefit-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.benefit-card h3 {
    font-size: 1.2rem;
    color: #1e293b;
    margin-bottom: 12px;
}

.benefit-card p {
    color: #64748b;
    line-height: 1.5;
}

.olympiad-features {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.olympiad-features h3 {
    text-align: center;
    font-size: 1.5rem;
    color: #1e293b;
    margin-bottom: 25px;
    font-weight: 600;
}

.features-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.feature-column {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    background: #f8fafc;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: #e0f2fe;
    transform: translateX(5px);
}

.feature-bullet {
    color: #1e40af;
    font-weight: bold;
    font-size: 1.1rem;
}

.parent-testimonials {
    margin-bottom: 50px;
}

.parent-testimonials h3 {
    text-align: center;
    font-size: 1.8rem;
    color: #1e293b;
    margin-bottom: 30px;
    font-weight: 600;
}

.testimonial-slider {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.testimonial {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border-left: 4px solid #1e40af;
}

.testimonial-content p {
    font-style: italic;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 15px;
}

.testimonial-author {
    color: #1e40af;
    font-weight: 600;
}

.success-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px 15px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 5px;
}

.stat-desc {
    color: #64748b;
    font-weight: 600;
}

.scholarship-highlight {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border: 2px solid #f59e0b;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 40px;
}

.scholarship-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.scholarship-icon {
    font-size: 3rem;
}

.scholarship-text h3 {
    color: #92400e;
    margin-bottom: 5px;
    font-size: 1.3rem;
}

.scholarship-text p {
    color: #b45309;
    margin: 0;
}

.cta-section {
    background: white;
    border-radius: 12px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 2px solid #1e40af;
}

.cta-section h3 {
    font-size: 1.8rem;
    color: #1e293b;
    margin-bottom: 10px;
}

.cta-section p {
    color: #64748b;
    margin-bottom: 25px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.cta-button {
    background: linear-gradient(135deg, #1e40af 0%, #4A2A51 100%);
    color: white;
    border: none;
    padding: 15px 30px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(30, 64, 175, 0.3);
}

.cta-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(30, 64, 175, 0.4);
}

.urgency-note {
    margin-top: 15px;
    color: #dc2626;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .promo-header h2 {
        font-size: 1.8rem;
    }
    
    .countdown-content {
        justify-content: center;
        text-align: center;
    }
    
    .countdown-timer {
        justify-content: center;
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
    }
    
    .success-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .scholarship-content {
        flex-direction: column;
        text-align: center;
    }
    
    .features-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .promotional-section {
        padding: 30px 15px;
    }
    
    .countdown-timer {
        gap: 10px;
    }
    
    .time-unit {
        min-width: 60px;
        padding: 8px 10px;
    }
    
    .time-value {
        font-size: 1.5rem;
    }
    
    .success-stats {
        grid-template-columns: 1fr;
    }
    
    .testimonial-slider {
        grid-template-columns: 1fr;
    }
} 
</style>

<style>
.google-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 10px 15px;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-weight: 500;
  color: #444;
  transition: all 0.2s ease;
}
.google-btn:hover {
  background-color: #f7f7f7;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.google-btn img {
  width: 22px;
  height: 22px;
}
 .popup {
    position: fixed;
    bottom: 25px;
    left: 25px;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: #fff;
    padding: 12px 18px;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 500;
    display: none;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.25);
    z-index: 9999;
    animation: fadeInOut 3.5s ease-in-out;
  }

  .popup.show {
    display: flex;
  }

  .popup span {
    font-weight: 600;
    color: #fff;
  }

  @keyframes fadeInOut {
    0% {opacity: 0; transform: translateY(20px);}
    10%, 90% {opacity: 1; transform: translateY(0);}
    100% {opacity: 0; transform: translateY(20px);}
  }
  
  .course-selection-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding: 10px;
}

@media (max-width: 640px) {
    .course-selection-container {
        grid-template-columns: 1fr;
    }
}

.course-card {
    display: block;
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 14px 18px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.course-card:hover,
.course-card.active {
    background: linear-gradient(135deg, #007bff, #0056d2);
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.course-card-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.course-title {
    font-size: 16px;
    font-weight: 600;
    color: inherit;
    margin: 0;
}

.course-arrow {
    font-size: 18px;
    font-weight: bold;
    color: inherit;
    transition: transform 0.3s ease;
}

.course-card:hover .course-arrow {
    transform: translateX(5px);
}

</style>

@stop

@section('content')
<main>
  <div class="test-series-page">
    <div class="layout-container">
      
      <!-- Left Side - Test Series -->
      <div class="content-main">
        <!-- Course Selection -->
        <div class="course-selection">
          <div class="selection-header">Select Course</div>
          <select class="course-dropdown" id="courseSelector">
            <option value="">-- Choose Course --</option>
            @foreach($courses as $c)
            
            <?php  $tsx = TestSeries::where('course_id',$c->id)->where('status','1')->orderBy("offer_price","desc")->count(); ?>
            @if($tsx>0)
            <option value="{{$c->slug}}" @if($course) @if($course->id==$c->id) selected @endif @endif>{{$c->title}}</option>
            @endif
            @endforeach
          </select> 
        </div>
 @if($course)
   @if(count($testSeries)>0)
        <!-- Test Series Grid -->
        <div class="test-series-grid">
         
        
       
         
         @foreach($testSeries as $test)
         @if($test->status=='1')
          <!-- Test Series 1 -->
          <div class="test-series-card">
            <div class="test-header">
              <div class="test-title">{{$test->name}}</div>
              <div class="test-meta">
                <span>📊 {{$test->total_test}} Tests</span>
                <span>⏱️ {{$test->validity}}</span>
              </div>
            </div>
            
            <div class="test-features">
                <?php $items = explode(",",$test->detail) ?>
              <ul class="feature-list">
                  @foreach($items as $item)
                <li>{{trim($item)}}</li>
                @endforeach
              </ul>
            </div>
            
            <div class="pricing">
              <div class="price-main">
                <span class="current-price">₹{{number_format($test->offer_price,0)}}</span>
                <span class="original-price">₹{{number_format($test->price,0)}}</span>
                <span class="discount">{{number_format(($test->price-$test->offer_price)*100/$test->price,0)}}% OFF</span>
              </div>
              <div class="price-note">One-time payment • {{$test->validity}} access</div>
            </div>
            
            <div class="action-buttons">
            @if(!in_array($test->id,$alreadyPurchased))
                 <button class="btn-buy" data-id="{{base64_encode($test->id)}}" data-name="{{$test->name}}" data-price="{{$test->offer_price}}">
        Buy Now
    </button>
    @else
    <?php $myTest = TestSeriesPurchase::where('user_id',Auth::user()->id)->where("test_series_id",$test->id)->where('payment_status','paid')->first() ?>
     <a href="/user/my-test/{{$myTest->id}}"  style="background: #f8f9fa;
    border: 2px solid #f8f9fa;
    color: #FF9800; text-align:center;">
        Access Test Series
        </a>
    @endif
            </div>
          </div>
          @endif
          @endforeach
          
           </div>
          @else
          
          <div class="course-selection-not-found">
    <!-- SVG Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#4FBDD8" viewBox="0 0 24 24">
        <path d="M19 2H9a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm0 18H9V4h10v16zM7 6H5v14a2 2 0 0 0 2 2h12v-2H7V6z"/>
    </svg>
    <p>Test series not found for selected course.</p>
    <div class="pulse-dot"></div>
</div>
@endif



         

       
        
        @else

<div class="course-selection-container">
    @foreach($courses as $c)
        <?php  
            $tsx = \App\Models\TestSeries::where('course_id', $c->id)
                ->where('status', '1')
                ->orderBy("offer_price", "desc")
                ->count();  
        ?>
        @if($tsx > 0)
            <a 
                href="{{ url('test-series/'.$c->slug) }}" 
                class="course-card @if($course && $course->id == $c->id) active @endif"
            >
                <div class="course-card-content">
                    <h3 class="course-title">{{ $c->title }}</h3>
                    <span class="course-arrow">→</span>
                </div>
            </a>
        @endif
    @endforeach
</div>




@endif
      </div>

      <!-- Right Side - Promotion -->
   <div class="promotion-sidebar">
  <div class="promo-card">
    <div class="promo-badge">🔥 Popular Choice</div>
    
    <div class="promo-main">
      <div class="promo-icon">🏆</div>
      <h4>Top Rated Test Series</h4>
      <p>Join 10,000+ successful students</p>
    </div>

    <div class="key-stats">
      <div class="stat">
        <span class="stat-value">4.9★</span>
        <span class="stat-label">Rating</span>
      </div>
      <div class="stat">
        <span class="stat-value">95%</span>
        <span class="stat-label">Success</span>
      </div>
    </div>

    <div class="quick-features">
      <div class="feature">
        <span class="feature-icon">📊</span>
        <span>AI Analytics</span>
      </div>
      <div class="feature">
        <span class="feature-icon">💡</span>
        <span>Expert Support</span>
      </div>
    </div>

    <div class="trust-badge">
      <span class="trust-icon">✅</span>
      <span>Trusted by Toppers</span>
    </div>
  </div>
</div>

    </div>
    
    <section class="promotional-section">
    <div class="promo-container">
        <div class="promo-header">
            <h2>Why Choose VaaGa Academy Olympiad Test Series?</h2>
            <p class="subtitle">Give Your Child the Winning Edge in National & International Olympiads</p>
        </div>
        
        <div class="countdown-banner">
            <div class="countdown-content">
                <div class="countdown-icon">🏆</div>
                <div class="countdown-text">
                    <h3 class="text-white">Early Bird Olympiad Registration!</h3>
                    <p>Special test series pricing ends in:</p>
                </div>
                <div class="countdown-timer" id="countdown">
                    <div class="time-unit">
                        <span class="time-value" id="days">03</span>
                        <span class="time-label">Days</span>
                    </div>
                    <div class="time-unit">
                        <span class="time-value" id="hours">18</span>
                        <span class="time-label">Hours</span>
                    </div>
                    <div class="time-unit">
                        <span class="time-value" id="minutes">22</span>
                        <span class="time-label">Minutes</span>
                    </div>
                    <div class="time-unit">
                        <span class="time-value" id="seconds">45</span>
                        <span class="time-label">Seconds</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🔬</div>
                <h3>Subject-Specific Olympiad Tests</h3>
                <p>Specialized test series for Science and Mathematics Olympiads with IMO, NSO, IEO patterns.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">📈</div>
                <h3>Performance Tracking</h3>
                <p>        Track your child’s progress in real time with our comprehensive performance tracking system. 
</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">🎯</div>
                <h3>Olympiad Pattern Questions</h3>
                <p>Higher-order thinking questions that match actual Olympiad standards and difficulty levels.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">🏅</div>
                <h3>Previous Toppers' Strategies</h3>
                <p>Learn from the techniques and approaches used by national rank holders and medal winners.</p>
            </div>
        </div>

        <div class="olympiad-features">
            <h3>Comprehensive Olympiad Preparation</h3>
            <div class="features-container">
                <div class="feature-column">
                    <div class="feature-item">
                        <span class="feature-bullet">✓</span>
                        <span>IMO (International Mathematics Olympiad)</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-bullet">✓</span>
                        <span>NSO (National Science Olympiad)</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-bullet">✓</span>
                        <span>IEO (International English Olympiad)</span>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="parent-testimonials">
            <h3>Success Stories from Olympiad Champions</h3>
            <div class="testimonial-slider">
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"VaaGa Academy's Olympiad test series was a game-changer for my daughter. She secured Gold in NSO and Silver in IMO - all thanks to the strategic preparation!"</p>
                        <div class="testimonial-author">
                            <strong>- Mrs. Reddy, Parent of National Olympiad Medalist</strong>
                        </div>
                    </div>
                </div>
                <div class="testimonial">
    <div class="testimonial-content">
        <p>
            "The Performance Tracking system gave us a clear picture of my son's progress in every topic. 
            With detailed insights and personalized feedback, he was able to focus on his weaker areas and improve consistently. 
            The structured tracking and rating system truly motivated him to excel in Mathematics!"
        </p>
        <div class="testimonial-author">
            <strong>- Mr. Patel, Parent of a High-Performing Student</strong>
        </div>
    </div>
</div>

            </div>
        </div>
        
        <div class="success-stats">
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-desc">Olympiad Medalists</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">92%</div>
                <div class="stat-desc">Selection Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">4.9/5</div>
                <div class="stat-desc">Parent Rating</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">4+</div>
                <div class="stat-desc">Years Olympiad Excellence</div>
            </div>
        </div>

        <div class="scholarship-highlight">
            <div class="scholarship-content">
                <div class="scholarship-icon">💰</div>
                <div class="scholarship-text">
    <h3>Excel in Olympiads, Shine Bright!</h3>
    <p>Top performers in our test series gain recognition for their hard work and talent, standing out among peers and unlocking new opportunities for growth.</p>
</div>

            </div>
        </div>
        
        <div class="cta-section">
            <h3>Build Your Child's Olympiad Journey Today!</h3>
            <p>Don't let your child miss this opportunity to compete at national level and win prestigious awards.</p>
            <button class="cta-button">Enroll in Olympiad Test Series Now!</button>
            <p class="urgency-note">🏅 <strong>Limited Seats!</strong> Only 50 spots available</p>
        </div>
    </div>
</section>

    
  </div>
</main>

<div id="purchasePopup" class="popup">
  🛍️ <span id="popupText"></span>
</div>

<!-- Coupon Modal -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Apply Coupon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-0">
        <div class="mb-3">
          <p class="mb-1" style="font-size:14px;">Test Series: <strong id="coupon-ts-name"></strong></p>
          <p class="mb-2" style="font-size:14px;">Price: <strong>₹<span id="coupon-ts-price"></span></strong></p>
        </div>
        <div class="input-group mb-2">
          <input type="text" class="form-control form-control-sm" id="ts-coupon-code" placeholder="Enter coupon code">
          <button class="btn btn-sm btn-primary" type="button" id="apply-ts-coupon">Apply</button>
        </div>
        <div id="ts-coupon-msg" class="mb-2" style="font-size:13px;"></div>
        <div id="ts-coupon-summary" class="d-none mb-3" style="font-size:14px; background:#f0f9f0; padding:10px; border-radius:6px;">
          <div>Discount: <strong>₹<span id="ts-discount-amt">0</span></strong></div>
          <div>Final Amount: <strong>₹<span id="ts-final-amt">0</span></strong></div>
        </div>
        <button class="btn btn-warning w-100" id="proceed-to-pay" type="button">Proceed to Pay</button>
        <button class="btn btn-link btn-sm w-100 mt-1" id="skip-coupon" type="button" style="font-size:13px;">Skip & Pay Full Price</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">

      <div class="modal-header border-0">
        <h5 class="modal-title">Login / Register</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body pt-3">
           <!-- Custom Google Login Button -->
            <button id="googleLoginBtn" class="google-btn w-100 mb-2">
              <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo">
              <span>Continue with Google</span>
            </button>
              <!-- OR separator -->
            <div class="text-center my-3 text-muted">— or —</div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified mb-3" id="authTab">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login">Login</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#register">Register</button>
          </li>
        </ul>

        <div class="tab-content">
           
           

          <!-- Login Form -->
          <div class="tab-pane fade show active" id="login">
            <form id="loginForm" class="p-1">
              <div class="form-group">
                <input type="email" name="email" class="form-control form-control-sm" placeholder="Email" required>
                <label class="form-label">Email</label>
              </div>
              <div class="form-group position-relative">
                <input type="password" name="password" class="form-control form-control-sm" placeholder="Password" required>
                <label class="form-label">Password</label>
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Login</button>
            </form>

           
          </div>

          <!-- Register Form -->
          <div class="tab-pane fade" id="register">
            <form id="registerForm" class="p-1">
              <div class="row g-2 mb-2">
                <div class="col form-group">
                  <input type="text" name="first_name" class="form-control form-control-sm" placeholder="First Name" required>
                  <label class="form-label">First Name</label>
                </div>
                <div class="col form-group">
                  <input type="text" name="last_name" class="form-control form-control-sm" placeholder="Last Name" required>
                  <label class="form-label">Last Name</label>
                </div>
              </div>
              <div class="form-group">
                <input type="email" name="email" class="form-control form-control-sm" placeholder="Email" required>
                <label class="form-label">Email</label>
              </div>
              <div class="form-group">
                <input type="text" name="phone" class="form-control form-control-sm" placeholder="Phone" required>
                <label class="form-label">Phone</label>
              </div>
              <div class="form-group position-relative">
                <input type="password" name="password" class="form-control form-control-sm" placeholder="Password" required>
                <label class="form-label">Password</label>
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <div class="form-group position-relative">
                <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Confirm Password" required>
                <label class="form-label">Confirm Password</label>
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <button type="submit" class="btn btn-success btn-sm w-100 mt-2">Register & Buy</button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<input type="hidden" id="selected_test" />
@stop

@section('page_js')
<script>
$(document).ready(function() {
   $(document).on("change","#courseSelector",function(){
       
       
       var slug = $(this).val();
       window.location.href=slug
   })
   
   // Setup CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var testId='';
var tsCouponId = '';
var tsName = '';
var tsPrice = 0;
   $(document).ready(function() {
     $(".btn-buy").click(function(){
         testId = $(this).data("id");
         tsName = $(this).data("name");
         tsPrice = $(this).data("price");
         $("#selected_test").val(testId);
        @if(Auth::check())
            // User is logged in, show coupon modal
            tsCouponId = '';
            $("#ts-coupon-code").val('');
            $("#ts-coupon-msg").html('');
            $("#ts-coupon-summary").addClass('d-none');
            $("#coupon-ts-name").text(tsName);
            $("#coupon-ts-price").text(tsPrice);
            $("#ts-final-amt").text(tsPrice);
            $("#couponModal").modal("show");
        @else
            // User not logged in, show modal
            $("#authModal").modal("show");
        @endif
    });
    
    // Apply coupon for test series
    $("#apply-ts-coupon").click(function(){
        var code = $("#ts-coupon-code").val().trim();
        if(!code){ $("#ts-coupon-msg").html('<span class="text-danger">Please enter a coupon code</span>'); return; }
        $("#ts-coupon-msg").html('<span class="text-muted">Checking...</span>');
        $.ajax({
            url: "/apply-coupon-test-series",
            method: "POST",
            data: { coupon: code, test_series_id: testId, amount: tsPrice },
            success: function(res){
                if(res.status == 'success'){
                    tsCouponId = res.coupon_id;
                    $("#ts-coupon-msg").html('<span class="text-success">' + res.message + '</span>');
                    $("#ts-discount-amt").text(res.discount);
                    $("#ts-final-amt").text(res.final_amount);
                    $("#ts-coupon-summary").removeClass('d-none');
                } else {
                    tsCouponId = '';
                    $("#ts-coupon-msg").html('<span class="text-danger">' + res.message + '</span>');
                    $("#ts-coupon-summary").addClass('d-none');
                }
            },
            error: function(){
                $("#ts-coupon-msg").html('<span class="text-danger">Something went wrong. Try again.</span>');
            }
        });
    });

    // Proceed to pay with coupon
    $("#proceed-to-pay").click(function(){
        var url = "/buy-test/" + testId;
        if(tsCouponId){
            url += "?coupon_id=" + tsCouponId;
        }
        window.location.href = url;
    });

    // Skip coupon and pay full price
    $("#skip-coupon").click(function(){
        window.location.href = "/buy-test/" + testId;
    });

    // AJAX Login
    $("#loginForm").submit(function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: "{{route('frontend.login.ajax')}}",
            method: "POST",
            data: form.serialize(),
            success: function(res){
                if(res.success){
                      // After login, show coupon modal
                      tsCouponId = '';
                      $("#ts-coupon-code").val('');
                      $("#ts-coupon-msg").html('');
                      $("#ts-coupon-summary").addClass('d-none');
                      $("#coupon-ts-name").text(tsName || 'Test Series');
                      $("#coupon-ts-price").text(tsPrice || '');
                      $("#ts-final-amt").text(tsPrice || '');
                      $("#authModal").modal("hide");
                      $("#couponModal").modal("show");
                } else {
                    alert(res.message);
                }
            }
        });
    });

    // AJAX Register
    $("#registerForm").submit(function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: "{{route('frontend.register.ajax')}}",
            method: "POST",
            data: form.serialize(),
            success: function(res){
                if(res.success){
                     // After registration, show coupon modal
                    tsCouponId = '';
                    $("#ts-coupon-code").val('');
                    $("#ts-coupon-msg").html('');
                    $("#ts-coupon-summary").addClass('d-none');
                    $("#coupon-ts-name").text(tsName || 'Test Series');
                    $("#coupon-ts-price").text(tsPrice || '');
                    $("#ts-final-amt").text(tsPrice || '');
                    $("#authModal").modal("hide");
                    $("#couponModal").modal("show");
                } else {
                    alert(res.message);
                }
            }
        });
    });
});

});
</script>

<script>
    // Countdown Timer with Session Storage and Dynamic Seat Availability
function initializeCountdown() {
    // Check if we already have a countdown in session storage
    let countdownEnd = sessionStorage.getItem('countdownEnd');
    let seatData = sessionStorage.getItem('seatAvailability');
    
    if (!countdownEnd) {
        // Set countdown for 3 days, 5 hours, and 30 seconds from now
        const targetDate = new Date();
        targetDate.setDate(targetDate.getDate() + 3);
        targetDate.setHours(targetDate.getHours() + 5);
        targetDate.setSeconds(targetDate.getSeconds() + 30);
        
        countdownEnd = targetDate.getTime();
        sessionStorage.setItem('countdownEnd', countdownEnd);
        
        // Initialize seat availability starting from 50
        initializeSeatAvailability();
    } else if (!seatData) {
        initializeSeatAvailability();
    }
    
    return parseInt(countdownEnd);
}

function initializeSeatAvailability() {
    const now = new Date();
    const seatAvailability = {
        value: 50, // Start with 50 seats
        lastUpdated: now.getTime(),
        countdownStage: getCountdownStage(now),
        initialValue: 50 // Remember we started with 50
    };
    sessionStorage.setItem('seatAvailability', JSON.stringify(seatAvailability));
    updateSeatDisplay(seatAvailability.value);
}

function generateSeatValue(currentTime) {
    const hours = currentTime.getHours();
    const minutes = currentTime.getMinutes();
    
    // Generate random value between 3-50 based on time of day
    // Starting from 50, gradually decreasing
    let minSeats = 3;
    let maxSeats = 50;
    
    if (hours >= 9 && hours < 18) {
        // Peak hours: more seats available (20-50)
        minSeats = 20;
        maxSeats = 50;
    } else if (hours >= 18 && hours < 22) {
        // Evening: moderate availability (10-35)
        minSeats = 10;
        maxSeats = 35;
    } else {
        // Night/Early morning: low availability (3-20)
        minSeats = 3;
        maxSeats = 20;
    }
    
    return Math.floor(Math.random() * (maxSeats - minSeats + 1)) + minSeats;
}

function getCountdownStage(currentTime) {
    const countdownEnd = parseInt(sessionStorage.getItem('countdownEnd'));
    const totalDuration = countdownEnd - currentTime.getTime();
    const daysLeft = Math.floor(totalDuration / (1000 * 60 * 60 * 24));
    
    if (daysLeft >= 2) return 'early';
    if (daysLeft >= 1) return 'mid';
    return 'final';
}

function updateSeatAvailability() {
    const seatData = JSON.parse(sessionStorage.getItem('seatAvailability') || '{}');
    const currentTime = new Date();
    const currentStage = getCountdownStage(currentTime);
    
    let updatedValue = seatData.value;
    
    // Only update if countdown stage changed or it's a new day
    if (seatData.countdownStage !== currentStage || 
        isNewDay(seatData.lastUpdated, currentTime)) {
        
        // When stage changes, generate new random value but ensure it's decreasing
        const newRandomValue = generateSeatValue(currentTime);
        
        // Ensure the new value is less than or equal to previous value (seats are selling)
        updatedValue = Math.min(seatData.value, newRandomValue);
        
        const newSeatData = {
            value: updatedValue,
            lastUpdated: currentTime.getTime(),
            countdownStage: currentStage,
            initialValue: seatData.initialValue || 50
        };
        
        sessionStorage.setItem('seatAvailability', JSON.stringify(newSeatData));
    }
    
    // Gradually decrease seats as countdown progresses (psychological effect)
    const countdownEnd = parseInt(sessionStorage.getItem('countdownEnd'));
    const timeLeft = countdownEnd - currentTime.getTime();
    const totalDuration = 3 * 24 * 60 * 60 * 1000; // 3 days total
    const progressRatio = 1 - (timeLeft / totalDuration);
    
    // Calculate seats based on progress - start from 50 and decrease
    const initialSeats = seatData.initialValue || 50;
    const minSeatsAtEnd = 3; // Minimum seats when countdown ends
    
    // Linear decrease from 50 to 3 over the countdown period
    const calculatedSeats = Math.max(
        minSeatsAtEnd,
        Math.floor(initialSeats - (progressRatio * (initialSeats - minSeatsAtEnd)))
    );
    
    // Use the minimum between calculated decrease and current random value
    const finalSeats = Math.min(updatedValue, calculatedSeats);
    
    updateSeatDisplay(finalSeats);
    return finalSeats;
}

function isNewDay(lastUpdated, currentTime) {
    const lastDate = new Date(lastUpdated);
    return lastDate.getDate() !== currentTime.getDate() || 
           lastDate.getMonth() !== currentTime.getMonth() ||
           lastDate.getFullYear() !== currentTime.getFullYear();
}

function updateSeatDisplay(seatCount) {
    const seatElement = document.querySelector('.urgency-note');
    if (seatElement) {
        let urgencyText = '';
        let icon = '🏅';
        
        if (seatCount <= 5) {
            urgencyText = `🚨 <strong>Almost Gone!</strong> Only ${seatCount} spot${seatCount > 1 ? 's' : ''} left!`;
            icon = '🚨';
        } else if (seatCount <= 15) {
            urgencyText = `🔥 <strong>Selling Fast!</strong> Only ${seatCount} spots available`;
            icon = '🔥';
        } else if (seatCount <= 25) {
            urgencyText = `⚡ <strong>Limited Seats!</strong> Only ${seatCount} spots available`;
            icon = '⚡';
        } else if (seatCount <= 35) {
            urgencyText = `🎯 <strong>Limited Seats!</strong> Only ${seatCount} spots available`;
            icon = '🎯';
        } else {
            urgencyText = `🏅 <strong>Limited Seats!</strong> Only ${seatCount} spots available`;
            icon = '🏅';
        }
        
        seatElement.innerHTML = urgencyText;
        
        // Also update the CTA button text based on availability
        const ctaButton = document.querySelector('.cta-button');
        if (ctaButton) {
            if (seatCount <= 5) {
                ctaButton.textContent = 'Grab Last Spot Now!';
                ctaButton.style.background = 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)';
            } else if (seatCount <= 15) {
                ctaButton.textContent = 'Enroll Now - Selling Fast!';
            } else {
                ctaButton.textContent = 'Enroll in Olympiad Test Series Now!';
            }
        }
    }
}

function updateCountdown() {
    const countdownEnd = initializeCountdown();
    const now = new Date().getTime();
    const distance = countdownEnd - now;
    
    // Calculate days, hours, minutes, seconds
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Safely update the display elements
    const daysElement = document.getElementById("days");
    const hoursElement = document.getElementById("hours");
    const minutesElement = document.getElementById("minutes");
    const secondsElement = document.getElementById("seconds");
    const countdownContainer = document.getElementById("countdown");
    
    if (daysElement) daysElement.textContent = days.toString().padStart(2, '0');
    if (hoursElement) hoursElement.textContent = hours.toString().padStart(2, '0');
    if (minutesElement) minutesElement.textContent = minutes.toString().padStart(2, '0');
    if (secondsElement) secondsElement.textContent = seconds.toString().padStart(2, '0');
    
    // Update seat availability based on countdown progress
    updateSeatAvailability();
    
    // If the countdown is over
    if (distance < 0) {
        clearInterval(countdownTimer);
        if (countdownContainer) {
            countdownContainer.innerHTML = "<div class='expired-message'>REGISTRATION CLOSED</div>";
        }
        
        // Update seat display to show sold out
        const seatElement = document.querySelector('.urgency-note');
        if (seatElement) {
            seatElement.innerHTML = "❌ <strong>Sold Out!</strong> All 50 spots taken";
        }
        
        // Update CTA button
        const ctaButton = document.querySelector('.cta-button');
        if (ctaButton) {
            ctaButton.textContent = 'Registration Closed';
            ctaButton.style.background = '#6b7280';
            ctaButton.disabled = true;
        }
        
        // Reset countdown after 10 seconds for demo purposes
        setTimeout(() => {
            sessionStorage.removeItem('countdownEnd');
            sessionStorage.removeItem('seatAvailability');
            location.reload();
        }, 10000);
    }
}

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM loaded, initializing countdown and seats...");
    
    // Initialize countdown and update every second
    updateCountdown();
    const countdownTimer = setInterval(updateCountdown, 1000);
    
    // Update seats every 30 seconds (for gradual decrease effect)
    setInterval(updateSeatAvailability, 30000);
    
    // CTA Button Click Handler
    const ctaButton = document.querySelector('.cta-button');
    if (ctaButton) {
        ctaButton.addEventListener('click', function() {
            const testSeriesPage = document.querySelector('.test-series-page');
            if (testSeriesPage) {
                testSeriesPage.scrollIntoView({
                    behavior: 'smooth'
                });
                
                // Simulate seat reduction when someone clicks to enroll
                setTimeout(() => {
                    const seatData = JSON.parse(sessionStorage.getItem('seatAvailability') || '{}');
                    if (seatData.value > 1) {
                        seatData.value -= 1;
                        sessionStorage.setItem('seatAvailability', JSON.stringify(seatData));
                        updateSeatDisplay(seatData.value);
                    }
                }, 2000);
            }
        });
    }
});
</script>


<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
  let client;

  window.onload = function () {
    client = google.accounts.oauth2.initTokenClient({
      client_id: '97120117675-d30lp7k4gq20f3qbl6p0arru03600sk8.apps.googleusercontent.com',
      scope: 'email profile openid',
      callback: handleCredentialResponse
    });

    document.getElementById('googleLoginBtn').addEventListener('click', () => {
      client.requestAccessToken();
    });
  }

  function handleCredentialResponse(response) {
    fetch('https://www.googleapis.com/oauth2/v3/userinfo', {
      headers: { Authorization: `Bearer ${response.access_token}` }
    })
      .then(res => res.json())
      .then(user => {
        // Send to Laravel backend
        return fetch('/auth/google', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            access_token: response.access_token,
            user,
            redirect:"/buy-test/" +  $("#selected_test").val()
          })
        });
      })
      .then(res => res.json())
      .then(data => {
        if (data.redirect_url) {
          window.location.href = data.redirect_url;
        } else {
          alert('Login successful');
        }
      })
      .catch(err => console.error('Google login error:', err));
  }
</script>
<script>
  const cities = ["Delhi", "Mumbai", "Kolkata", "Chennai", "Pune", "Patna", "Bhopal", "Ahmedabad", "Lucknow"];
  const gradients = [
    "linear-gradient(135deg, #FF512F, #DD2476)",
    "linear-gradient(135deg, #1D976C, #93F9B9)",
    "linear-gradient(135deg, #F7971E, #FFD200)",
    "linear-gradient(135deg, #6A11CB, #2575FC)",
    "linear-gradient(135deg, #11998E, #38EF7D)",
    "linear-gradient(135deg, #FC466B, #3F5EFB)"
  ];

  function getRandomCourse() {
    const select = document.getElementById("courseSelector");
    const options = Array.from(select.options).filter(opt => opt.value !== "");
    const randomOption = options[Math.floor(Math.random() * options.length)];
    return randomOption.text; // You can also use randomOption.value if needed
  }

  function showPopup() {
    const city = cities[Math.floor(Math.random() * cities.length)];
    const course = getRandomCourse();
    const popup = document.getElementById("purchasePopup");
    const text = document.getElementById("popupText");
    const gradient = gradients[Math.floor(Math.random() * gradients.length)];
    const secondsAgo = Math.floor(Math.random() * 5) + 1; // 1–5 min ago

    text.innerHTML = `Someone from <span>${city}</span> just purchased <span>${course}</span> ${secondsAgo} min ago 🎉`;
    popup.style.background = gradient;

    popup.classList.add("show");
    setTimeout(() => popup.classList.remove("show"), 3500);
  }

  setTimeout(showPopup, 2000);
  setInterval(showPopup, 10000);
</script>

@stop