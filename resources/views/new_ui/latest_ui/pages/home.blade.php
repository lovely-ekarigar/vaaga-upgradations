@extends('new_ui.latest_ui.app')

@section('content')

<div class="container-fluid herobanner">
    <div class="container">
        <section>
                <div class="heroinformation">
                    <div class="row">
                        <div class="col-md-7">
                            <h1 class="hero-title">Unlock Your Potential with </h1>
                            <h4 class="hero-tagline">Smart Learning & Olympiad Excellence</h4>
                            <p class="hero-description">A complete platform for academic growth, competitive exam <br>preparation, and Olympiad success — guided by expert mentors.</p>
                            <div>
                                <div class="hero-button">Prepare for Olympiads <img src="{{ asset('images/new_ui/header/arrow.png') }}" alt="whatsapp"></div>
                        </div>
                        <div class="col-md-5"></div>
                    </div>
                </div>
        </section>
    </div>
</div>

<section class="section-counter">
    <div class="container-fluid">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h1 class="counter-title">200+ </h1>
                        <p class="counter-description">Courses offered</p>
                    </div>
                    <div class="col-md-3">
                        <h1 class="counter-title">2500+ </h1>
                        <p class="counter-description">Happy Students</p>
                    </div>
                    <div class="col-md-3">
                        <h1 class="counter-title">95+ </h1>
                        <p class="counter-description">Expert Tutors</p>
                    </div>
                    <div class="col-md-3">
                        <h1 class="counter-title">9000+ </h1>
                        <p class="counter-description">Hours taught</p>
                    </div>
                </div>
            </div>
    </div>
</section>

<section class="trial-section">
        <div class="trial-container">
            <!-- LEFT SIDE -->
            <div class="trial-image">
                <div class="trial-overlay">
                    <h2>Book <span>Free Trial</span> Class</h2>
                    <p>
                        Are you ready to take the next step towards achieving your Olympiad success?
                        Let VaaGa Academy be your trusted partner
                    </p>
                </div>
            </div>

            <!-- RIGHT SIDE FORM -->
            <div class="trial-form">
                <form>
                    <input type="text" placeholder="Full Name">
                    <input type="text" placeholder="Phone Number">
                    <input type="email" placeholder="Email">

                    <select>
                        <option>Select Course</option>
                        <option>Math Olympiad</option>
                        <option>Science Olympiad</option>
                        <option>Foundation Course</option>
                    </select>

                    <div class="captcha">
                        <input type="checkbox"> I'm not a robot
                    </div>

                    <button type="submit">SUBMIT</button>
                </form>
            </div>

        </div>
</section>

<section class="images">
    <div class="container">
        <div class="images-row">
            <img src="https://vaagastag.ekarigar.com/images/new_ui/image-1.png" alt="image-1">
            <img src="https://vaagastag.ekarigar.com/images/new_ui/image-2.png" alt="image-2">
            <img src="https://vaagastag.ekarigar.com/images/new_ui/image-3.png" alt="image-3">
        </div>
    </div>
</section>

