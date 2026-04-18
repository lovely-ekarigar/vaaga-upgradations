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
                    <div class="feature-item"><span class="check-box"></span> Regular Test &amp; Weekly Detailed reports</div>
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
        <div class="support-body">
            <div class="support-list">
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
            <div class="support-gallery">
                <div class="gallery-card">
                    <img src="https://placehold.co/400x600/d9d9d9/333?text=Study" alt="Study space">
                </div>
                <div class="gallery-card featured">
                    <img src="https://placehold.co/400x600/d9d9d9/333?text=Student" alt="Student studying">
                    <a href="#" class="btn-seat">Book Your Seat</a>
                </div>
                <div class="gallery-card">
                    <img src="https://placehold.co/400x600/d9d9d9/333?text=Desk" alt="Study desk">
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 30% !important;"></section>
@endsection