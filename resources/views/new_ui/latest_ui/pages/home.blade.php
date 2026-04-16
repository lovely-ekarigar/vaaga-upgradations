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
            <p>A smarter, structured approach to learn that help Students excel in academics and Olympiad exams.</p>
            <br>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <div class="icon"></div>
                    <p>Expert Faculty with proven teaching experience</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="icon"></div>
                    <p>Structured learning paths for competitive exams</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="icon"></div>
                    <p>Dedicated Olympiad preparation modules</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <div class="icon"></div>
                    <p>Interactive live and recorded classes</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="icon"></div>
                    <p>Performance tracking and personalized feedback</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="icon"></div>
                    <p>Self learning preparation modules</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="our-programs">
        <div class="container">
            <div>
            <p><span class="tagline">Courses</span></p>
            <h2>Explore Our Programs</h2>
            <p>Comprehensive courses designed to strengthen academics, build problem-solving skills,<br> and prepare for Olympiad success.</p>
            <br>
            <div class="info-btn">Explore All Courses</div>
        </div>
        </div>
</section>

<section style="padding: 30% !important;"></section>
@endsection