<section class="about">
    <div class="container">
        <!--row one-->
        <div class="row">
            <div class="col-md-6">
                <div class="about-information">
                    <p><span class="tagline">About Us</span></p>
                    <div class="info">
                        <h2>About <span style="color: #FEBC5A;">VaaGa Academy</span></h2>
                        <p>Empowering students with strong fundamentals, smart learning strategies, and Olympiad-focused excellence.</p>
                        <p>VaaGa Academy is a modern learning platform dedicated to helping students achieve academic excellence and succeed in competitive environments. Our approach focuses on building strong conceptual foundations, improving problem-solving skills, and fostering analytical thinking.<br>
                          <br> We go beyond traditional learning by integrating structured programs for specialized Olympiad preparation, ensuring students are prepared for both classroom performance and higher-level competitions.</p>
                          <br>
                        <div class="info-btn">Explore Programs</div>
                    </div>
                </div>
                
            </div>
            <div class="col-md-6">
                <img src="https://vaagastag.ekarigar.com/images/new_ui/about.png" alt="image-1">
            </div>
        </div>
        <!---row two-->
        <div class="row">
            <div class="col-md-6">
                <img src="https://vaagastag.ekarigar.com/images/new_ui/about.png" alt="image-1">
            </div>
            <div class="col-md-6">
                <div class="about-information">
                    <br>
                    <p><span class="tagline">Book a Free Demo</span></p>
                    <div class="info-2">
                        <h2>Experience<span style="color: #FEBC5A;"> Before You Join</span></h2>
                        <p>Discover how our expert tutors, interactive sessions, and personalized approach can help you achieve better academic results. Join a live demo class to understand our teaching style, course structure, and platform features.</p>
                        <br>
                        <h5><b>What You’ll Get:</b></h5>
        
                        <ul class="demo-list">
                            <li>Live interactive class experience</li>
                            <li>Introduction to our Olympiad-focused curriculum</li>
                            <li>One-on-one guidance and doubt-solving approach</li>
                            <li>Overview of courses and learning methodology</li>
                        </ul>
        
                        <br>
        
                        <div class="info-btn">Book Free Demo</div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<section class="choose-us">
    <div class="container">
        <div class="text-center">
            <p><span class="tagline">WHY CHOOSE US</span></p>
            <h2>Why Students Prefer Us</h2>
            <p class="section-subtitle">A smarter, structured approach to learn that help Students excel in academics and Olympiad exams.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="box featured">
                    <div class="icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <p>Expert Faculty with proven teaching experience</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="box">
                    <div class="icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <p>Structured learning paths for competitive exams</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="box">
                    <div class="icon"><i class="bi bi-trophy-fill"></i></div>
                    <p>Dedicated Olympiad preparation modules</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="box">
                    <div class="icon"><i class="bi bi-laptop-fill"></i></div>
                    <p>Interactive live and recorded classes</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="box">
                    <div class="icon"><i class="bi bi-bullseye"></i></div>
                    <p>Performance tracking and personalized feedback</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="box">
                    <div class="icon"><i class="bi bi-book-fill"></i></div>
                    <p>Self learning preparation modules</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="our-programs">
    <div class="container">
        <div class="programs-header">
            <div class="header-left">
                <span class="tagline">Courses</span>
                <h2>Explore Our Programs</h2>
                <p class="subtitle">Comprehensive Courses Designed To Strengthen Academics, Build Problem-Solving Skills, And Prepare For Olympiad Success.</p>
            </div>
            <div class="header-right">
                <a href="#" class="btn-explore">Explore All Courses</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="program-card featured">
                    <img src="https://placehold.co/500x400/0d6f7e/ffffff?text=Olympiad" alt="Olympiad Preparation" class="card-img">
                    <div class="card-body">
                        <h3>Olympiad Preparation</h3>
                        <p>Specialized programs designed to prepare students for national and international Olympiad exams.</p>
                        <div class="includes-title">Includes:</div>
                        <ul>
                            <li>Math, Science & English Olympiad</li>
                            <li>Previous year papers</li>
                            <li>Regular Mock tests & analysis</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="program-card">
                    <img src="https://placehold.co/500x400/d9d9d9/333?text=Live+Classes" alt="Live Classes" class="card-img">
                    <div class="card-body">
                        <h3>Live Classes</h3>
                        <p>Interactive live sessions with expert educators to ensure real-time learning and doubt resolution.</p>
                        <div class="includes-title">Includes:</div>
                        <ul>
                            <li>Scheduled live sessions</li>
                            <li>Q&A interaction</li>
                            <li>Instant feedback</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="program-card">
                    <img src="https://placehold.co/500x400/d9d9d9/333?text=Recorded" alt="Recorded Sessions" class="card-img">
                    <div class="card-body">
                        <h3>Recorded Sessions</h3>
                        <p>View high-quality recorded live sessions anytime, anywhere for quick revision.</p>
                        <div class="includes-title">Includes:</div>
                        <ul>
                            <li>Quick access</li>
                            <li>Revision-friendly format</li>
                            <li>Flexible learning</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="program-card">
                    <img src="https://placehold.co/500x400/d9d9d9/333?text=Study+Material" alt="Study Material & Resources" class="card-img">
                    <div class="card-body">
                        <h3>Study Material & Resources</h3>
                        <p>Specialized resources designed to help students prepare for Olympiad exams.</p>
                        <div class="includes-title">Includes:</div>
                        <ul>
                            <li>Math, Science & English Olympiad</li>
                            <li>Regular practice worksheets</li>
                            <li>Pre-Built Study Material</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="trial-banner">
    <div class="trial-bg">
        <div class="container">
            <div class="trial-content-wrapper">
                <div class="trial-card">
                    <h2>Book a Free Trial Class</h2>
                    <p>Book your free demo session and experience our interactive teaching approach firsthand. Understand how our expert tutors and personalized learning methods can help you improve performance and achieve better results—all from the comfort of your home.</p>
                </div>
                <div class="trial-deco-line"></div>
            </div>
        </div>
    </div>
</section>

<section class="student-growth">
    <div class="container">
        <div class="growth-layout">
            <div class="growth-image d-none d-lg-block">
                <img src="https://placehold.co/400x500/333/fff?text=Student" alt="Student studying">
            </div>
            <div class="growth-content">
                <span class="tagline-gold">Student Growth Focus</span>
                <h2>Olympiad Exams</h2>
                <p>Olympiad exams are not just tests—they are a gateway to developing deeper understanding and critical thinking. Our program helps students go beyond textbooks, encouraging curiosity, logic, and real-world application of concepts.</p>
                <p>With continuous practice, expert mentoring, and performance tracking, we ensure every student progresses with confidence and clarity.</p>
                <div class="growth-features">
                    <div class="feature-item"><span class="check-box"></span> High-quality, exam-aligned study material</div>
                    <div class="feature-item"><span class="check-box"></span> Regular instead of Weekly &amp; Detailed reports</div>
                    <div class="feature-item"><span class="check-box"></span> Time management &amp; accuracy training</div>
                    <div class="feature-item"><span class="check-box"></span> Previous year question practice</div>
                    <div class="feature-item"><span class="check-box"></span> Competitive exam readiness</div>
                    <div class="feature-cta"><a href="#" class="btn-gold">Enroll Now</a></div>
                </div>
            </div>
            <div class="growth-image d-none d-lg-block">
                <img src="https://placehold.co/400x500/333/fff?text=Student" alt="Student with laptop">
            </div>
        </div>
    </div>
</section>

<section class="academic-support">
    <div class="container">
        <div class="support-header">
            <div class="header-left">
                <span class="tagline">Academic Classes</span>
                <h2>Complete Academic Support</h2>
            </div>
            <div class="header-right">
                <p>Boost School Performance With Our Academic Excellence Classes CBSE, ICSE And Other Boards — Focused On Clear Concepts, Regular Practice, And Exam Readiness.</p>
            </div>
        </div>
        <div class="support-gallery">
            <!-- Card 1: Image + "What You Get" list below -->
            <div class="gallery-card card-info">
                <div class="card-image-top">
                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Study space">
                </div>
                <div class="card-body">
                    <h4>What You Get:</h4>
                    <ul>
                        <li>Complete syllabus coverage</li>
                        <li>Chapter-wise concept clarity</li>
                        <li>Regular tests &amp; revision sessions</li>
                        <li>Doubt-solving with expert teachers</li>
                        <li>Homework support &amp; exam preparation</li>
                        <li>Exam preparation &amp; Readiness</li>
                    </ul>
                </div>
            </div>
            <!-- Card 2: Image only -->
            <div class="gallery-card">
                <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Student studying">
            </div>
            <!-- Card 3: Image with CTA button -->
            <div class="gallery-card card-cta">
                <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Study lamp">
                <a href="#" class="btn-seat-card">Book Your Seat</a>
            </div>
            <!-- Card 4: Image only -->
            <div class="gallery-card">
                <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Study desk">
            </div>
        </div>
    </div>
</section>

<section class="anytime-learning">
    <div class="container">
        <div class="anytime-row">
            <div class="anytime-image">
                <img src="https://placehold.co/600x500/d9d9d9/333?text=Self+Learning" alt="Self Learning">
            </div>
            <div class="anytime-content">
                <span class="tagline-dark">Self Learning</span>
                <h2>Anytime Learning</h2>
                <p>Empower students to learn at their own pace with our flexible Self Learning programs. Designed for independent study, this mode allows learners to access high-quality content anytime, anywhere—perfect for revision, practice, and concept mastery.</p>
                <h4>Features</h4>
                <ul>
                    <li>Recorded video lectures</li>
                    <li>Topic-wise study modules</li>
                    <li>Mock Test Series</li>
                    <li>Anytime, anywhere access</li>
                    <li>Self-paced progress tracking</li>
                </ul>
                <a href="#" class="btn-enroll">Enroll Now</a>
            </div>
        </div>
    </div>
</section>

<section class="how-it-works">
    <div class="container">
        <!-- Bordered Header -->
        <div class="works-header-box">
            <div class="header-left">
                <span class="tagline">HOW IT WORKS</span>
                <h2>Your Path to Success</h2>
                <p>A Simple And Structured Process To Start Learning And Excel In Academics And Olympiad Exams.</p>
            </div>
            <div class="header-right">
                <a href="#" class="btn-started">Get Started</a>
            </div>
        </div>

        <!-- Timeline — CSS-only S-curve -->
        <div class="works-timeline">
            <!-- CSS S-curve path: 5 horizontal lines + 4 semicircular curves -->
            <div class="s-curve-path">
                <div class="sc-line sc-line-1"></div>
                <div class="sc-line sc-line-2"></div>
                <div class="sc-line sc-line-3"></div>
                <div class="sc-line sc-line-4"></div>
                <div class="sc-line sc-line-5"></div>

                <div class="sc-curve sc-curve-left sc-curve-1"></div>
                <div class="sc-curve sc-curve-right sc-curve-2"></div>
                <div class="sc-curve sc-curve-left sc-curve-3"></div>
                <div class="sc-curve sc-curve-right sc-curve-4"></div>
            </div>

            <!-- Step 1 -->
            <div class="timeline-step step-1 step-right">
                <div class="step-badge"><span>Step 1</span></div>
                <div class="step-inner">
                    <div class="step-content">
                        <h3>Choose Your Program</h3>
                        <p>Select the right course based on your class, goals, and interest in Olympiad preparation.</p>
                    </div>
                    <div class="step-image">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Choose Your Program">
                    </div>
                </div>
            </div>
            <!-- Step 2 -->
            <div class="timeline-step step-2 step-left">
                <div class="step-badge"><span>Step 2</span></div>
                <div class="step-inner">
                    <div class="step-image">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Get Instant Access">
                    </div>
                    <div class="step-content">
                        <h3>Get Instant Access</h3>
                        <p>Unlock your learning dashboard with access to live classes, recorded lectures, and study material.</p>
                    </div>
                </div>
            </div>
            <!-- Step 3 -->
            <div class="timeline-step step-3 step-right">
                <div class="step-badge"><span>Step 3</span></div>
                <div class="step-inner">
                    <div class="step-content">
                        <h3>Learn & Practice</h3>
                        <p>Attend interactive sessions, complete assignments, and solve Olympiad-level mock tests.</p>
                    </div>
                    <div class="step-image">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Learn & Practice">
                    </div>
                </div>
            </div>
            <!-- Step 4 -->
            <div class="timeline-step step-4 step-left">
                <div class="step-badge"><span>Step 4</span></div>
                <div class="step-inner">
                    <div class="step-image">
                        <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Test & Improve">
                    </div>
                    <div class="step-content">
                        <h3>Test & Improve</h3>
                        <p>Take regular tests, analyze your performance, and improve with expert feedback and guidance.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container">
        <div class="features-header">
            <div class="header-left">
                <span class="tagline">Features And Benefits</span>
                <h2>Everything <span>You Need to Succeed</span></h2>
                <p>A Complete Learning Ecosystem Designed For Academic Excellence And Olympiad Success.</p>
            </div>
            <div class="header-right">
                <a href="#" class="btn-prep">Start Olympiad Prep</a>
            </div>
        </div>
        <div class="features-interactive" id="features-interactive">
            <!-- Left: Sticky Image Stack -->
            <div class="features-image-sticky">
                <div class="features-image-stack">
                    <div class="feature-img-panel active" data-feature="0">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Live & Interactive Classes">
                    </div>
                    <div class="feature-img-panel" data-feature="1">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Recorded Lectures">
                    </div>
                    <div class="feature-img-panel" data-feature="2">
                        <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Structured Study Material">
                    </div>
                    <div class="feature-img-panel" data-feature="3">
                        <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Olympiad Practical Sets">
                    </div>
                    <div class="feature-img-panel" data-feature="4">
                        <img src="https://images.unsplash.com/photo-1501504905252-473c47e087f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Regular Mock Tests">
                    </div>
                    <div class="feature-img-panel" data-feature="5">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Doubt Solving Support">
                    </div>
                    <div class="feature-img-panel" data-feature="6">
                        <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Performance Analytics">
                    </div>
                    <div class="feature-img-panel" data-feature="7">
                        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Expert Mentorship">
                    </div>
                </div>
            </div>

            <!-- Right: Scrolling Badge List -->
            <div class="features-badge-list">
                <div class="feature-badge active" data-feature="0">
                    <div class="feature-tag"><span class="f-num">01</span><span class="f-title">Live & Interactive Classes</span></div>
                    <p class="badge-desc">Engage in real-time sessions with expert educators, designed to simplify concepts and encourage active participation.</p>
                </div>
                <div class="feature-badge" data-feature="1">
                    <div class="feature-tag"><span class="f-num">02</span><span class="f-title">Recorded Lectures</span></div>
                    <p class="badge-desc">Access high-quality recorded sessions anytime, allowing you to learn at your own pace and revise easily.</p>
                </div>
                <div class="feature-badge" data-feature="2">
                    <div class="feature-tag"><span class="f-num">03</span><span class="f-title">Structured Study Material</span></div>
                    <p class="badge-desc">Well-organized notes, worksheets, and resources aligned with Olympiad curriculum.</p>
                </div>
                <div class="feature-badge" data-feature="3">
                    <div class="feature-tag"><span class="f-num">04</span><span class="f-title">Olympiad Practical Sets</span></div>
                    <p class="badge-desc">Advanced-level questions and topic-wise practice designed to prepare students for national and international Olympiad exams.</p>
                </div>
                <div class="feature-badge" data-feature="4">
                    <div class="feature-tag"><span class="f-num">05</span><span class="f-title">Regular Mock Tests</span></div>
                    <p class="badge-desc">Regular assessments to evaluate progress, improve accuracy, and build exam confidence.</p>
                </div>
                <div class="feature-badge" data-feature="5">
                    <div class="feature-tag"><span class="f-num">06</span><span class="f-title">Doubt Solving Support</span></div>
                    <p class="badge-desc">Get your queries resolved quickly through dedicated doubt-clearing sessions and mentor support.</p>
                </div>
                <div class="feature-badge" data-feature="6">
                    <div class="feature-tag"><span class="f-num">07</span><span class="f-title">Performance Analytics</span></div>
                    <p class="badge-desc">Track your progress with detailed reports, identify weak areas, and improve strategically.</p>
                </div>
                <div class="feature-badge" data-feature="7">
                    <div class="feature-tag"><span class="f-num">08</span><span class="f-title">Expert Mentorship</span></div>
                    <p class="badge-desc">Learn from experienced educators who guide you through academic challenges and Olympiad preparation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="testimonials">
    <div class="container">
        <div class="testimonials-header">
            <div class="header-left">
                <span class="tagline">Testimonials</span>
                <h2>What Students Say</h2>
                <p>Real experiences from students achieving academic success and excelling in Olympiad exams.</p>
            </div>
            <div class="header-right">
                <div class="carousel-nav">
                    <button class="nav-arrow prev"><i class="bi bi-arrow-left"></i></button>
                    <div class="nav-dots">
                        <span class="dot active"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                    <button class="nav-arrow next"><i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-video">
                <img src="https://placehold.co/600x400/333/fff?text=Video+Testimonial" alt="Video testimonial">
                <div class="play-button">
                    <i class="bi bi-play-fill"></i>
                </div>
            </div>
            <div class="testimonial-cards">
                <div class="t-card t-dark">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Riya Sharma</strong>
                        <span>Class 9 Student</span>
                    </div>
                </div>
                <div class="t-card t-teal">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Riya Sharma</strong>
                        <span>Class 9 Student</span>
                    </div>
                </div>
                <div class="t-card t-light">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Riya Sharma</strong>
                        <span>Class 9 Student</span>
                    </div>
                </div>
                <div class="t-card t-teal">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Aarav Patel</strong>
                        <span>Class 8 Student</span>
                    </div>
                </div>
                <div class="t-card t-light">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Priya Gupta</strong>
                        <span>Class 10 Student</span>
                    </div>
                </div>
                <div class="t-card t-dark">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Karan Singh</strong>
                        <span>Class 7 Student</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // TESTIMONIALS CAROUSEL
    var cardsContainer = document.querySelector('.testimonial-cards');
    var prevBtn = document.querySelector('.carousel-nav .nav-arrow.prev');
    var nextBtn = document.querySelector('.carousel-nav .nav-arrow.next');
    var dots = document.querySelectorAll('.carousel-nav .nav-dots .dot');

    if (cardsContainer && prevBtn && nextBtn && dots.length) {
        var currentIndex = 0;
        var totalCards = cardsContainer.querySelectorAll('.t-card').length;
        var cardWidth = 320 + 16; // card width + gap

        function updateDots(index) {
            dots.forEach(function (dot, i) {
                dot.classList.toggle('active', i === index);
            });
        }

        function scrollToIndex(index) {
            if (index < 0) index = totalCards - 1;
            if (index >= totalCards) index = 0;

            cardsContainer.scrollTo({
                left: cardWidth * index,
                behavior: 'smooth'
            });

            currentIndex = index;
            updateDots(currentIndex);
        }

        prevBtn.addEventListener('click', function () {
            scrollToIndex(currentIndex - 1);
        });

        nextBtn.addEventListener('click', function () {
            scrollToIndex(currentIndex + 1);
        });

        dots.forEach(function (dot, index) {
            dot.addEventListener('click', function () {
                scrollToIndex(index);
            });
        });
    }

    // ============================================================
    // COUNTER ANIMATION
    // ============================================================
    var counterSection = document.querySelector('.section-counter');
    var counters       = document.querySelectorAll('.counter-title');

    if (counterSection && counters.length) {
        var animated = false;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting && !animated) {
                    animated = true;

                    counters.forEach(function (counter) {
                        var targetText  = counter.textContent.trim();
                        var targetValue = parseInt(targetText.replace(/\D/g, ''), 10);
                        var suffix      = targetText.replace(/[\d\s]/g, '');
                        var duration    = 3000;
                        var startTime   = performance.now();

                        function easeOutCubic(t) {
                            return 1 - Math.pow(1 - t, 3);
                        }

                        function updateCounter(currentTime) {
                            var elapsed  = currentTime - startTime;
                            var progress = Math.min(elapsed / duration, 1);
                            var current  = Math.floor(easeOutCubic(progress) * targetValue);

                            counter.textContent = current + suffix;

                            if (progress < 1) {
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.textContent = targetValue + suffix;
                            }
                        }

                        requestAnimationFrame(updateCounter);
                    });

                    observer.unobserve(counterSection);
                }
            });
        }, { threshold: 0.3 });

        observer.observe(counterSection);
    }

    // FEATURES INTERACTIVE — Scroll-based image switching
    (function() {
        var section = document.getElementById('features-interactive');
        if (!section) return;

        var badges = section.querySelectorAll('.feature-badge');
        var images = section.querySelectorAll('.feature-img-panel');
        if (!badges.length || !images.length) return;

        var activeIndex = 0;
        var isScrolling = false;
        var scrollTimeout;

        function setActive(index) {
            if (index === activeIndex) return;
            activeIndex = index;

            // Update badges
            badges.forEach(function(b, i) {
                b.classList.toggle('active', i === index);
            });

            // Update images with smooth transition
            images.forEach(function(img, i) {
                img.classList.toggle('active', i === index);
            });
        }

        // Intersection Observer for scroll-based activation
        var observerOptions = {
            root: null,
            rootMargin: '-35% 0px -35% 0px',
            threshold: 0
        };

        var badgeObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var idx = parseInt(entry.target.getAttribute('data-feature'), 10);
                    setActive(idx);
                }
            });
        }, observerOptions);

        badges.forEach(function(badge) {
            badgeObserver.observe(badge);
        });

        // Click to jump
        badges.forEach(function(badge) {
            badge.addEventListener('click', function() {
                var idx = parseInt(this.getAttribute('data-feature'), 10);
                setActive(idx);
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });

        // Set first active on load
        setActive(0);
    })();
});
</script>
@endsection